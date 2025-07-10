<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BranchAuditLogDataTable;
use App\Helpers\AuditLogHelper;
use App\Helpers\FinancialYearHelper;
use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BankDetailStoreRequest;
use App\Http\Requests\Branch\BranchStoreRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Http\Requests\Branch\RegistrationDetailStoreRequest;
use App\Http\Requests\BranchDocument\BranchDocumentStoreRequest;
use App\Models\Branch;
use App\Models\BranchDocument;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PreVendorSubCategory;
use App\Models\State;
use App\Models\Vendor;
use App\Models\VendorDocDetail;
use App\Models\VendorDocType;
use App\Models\VendorItem;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminBranchController extends Controller
{
    public function create(Vendor $vendor)
    {
        $vendorTypes = VendorType::all();
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $vendorItemArray = VendorItem::where('vendor_id', $vendor->id)->pluck('pre_vendor_sub_category_id',)->toArray();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.admin-vendor.branch-detail.create', compact('vendorTypes', 'states', 'preVendorSubCategories', 'vendor', 'vendorItemArray', 'vendorItems'));
    }

    public function store(Vendor $vendor, BranchStoreRequest $request)
    {
        try {
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
            $branch->save();

            AuditLogHelper::storeLog('created', 'branch', $branch->id, [], $branch);

            return redirect()->route('vendors.branch.detail', $vendor)->with(['success' => 'Branch detail store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendors.branch.detail', $vendor)->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function detail(Vendor $vendor, Branch $branch)
    {
        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.admin-vendor.branch-detail.branch-view.detail', compact('states', 'vendor', 'branch', 'vendorItems'));
    }

    public function branchDetailStore(Vendor $vendor, Branch $branch, BranchUpdateRequest $request)
    {
        try {
            //$branch->user_id = Auth::id();
            $branch->vendor_id = $vendor->id;
            $branch->address = $request->address;
            $branch->state_id = $request->state;
            $branch->city_id = $request->city;
            $branch->pin_code = $request->pin_code;
            $branch->phone_number_1 = $request->mobile_no;
            $branch->phone_number_2 = $request->phone_number_2;
            $branch->fax_no = $request->fax_no;
            $branch->email = $request->email;
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

    public function bankDetailStore(Vendor $vendor, Branch $branch, BankDetailStoreRequest $request)
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

    public function registrationDetailStore(Vendor $vendor, Branch $branch, RegistrationDetailStoreRequest $request)
    {
        try {
            if ($branch->pan_account_no != $request->pan_account_no) {
                $branch->pan_no_verify = '0';
            }

            $branch->pan_account_no = $request->pan_account_no;
            $branch->pf_no = $request->pf_no;
            $branch->esic_no = $request->esic_no;
            $branch->digital_signature = $request->digital_signature;
            $branch->msme_registered = $request->MSME_registered;
            $branch->gst_status = $request->gst_status;

            if ($request->gst_status == 'yes') {
                if ($branch->gst_no != $request->gst_no) {
                    $branch->gst_no_verify = '0';
                }
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

    public function branchDocument(Vendor $vendor, Branch $branch)
    {
        $vendorDocDetails = VendorDocDetail::where('vendor_type_id', $vendor->vendor_type_id)->get();
        $uploadedVendorDocs = BranchDocument::whereNotNull('document')->where('branch_id', $branch->id)->pluck('document', 'vendor_doc_id',)->toArray();
        $uploadedVendorDocsStatuses = BranchDocument::where('branch_id', $branch->id)->get();
        $uploadedVendorDocsStatusArr = [];
        foreach ($uploadedVendorDocsStatuses as $uploadedVendorDocsStatus) {
            $uploadedVendorDocsStatusArr[$uploadedVendorDocsStatus->vendor_doc_id] = $uploadedVendorDocsStatus->status;
        }
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return view('backend.admin-vendor.branch-detail.branch-view.branch-document', compact('branch', 'vendor', 'vendorDocDetails', 'uploadedVendorDocs', 'uploadedVendorDocsStatusArr', 'vendorItems'));
    }

    public function branchDocumentStore(Vendor $vendor, Branch $branch, BranchDocumentStoreRequest $request)
    {
        try {
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

                    $vendorDocTypes = VendorDocType::find($docType);
                    $updatedValues[$vendorDocTypes->name] = "document uploaded";
                }
                $branchDocument->save();
            }

            if (count($updatedValues) > 0) {
                AuditLogHelper::storeLog('updated', 'branch', $branch->id, [], $updatedValues);
            }

            return redirect()->route('branches.branch.document', [$vendor, $branch])->with(['success' => 'Branch Document store successfully']);
        } catch (\Exception $e) {
            return redirect()->route('branches.branch.document', [$vendor, $branch])->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function statusChange(Vendor $vendor, Branch $branch, Request $request)
    {
        try {
            $branchDocument = BranchDocument::where('branch_id', $branch->id)
                ->where('vendor_doc_id', $request->vendor_doc_id)
                ->first();

            if (empty($branchDocument)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Document not found'
                ]);
            }

            $vendorDocType = VendorDocType::find($request->vendor_doc_id);

            $oldValue = [
                'branch_document' => (isset($vendorDocType->name) ? $vendorDocType->name : '') . ' document status ' . $branchDocument->status
            ];

            $branchDocument->status = $request->status;
            $branchDocument->save();

            $newValue = [
                'branch_document' => (isset($vendorDocType->name) ? $vendorDocType->name : '') . ' document status ' . $request->status
            ];

            AuditLogHelper::storeLog('updated', 'branch', $branch->id, $oldValue, $newValue);

            if (!empty($branchDocument)) {
                $vendorDocType = VendorDocType::find($request->vendor_doc_id);
                $notification = new Notification;
                $notification->user_id = Auth::id();
                $notification->vendor_id = $vendor->user_id;
                $notification->title = $vendorDocType->name . ' branch document ' . $request->status;
                $notification->from = 'vendor';
                $notification->module = 'branch_document';
                $notification->admin_status = 'Approved';
                $notification->branch_id = $branch->id;
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

    public function delete(Vendor $vendor, Branch $branch)
    {
        try {
            $branchDocuments = BranchDocument::where('branch_id', $branch->id)->get();
            foreach ($branchDocuments as $branchDocument) {
                $branchDocumentPath = public_path("branch_documents" . "/" . $branchDocument->document);
                if (is_file($branchDocumentPath) && file_exists($branchDocumentPath)) {
                    unlink($branchDocumentPath);
                }
                $branchDocument->delete();
            }

            if ($branch->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Branch deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Branch not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function isPrimary(Vendor $vendor, Request $request)
    {
        try {
            Branch::where('vendor_id', $vendor->id)->update(['is_primary' => '0']);

            $branch = Branch::find($request->id);
            $branch->is_primary = '1';
            $branch->save();

            return response()->json([
                'status'  => true,
                'message' => 'Primary changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusEdit(Vendor $vendor, Request $request)
    {
        $branch = Branch::find($request->id);
        try {
            return response()->json([
                'status'  => true,
                'data'    => $branch,
                'message' => 'Status fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusUpdate(Vendor $vendor, Request $request)
    {
        try {
            $branch = Branch::find($request->branch_id);
            $branch->status = $request->status;

            $updatedValues = $branch->getDirty();
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $branch->getOriginal($field);
            }

            $branch->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'branch', $request->branch_id, $oldValues, $updatedValues);
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

    public function panVerify(Vendor $vendor, Branch $branch)
    {
        try {
            $response = Http::withOptions(['verify' => false]) // Disable SSL verification
            ->withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . config('services.pan.pan_api_auth_token'),
            ])->post(config('services.pan.pan_api_base_url'), [
                'pan' => $branch->pan_account_no,
            ]);

            if ($response->successful()) {
                if (isset($response->json()['valid']) && $response->json()['valid']) {
                    $panAadhaarLinked = isset($response->json()['aadhaarLinked']) && $response->json()['aadhaarLinked'] ? 'Yes' : 'No';

                    $branch->pan_category = $response->json()['category'] ?? "";
                    $branch->pan_name = $response->json()['name'] ?? "";
                    $branch->pan_aadhaar_linked = $panAadhaarLinked;
                    $branch->save();

                    return response()->json([
                        'status' => true,
                        'data'   => $branch,
                    ]);
                }
            }

            return response()->json([
                'status'  => false,
                'message' => 'Invalid pan number',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function panVerifyStore(Vendor $vendor, Branch $branch)
    {
        try {
            $branch->pan_no_verify = '1';
            $branch->save();

            $updatedValues = ['pan_no_verify' => 'Yes'];
            AuditLogHelper::storeLog('updated', 'branch', $branch->id, [], $updatedValues);

            return response()->json([
                'status'  => true,
                'message' => 'Pan card verify successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function gstNoVerify(Vendor $vendor, Branch $branch)
    {
        try {
            $currentFinancialYear = FinancialYearHelper::currentFinancialYear();
            $previousFinancialYear = FinancialYearHelper::previousFinancialYear();

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Basic ' . config('services.gst.gst_in_api_auth_token'),
                ])->post(config('services.gst.gst_in_api_base_url'), [
                    'gstin'        => $branch->gst_no,
                    'fetchFilings' => true,
                    'fy'           => $currentFinancialYear
                ]);

            if ($response->successful()) {
                if (isset($response->json()['valid']) && $response->json()['valid']) {

                    $gstActive = isset($response->json()['active']) && $response->json()['active'] ? 'Yes' : 'No';
                    $einVoiceEnabled = isset($response->json()['einvoiceEnabled']) && $response->json()['einvoiceEnabled'] ? 'Yes' : 'No';
                    $addresses = $response->json()['addresses'] ?? [];
                    $filings = $response->json()['filings'] ?? [];

                    $branch->gst_active = $gstActive;
                    $branch->gst_legal_name = $response->json()['legalName'] ?? "";
                    $branch->gst_trade_name = $response->json()['tradeName'] ?? "";
                    $branch->gst_pan = $response->json()['pan'] ?? "";
                    $branch->gst_constitution = $response->json()['constitution'] ?? "";
                    $branch->gst_nature = implode(", ", $response->json()['nature']);
                    $branch->gst_type = $response->json()['type'] ?? "";
                    $branch->gst_registered = $response->json()['registered'] ?? "";
                    $branch->gst_updated = $response->json()['updated'] ?? "";
                    $branch->gst_expiry = $response->json()['expiry'] ?? "";
                    $branch->gst_state = $response->json()['state'] ?? "";
                    $branch->gst_state_code = $response->json()['stateCode'] ?? "";
                    $branch->gst_center = $response->json()['center'] ?? "";
                    $branch->gst_center_code = $response->json()['centerCode'] ?? "";
                    $branch->gst_current_financial_year_filings = json_encode($filings);
                    $branch->gst_current_financial_year = $currentFinancialYear;
                    $branch->gst_einvoice_enabled = $einVoiceEnabled;
                    $branch->gst_addresses = json_encode($addresses);
                    $branch->save();

                    $response = Http::withOptions(['verify' => false])
                        ->withHeaders([
                            'Content-Type'  => 'application/json',
                            'Authorization' => 'Basic ' . config('services.gst.gst_in_api_auth_token'),
                        ])->post(config('services.gst.gst_in_api_base_url'), [
                            'gstin'        => $branch->gst_no,
                            'fetchFilings' => true,
                            'fy'           => $previousFinancialYear
                        ]);

                    if ($response->successful()) {
                        if (isset($response->json()['valid']) && $response->json()['valid']) {
                            $filings = $response->json()['filings'] ?? [];

                            $branch->gst_previous_financial_year_filings = json_encode($filings);
                            $branch->gst_previous_financial_year = $previousFinancialYear;
                            $branch->save();
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'data'   => $branch,
                    ]);
                }
            }

            return response()->json([
                'status'  => false,
                'message' => 'GST no invalid',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function gstVerifyStore(Vendor $vendor, Branch $branch)
    {
        try {
            $branch->gst_no_verify = '1';
            $branch->save();

            $updatedValues = ['gst_no_verify' => 'Yes'];
            AuditLogHelper::storeLog('updated', 'branch', $branch->id, [], $updatedValues);

            return response()->json([
                'status'  => true,
                'message' => 'GST no verify successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function gstDetail(Vendor $vendor, Branch $branch, Request $request)
    {
        try {
            return response()->json([
                'status'  => true,
                'data'    => $branch,
                'message' => 'GST detail fetch successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function auditLog(Vendor $vendor, Branch $branch, BranchAuditLogDataTable $dataTable)
    {
        $vendorItems = VendorItem::where('vendor_id', $vendor->id)->get();
        return $dataTable->render('backend.admin-vendor.branch-detail.branch-audit-log', compact('vendor', 'branch', 'vendorItems'));
    }
}
