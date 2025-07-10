<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BranchDataTable;
use App\DataTables\VendorDataTable;
use App\Helpers\AuditLogHelper;
use App\Helpers\GenerateStringNumberHelper;
use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminVendor\UpdatePasswordRequest;
use App\Http\Requests\Vendor\BankDetailStoreRequest;
use App\Http\Requests\Vendor\RegistrationDetailStoreRequest;
use App\Http\Requests\Vendor\VendorDetailStoreRequest;
use App\Http\Requests\Vendor\VendorDetailUpdateRequest;
use App\Http\Requests\VendorDocument\VendorDocumentStoreRequest;
use App\Mail\VendorApproveMail;
use App\Mail\VendorPasswordUpdateMail;
use App\Mail\VendorRegistrationMail;
use App\Models\Branch;
use App\Models\BranchDocument;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PreVendorDetail;
use App\Models\PreVendorSubCategory;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocDetail;
use App\Models\VendorDocType;
use App\Models\VendorDocument;
use App\Models\VendorItem;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminVendorController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function index(VendorDataTable $dataTable)
    {
        return $dataTable->render('backend.admin-vendor.index');
    }

    public function create()
    {
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $preVendorSubCategories = PreVendorSubCategory::all();
        return view('backend.admin-vendor.create', compact('vendorTypes', 'states', 'preVendorSubCategories'));
    }

    public function store(VendorDetailStoreRequest $request)
    {
        try {
            $password = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', GenerateStringNumberHelper::generateTimeRandomString(6)));

            $user = new User;
            $user->name = $request->business_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile_number;
            $user->password = bcrypt($password);
            $user->is_admin_created = '1';
            $user->save();

            $user->invite_vendor_id = $user->id;
            $user->save();

            $user->assignRole('vendor');

            $vendor = new Vendor;
            $vendor->invite_vendor_id = $user->id;
            $vendor->user_id = $user->id;
            $vendor->vendor_type_id = $request->vendor_type;
            $vendor->business_name = $request->business_name;
            $vendor->address = $request->address;
            $vendor->state_id = $request->state;
            $vendor->city_id = $request->city;
            $vendor->pin_code = $request->pin_code;
            $vendor->phone_number_1 = $request->mobile_number;
            $vendor->phone_number_2 = $request->phone_number_2;
            $vendor->fax_no = $request->fax_no;
            $vendor->email = $request->email;
            $vendor->name_of_contact_person = $request->name_of_contact_person;
            $vendor->contact_person_mobile_number = $request->contact_person_mobile_number;
            $vendor->contact_person_email = $request->contact_person_email;
            $vendor->created_by_id = Auth::id();
            $vendor->save();

            $branch = new Branch;
            $branch->user_id = Auth::id();
            $branch->vendor_id = $vendor->id;
            $branch->address = $request->address;
            $branch->state_id = $request->state;
            $branch->city_id = $request->city;
            $branch->pin_code = $request->pin_code;
            $branch->phone_number_1 = $request->mobile_number;
            $branch->phone_number_2 = $request->phone_number_2;
            $branch->fax_no = $request->fax_no;
            $branch->email = $request->email;
            $branch->name_of_contact_person = $request->name_of_contact_person;
            $branch->contact_person_mobile_number = $request->contact_person_mobile_number;
            $branch->contact_person_email = $request->contact_person_email;
            $branch->is_primary = '1';
            $branch->save();

            foreach ($request->pre_vendor_sub_category as $subCategory) {
                $preVendorSubCategory = PreVendorSubCategory::where('id', $subCategory)->first();
                $vendorItem = new VendorItem;
                $vendorItem->vendor_id = $vendor->id;
                $vendorItem->pre_vendor_category_id = $preVendorSubCategory->pre_vendor_category_id;
                $vendorItem->pre_vendor_sub_category_id = $subCategory;
                $vendorItem->save();
            }

            if (isset($user->email) && $user->email != '') {
                Mail::to($user->email)->send(new VendorRegistrationMail($user, $password));
            }
            return redirect()->route('vendors.branch.detail', $vendor)->with(['success' => 'Vendor store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendors.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function vendorDetail(Vendor $vendor)
    {
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $vendorItemArray = VendorItem::where('vendor_id', $vendor->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.admin-vendor.vendor-detail', compact('vendor', 'vendorTypes', 'states', 'preVendorSubCategories', 'vendorItemArray', 'vendorItems'));
    }

    public function branchDetail(Vendor $vendor, BranchDataTable $dataTable)
    {
        $vendorTypes = VendorType::all();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $vendorItemArray = VendorItem::where('vendor_id', $vendor->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return $dataTable->render('backend.admin-vendor.branch-detail.index', compact('vendor', 'vendorItems', 'vendorTypes', 'preVendorSubCategories', 'vendorItemArray'));
    }

    public function vendorDetailStore(Vendor $vendor, VendorDetailUpdateRequest $request)
    {
        try {
            $vendor->vendor_type_id = $request->vendor_type;
            $vendor->business_name = $request->business_name;
            $vendor->email = $request->email;
            $vendor->phone_number_1 = $request->mobile_number;

            $updatedValues = $vendor->getDirty();
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $vendor->getOriginal($field);
            }

            $vendor->save();

            $user = User::where('id', $vendor->user_id)->first();
            $user->name = $request->business_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile_number;
            $user->save();

            $branch = Branch::where('vendor_id', $vendor->id)->where('is_primary', '1')->first();
            $branch->email = $request->email;
            $branch->phone_number_1 = $request->mobile_number;
            $branch->save();

            VendorItem::where('vendor_id', $vendor->id)->delete();

            foreach ($request->pre_vendor_sub_category as $subCategory) {
                $preVendorSubCategory = PreVendorSubCategory::where('id', $subCategory)->first();
                $vendorItem = new VendorItem;
                $vendorItem->vendor_id = $vendor->id;
                $vendorItem->pre_vendor_category_id = $preVendorSubCategory->pre_vendor_category_id;
                $vendorItem->pre_vendor_sub_category_id = $subCategory;
                $vendorItem->save();
            }

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Vendor Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function bankDetailStore(BankDetailStoreRequest $request, Vendor $vendor)
    {
        try {
            $vendor->type_of_account = $request->type_of_account;
            $vendor->bank_account_no = $request->bank_account_no;
            $vendor->bank_name = $request->bank_name;
            $vendor->payment_in_favour = $request->payment_in_favour;
            $vendor->bank_branch_name_and_address = $request->bank_branch_name_and_address;
            $vendor->bank_branch_code = $request->bank_branch_code;
            $vendor->bank_ifsc_code = $request->bank_ifsc_code;
            $vendor->save();

            return response()->json([
                'status'  => true,
                'message' => 'Vendor Bank Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function registrationDetailStore(RegistrationDetailStoreRequest $request, Vendor $vendor)
    {
        try {
            $vendor->pan_account_no = $request->pan_account_no;
            $vendor->pf_no = $request->pf_no;
            $vendor->esic_no = $request->esic_no;
            $vendor->digital_signature = $request->digital_signature;
            $vendor->msme_registered = $request->MSME_registered;
            $vendor->gst_status = $request->gst_status;

            if ($request->gst_status == 'yes') {
                $vendor->gst_no = $request->gst_no;
            }

            if ($request->gst_status == 'no') {
                if ($request->hasFile('attachment')) {
                    $image = $request->file('attachment');
                    $imageName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $image->getClientOriginalName();
                    $image->move(public_path(Vendor::imagePath), $imageName);
                    $vendor->gst_attachment = $imageName;
                }
            }

            if ($request->MSME_registered == 'no') {
                $vendor->msme_no = '';
                $vendor->form_of_msme = '';
            } else {
                $vendor->msme_no = $request->msme_no;
                $vendor->form_of_msme = $request->form_of_msme;
            }

            $vendor->save();

            return response()->json([
                'status'  => true,
                'message' => 'Registration detail store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function vendorDocument(Vendor $vendor)
    {
        $vendorDocDetails = VendorDocDetail::where('vendor_type_id', $vendor->vendor_type_id)->get();
        $uploadedVendorDocs = VendorDocument::whereNotNull('document')->where('vendor_id', $vendor->id)->pluck('document', 'vendor_doc_id',)->toArray();
        $uploadedVendorDocsStatuses = VendorDocument::where('vendor_id', $vendor->id)->get();
        $uploadedVendorDocsStatusArr = [];
        foreach ($uploadedVendorDocsStatuses as $uploadedVendorDocsStatus) {
            $uploadedVendorDocsStatusArr[$uploadedVendorDocsStatus->vendor_doc_id] = $uploadedVendorDocsStatus->status;
        }
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.admin-vendor.vendor-document', compact('vendor', 'vendorDocDetails', 'uploadedVendorDocs', 'uploadedVendorDocsStatusArr', 'vendorItems'));
    }

    public function vendorDocumentStore(Vendor $vendor, VendorDocumentStoreRequest $request)
    {
        try {
            foreach ($request->vendor_doc_type as $key => $docType) {
                $vendorDocument = VendorDocument::where('vendor_id', $vendor->id)
                    ->where('vendor_doc_id', $docType)->first();

                if ($vendorDocument == "") {
                    $vendorDocument = new VendorDocument;
                    $vendorDocument->status = 'pending';
                }
                $vendorDocument->user_id = Auth::id();
                $vendorDocument->vendor_id = $vendor->id;
                $vendorDocument->vendor_type_id = $vendor->vendor_type_id;
                $vendorDocument->vendor_doc_id = $docType;

                if ($request->hasFile('document.' . $key)) {
                    $file = $request->file('document.' . $key);
                    $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('vendor_documents'), $fileName);
                    $vendorDocument->document = $fileName;
                }
                $vendorDocument->save();
            }
            return redirect()->route('vendors.vendor.document', $vendor)->with(['success' => 'Vendor Document store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendors.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function delete(Vendor $vendor)
    {
        try {
            $vendorDocuments = VendorDocument::where('vendor_id', $vendor->id)->get();
            foreach ($vendorDocuments as $vendorDocument) {
                $vendorDocumentPath = public_path("vendor_documents" . "/" . $vendorDocument->document);
                if (is_file($vendorDocumentPath) && file_exists($vendorDocumentPath)) {
                    unlink($vendorDocumentPath);
                }
                $vendorDocument->delete();
            }

            $branchDocuments = BranchDocument::where('vendor_id', $vendor->id)->get();
            foreach ($branchDocuments as $branchDocument) {
                $branchDocumentPath = public_path("branch_documents" . "/" . $branchDocument->document);
                if (is_file($branchDocumentPath) && file_exists($branchDocumentPath)) {
                    unlink($branchDocumentPath);
                }
                $branchDocument->delete();
            }

            Branch::where('vendor_id', $vendor->id)->delete();
            User::where('id', $vendor->user_id)->delete();
            // PreVendorDetail::where('id', $vendor->invite_vendor_id)->delete();

            if ($vendor->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Vendor deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Vendor not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusChange(Vendor $vendor, Request $request)
    {
        try {
            $vendorDocument = VendorDocument::where('vendor_id', $vendor->id)
                ->where('vendor_doc_id', $request->vendor_doc_id)
                ->first();

            if (empty($vendorDocument)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Document not found'
                ]);
            }

            $vendorDocument->status = $request->status;
            $vendorDocument->save();

            if (!empty($vendorDocument)) {
                $vendorDocType = VendorDocType::find($request->vendor_doc_id);
                $notification = new Notification;
                $notification->user_id = Auth::id();
                $notification->vendor_id = $vendor->user_id;
                $notification->title = $vendorDocType->name . ' vendor document ' . $request->status;
                $notification->from = 'vendor';
                $notification->module = 'vendor_document';
                $notification->admin_status = 'Approved';
                $notification->save();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Status change successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusEdit(Request $request)
    {
        $vendor = Vendor::find($request->id);
        try {
            return response()->json([
                'status'  => true,
                'data'    => $vendor,
                'message' => 'Status fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusUpdate(Request $request)
    {
        try {
            $vendor = Vendor::find($request->vendor_id);
            $vendor->status = $request->status;
            $vendor->save();

            if ($vendor->status == 'partially_active' || $vendor->status == 'active') {
                Mail::to($vendor->email)->send(new VendorApproveMail($vendor));
            }

            return response()->json([
                'status'  => true,
                'message' => 'Status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePassword(Vendor $vendor, UpdatePasswordRequest $request)
    {
        try {
            $plainPassword = $request->password;

            $user = User::find($vendor->user_id);
            $user->password = Hash::make($request->password);
            $user->save();

            if ($request->has('password_mail_send')) {
                $message = "Your password has been updated. Here below updated password details";
                Mail::to($user->email)->send(new VendorPasswordUpdateMail($message, $user, $plainPassword));
            }

            $branch = Branch::where('vendor_id', $vendor->id)->where('is_primary', '1')->first();

            $updatedValues['Password'] = 'Password Updated';
            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, [], $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Password updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
