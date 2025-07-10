<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PreVendorCategory;
use App\Models\PreVendorDetail;
use App\Models\PreVendorDetailItem;
use App\Models\PreVendorSubCategory;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorItem;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function index($invitation_code)
    {
        $invitationDetail = PreVendorDetail::with('state', 'city', 'vendorType')->where('invitation_code', $invitation_code)->first();
        if ($invitationDetail->status == 'close') {
            return redirect()->route('login');
        }
        if (empty($invitationDetail)) {
            return redirect()->route('not.found');
        }
        $preVendorDetailItem = PreVendorDetailItem::where('pre_vendor_detail_id', $invitationDetail->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $preVendorDetailCategory = PreVendorDetailItem::where('pre_vendor_detail_id', $invitationDetail->id)->pluck('pre_vendor_category_id',)->toArray();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $preVendorCategories = PreVendorCategory::all();
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $cities = City::where('state_id', $invitationDetail->state_id)->get();
        //$vendor = Vendor::where('invite_vendor_id', $invitationDetail->id)->first();

        $user = User::where('invite_vendor_id', $invitationDetail->id)->where('is_admin_created', '0')->first();
        $vendor = "";
        if ($user != null) {
            $vendor = Vendor::where('user_id', $user->id)->first();
        }

        $categoryArr = [];
        foreach ($preVendorCategories as $category) {
            if (in_array($category->id, $preVendorDetailCategory)) {
                $categoryArr[] = $category->name;
            }
        }

        $subcategoryArr = [];
        foreach ($preVendorSubCategories as $subCategory) {
            if (in_array($subCategory->id, $preVendorDetailItem)) {
                $subcategoryArr[] = $subCategory->name;
            }
        }
        $subCategories = implode(',', $subcategoryArr);
        return view('frontend.invitation.index', compact('subCategories', 'categoryArr', 'subcategoryArr', 'preVendorDetailCategory', 'preVendorCategories', 'preVendorSubCategories', 'vendorTypes', 'states', 'invitationDetail', 'preVendorDetailItem', 'cities', 'vendor'));
    }

    public function store(Request $request, $invitationCode)
    {
        $preVendorDetails = PreVendorDetail::where('invitation_code', $invitationCode)->first();

        $preVendorDetailsId = $preVendorDetails ? $preVendorDetails->id : null;
        $request->validate([
            'name'                  => 'required',
            'email'                 => [
                'required',
                'email',
                Rule::unique('pre_vendor_details', 'email')->ignore($preVendorDetailsId),
                Rule::unique('users', 'email'),
            ],
            'mobile'                => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('pre_vendor_details', 'mobile')->ignore($preVendorDetailsId),
                Rule::unique('users', 'mobile'),
            ],
            'state'                 => 'required',
            'city'                  => 'required',
            'address'               => 'required',
            'password'              => 'required|string|min:6|confirmed',
            'pin_code'              => 'required',
            'contact_person_name'   => 'required',
            'contact_person_email'  => 'email|required',
            'contact_person_mobile' => 'required',
        ]);

        $preVendorDetails->name = $request->name;
        $preVendorDetails->email = $request->email;
        $preVendorDetails->mobile = $request->mobile;
        $preVendorDetails->state_id = $request->state;
        $preVendorDetails->city_id = $request->city;
        $preVendorDetails->address = $request->address;
        $preVendorDetails->status = 'close';
        $preVendorDetails->save();

        $user = User::where('invite_vendor_id', $preVendorDetails->id)->where('is_admin_created', '0')->first();
        if ($user == null) {
            $user = new User();
        }
        $user->invite_vendor_id = $preVendorDetails->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->assignRole('vendor');

        $vendor = Vendor::where('user_id', $user->id)->first();
        if ($vendor == null) {
            $vendor = new Vendor();
        }
        $vendor->user_id = $user->id;
        $vendor->invite_vendor_id = $preVendorDetails->id;
        $vendor->vendor_type_id = $preVendorDetails->vendor_type_id;
        $vendor->address = $request->address;
        $vendor->email = $request->email;
        $vendor->state_id = $request->state;
        $vendor->name_of_contact_person = $request->contact_person_name;
        $vendor->contact_person_email = $request->contact_person_email;
        $vendor->contact_person_mobile_number = $request->contact_person_mobile;
        $vendor->city_id = $request->city;
        $vendor->phone_number_1 = $request->mobile;
        $vendor->pin_code = $request->pin_code;
        $vendor->business_name = $request->name;
        $vendor->register_by_self_id = $user->id;
        $vendor->status = 'pending';
        $vendor->save();

        $branch = Branch::where('vendor_id', $vendor->id)->first();
        if ($branch == null) {
            $branch = new Branch;
        }
        $branch->vendor_id = $vendor->id;
        $branch->email = $request->email;
        $branch->address = $request->address;
        $branch->state_id = $request->state;
        $branch->city_id = $request->city;
        $branch->phone_number_1 = $request->mobile;
        $branch->pin_code = $request->pin_code;
        $branch->is_primary = '1';
        $branch->name_of_contact_person = $request->contact_person_name;
        $branch->contact_person_email = $request->contact_person_email;
        $branch->contact_person_mobile_number = $request->contact_person_mobile;
        $branch->save();

        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->count();

        if ($vendorItems == 0 && is_array($request->sub_category_id)) {
            foreach ($request->sub_category_id as $subCategory) {
                $preVendorDetailsItem = PreVendorDetailItem::where('pre_vendor_sub_category_id', $subCategory)->where('pre_vendor_detail_id', $preVendorDetails->id)->first();
                $vendorData = new VendorItem();
                $vendorData->vendor_id = $vendor->id;
                $vendorData->pre_vendor_sub_category_id = $subCategory;
                if ($preVendorDetailsItem !== null) {
                    $vendorData->pre_vendor_category_id = $preVendorDetailsItem->pre_vendor_category_id;
                }
                $vendorData->save();
            }
        }

        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $adminUser) {
            $notification = new Notification;
            $notification->user_id = $user->id;
            $notification->vendor_id = $adminUser->id;
            $notification->title = $request->name . ' vendor registration successfully';
            $notification->from = 'vendor';
            $notification->module = 'vendor_registration';
            $notification->save();
        }
        Auth::loginUsingId($user->id);
        return redirect()->route('vendor.vendor.detail');
    }
}
