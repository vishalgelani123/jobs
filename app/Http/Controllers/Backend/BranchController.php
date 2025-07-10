<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorBranchDataTable;
use App\Helpers\AuditLogHelper;
use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchDocument\BranchDocumentStoreRequest;
use App\Http\Requests\VendorBranch\VendorBankDetailStoreRequest;
use App\Http\Requests\VendorBranch\VendorBranchStoreRequest;
use App\Http\Requests\VendorBranch\VendorBranchUpdateRequest;
use App\Http\Requests\VendorBranch\VendorRegistrationDetailStoreRequest;
use App\Models\Branch;
use App\Models\BranchDocument;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PreVendorSubCategory;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocDetail;
use App\Models\VendorDocType;
use App\Models\VendorItem;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{

    public function index(VendorBranchDataTable $dataTable)
    {
        return $dataTable->render('backend.vendor-branch.index');
    }

    public function create()
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $vendorItemArray = VendorItem::where('vendor_id', $vendor->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.vendor-branch.create', compact('vendorTypes', 'states', 'preVendorSubCategories', 'vendor', 'vendorItemArray', 'vendorItems'));
    }

    public function store(VendorBranchStoreRequest $request)
    {
        try {
            $branch = new Branch;
            $branch->user_id = Auth::id();
            $branch->vendor_id = $request->vendor_id;
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
            $branch->save();

            return redirect()->route('vendor-branches.index')->with(['success' => 'Branch detail store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendor-branches.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function branchDetail(Branch $branch)
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.vendor-branch.branch-detail', compact('states', 'branch', 'vendorItems', 'vendor'));
    }

    public function branchDetailStore(Branch $branch, VendorBranchUpdateRequest $request)
    {
        try {
            $branch->user_id = Auth::id();
            $branch->vendor_id = $request->vendor_id;
            $branch->address = $request->address;
            $branch->state_id = $request->state;
            $branch->city_id = $request->city;
            $branch->pin_code = $request->pin_code;
            $branch->phone_number_1 = $request->mobile_number;
            $branch->phone_number_2 = $request->phone_number_2;
            $branch->fax_no = $request->fax_no;
            //$branch->email = $request->email;
            $branch->name_of_contact_person = $request->name_of_contact_person;
            $branch->contact_person_mobile_number = $request->contact_person_mobile_number;
            $branch->contact_person_email = $request->contact_person_email;

            $updatedValues = $branch->getDirty();
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $branch->getOriginal($field);
            }

            $branch->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Branch Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function bankDetail(Branch $branch)
    {
        return view('backend.vendor-branch.bank-detail', compact('branch'));
    }

    public function bankDetailStore(Branch $branch, VendorBankDetailStoreRequest $request)
    {
        try {
            $branch->type_of_account = $request->type_of_account;
            $branch->bank_account_no = $request->bank_account_no;
            $branch->bank_name = $request->bank_name;
            $branch->payment_in_favour = $request->payment_in_favour;
            $branch->bank_branch_name_and_address = $request->bank_branch_name_and_address;
            $branch->bank_branch_code = $request->bank_branch_code;
            $branch->bank_ifsc_code = $request->bank_ifsc_code;

            $updatedValues = $branch->getDirty();
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $branch->getOriginal($field);
            }

            $branch->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Branch Bank Detail Updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function registrationDetail(Branch $branch)
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();
        return view('backend.vendor-branch.registration-detail', compact('branch', 'vendor'));
    }

    public function registrationDetailStore(Branch $branch, VendorRegistrationDetailStoreRequest $request)
    {
        try {
            $branch->pan_account_no = $request->pan_account_no;
            $branch->pf_no = $request->pf_no;
            $branch->esic_no = $request->esic_no;
            $branch->digital_signature = $request->digital_signature;
            $branch->msme_registered = $request->MSME_registered;
            $branch->gst_status = $request->gst_status;

            if ($request->gst_status == 'yes') {
                $branch->gst_no = $request->gst_no;
            }

            if ($request->gst_status == 'no') {
                if ($request->hasFile('attachment')) {
                    $image = $request->file('attachment');
                    $imageName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $image->getClientOriginalName();
                    $image->move(public_path(Branch::imagePath), $imageName);
                    $branch->gst_attachment = $imageName;
                }
            }

            if ($request->MSME_registered == 'no') {
                $branch->msme_no = '';
                $branch->form_of_msme = '';
            } else {
                $branch->msme_no = $request->msme_no;
                $branch->form_of_msme = $request->form_of_msme;
            }

            $updatedValues = $branch->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $branch->getOriginal($field);
            }

            $branch->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, $oldValues, $updatedValues);
            }

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

    public function branchDocument(Branch $branch)
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', $user->id)->first();
        $vendorDocDetails = VendorDocDetail::where('vendor_type_id', $vendor->vendor_type_id)->get();
        $uploadedVendorDocs = BranchDocument::whereNotNull('document')->where('branch_id', $branch->id)->pluck('document', 'vendor_doc_id',)->toArray();
        $uploadedVendorDocsStatuses = BranchDocument::where('branch_id', $branch->id)->get();
        $uploadedVendorDocsStatusArr = [];
        foreach ($uploadedVendorDocsStatuses as $uploadedVendorDocsStatus) {
            $uploadedVendorDocsStatusArr[$uploadedVendorDocsStatus->vendor_doc_id] = $uploadedVendorDocsStatus->status;
        }
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.vendor-branch.branch-document', compact('branch', 'vendor', 'vendorDocDetails', 'uploadedVendorDocs', 'uploadedVendorDocsStatusArr', 'vendorItems'));
    }

    public function branchDocumentStore(Branch $branch, BranchDocumentStoreRequest $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();
            $vendor = Vendor::where('user_id', $user->id)->first();
            $updatedValues = [];
            foreach ($request->vendor_doc_type as $key => $docType) {
                $branchDocument = BranchDocument::where('branch_id', $branch->id)
                    ->where('vendor_doc_id', $docType)->first();

                if ($branchDocument == "") {
                    $branchDocument = new BranchDocument;
                    $branchDocument->status = 'pending';
                }
                $branchDocument->user_id = Auth::id();
                $branchDocument->vendor_id = $vendor->id;
                $branchDocument->branch_id = $branch->id;
                $branchDocument->vendor_type_id = $vendor->vendor_type_id;
                $branchDocument->vendor_doc_id = $docType;

                if ($request->hasFile('document.' . $key)) {
                    $file = $request->file('document.' . $key);
                    $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('branch_documents'), $fileName);
                    $branchDocument->document = $fileName;

                    $adminUsers = User::role('admin')->get();
                    foreach ($adminUsers as $adminUser) {
                        $vendorDocType = VendorDocType::find($docType);
                        $notification = new Notification;
                        $notification->user_id = Auth::id();
                        $notification->vendor_id = $adminUser->id;
                        $notification->branch_id = $branch->id;
                        $notification->title = $vendorDocType->name . ' branch document upload ';
                        $notification->from = 'vendor';
                        $notification->module = 'branch_document_upload';
                        $notification->save();
                    }

                    $vendorDocTypes = VendorDocType::find($docType);
                    $updatedValues[$vendorDocTypes->name] = "document uploaded";
                }
                $branchDocument->save();
            }

            if (count($updatedValues) > 0) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, [], $updatedValues);
            }
            return redirect()->route('vendor-branches.branch.document', $branch)->with(['success' => 'Branch Document store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendor-branches.branch.document', $branch)->withErrors(['error' => $e->getMessage()]);
        }
    }
}
