<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminVendor\BankDetailStoreRequest;
use App\Http\Requests\AdminVendor\RegistrationDetailStoreRequest;
use App\Http\Requests\AdminVendor\VendorDetailStoreRequest;
use App\Http\Requests\VendorDocument\VendorDocumentStoreRequest;
use App\Models\Country;
use App\Models\Notification;
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

class VendorController extends Controller
{

    public function pendingVendor()
    {
        return view('errors.pending-vendor');
    }

    public function vendorDetail()
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $vendorItemArray = VendorItem::where('vendor_id', $vendor->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();

        return view('backend.vendor.vendor-detail', compact('vendor', 'vendorTypes', 'states', 'preVendorSubCategories', 'vendorItemArray', 'vendorItems'));
    }

    public function vendorDetailStore(Vendor $vendor, VendorDetailStoreRequest $request)
    {
        try {
            $vendor->business_name = $request->business_name;
            $vendor->payment_in_favour = $request->payment_in_favour;
            $vendor->email = $request->email;
            $vendor->phone_number_1 = $request->phone_number_1;
            $vendor->phone_number_2 = $request->phone_number_2;
            $vendor->state_id = $request->state;
            $vendor->city_id = $request->city;
            $vendor->address = $request->address;
            $vendor->pin_code = $request->pin_code;
            $vendor->fax_no = $request->fax_no;
            $vendor->name_of_contact_person = $request->name_of_contact_person;
            $vendor->contact_person_mobile_number = $request->contact_person_mobile_number;
            $vendor->contact_person_email = $request->contact_person_email;
            $vendor->save();

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

    public function bankDetail()
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();

        return view('backend.vendor.bank-detail', compact('vendor'));
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
                'message' => 'Bank Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function registrationDetail()
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();

        return view('backend.vendor.registration-detail', compact('vendor'));
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
                    $imageName =GenerateStringNumberHelper::generateTimeRandomString() . '_' . $image->getClientOriginalName();
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
                'message' => 'Registration Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function vendorDocument()
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();

        $vendorDocDetails = VendorDocDetail::where('vendor_type_id', $vendor->vendor_type_id)->get();
        $uploadedVendorDocs = VendorDocument::whereNotNull('document')->where('vendor_id', $vendor->id)->pluck('document', 'vendor_doc_id',)->toArray();
        $uploadedVendorDocsStatuses = VendorDocument::where('vendor_id', $vendor->id)->get();
        $uploadedVendorDocsStatusArr = [];
        foreach ($uploadedVendorDocsStatuses as $uploadedVendorDocsStatus) {
            $uploadedVendorDocsStatusArr[$uploadedVendorDocsStatus->vendor_doc_id] = $uploadedVendorDocsStatus->status;
        }
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();

        return view('backend.vendor.vendor-document', compact('vendor', 'vendorDocDetails', 'uploadedVendorDocs', 'uploadedVendorDocsStatusArr', 'vendorItems'));
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

                    $adminUsers = User::role('admin')->get();
                    foreach ($adminUsers as $adminUser) {
                        $vendorDocType = VendorDocType::find($docType);
                        $notification = new Notification;
                        $notification->user_id = Auth::id();
                        $notification->vendor_id = $adminUser->id;
                        $notification->title = $vendorDocType->name . ' vendor document upload ';
                        $notification->from = 'vendor';
                        $notification->module = 'vendor_document_upload';
                        $notification->save();
                    }
                }
                $vendorDocument->save();
            }
            return redirect()->back()->with(['success' => 'Vendor Document store successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
