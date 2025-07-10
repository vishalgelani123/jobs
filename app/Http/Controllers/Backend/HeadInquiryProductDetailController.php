<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\DataTables\InquiryProductDetailDataTable;
use App\Http\Requests\InquiryDocument\ProductImportRequest;
use App\Imports\ProductImport;
use App\Models\Branch;
use App\Models\City;
use App\Models\Document;
use App\Models\GeneralCharge;
use App\Models\GeneralChargesVendorVersion;
use App\Models\GeneralTermConditionCategory;
use App\Models\Image;
use App\Models\InquiryAdmin;
use App\Models\InquiryApproval;
use App\Models\InquiryAward;
use App\Models\InquiryContactDetail;
use App\Models\InquiryGeneralCharge;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorDetail;
use App\Models\InquiryVendorRateDetail;
use App\Models\Notification;
use App\Models\ResInquiryMaster;
use App\Models\TermConditionCategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class HeadInquiryProductDetailController extends Controller
{
    public function index(InquiryProductDetailDataTable $dataTable, ResInquiryMaster $inquiry)
    {
        $inquiry->load('user', 'approval');
        $productInquires = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $inquiry->load('vendorType');
        $vendorDetails = InquiryVendorDetail::with('vendor', 'city')->where('inquiry_id', $inquiry->id)->get();
        $inquiryVendorDetailVendor = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->pluck('vendor_id')->toArray();
        $vendors = Vendor::with('user')->where('vendor_type_id', $inquiry->vendor_type)
            ->whereNotIn('user_id', $inquiryVendorDetailVendor)->get();
        $products = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $vendorArr = [];
        $images = Image::where('inquiry_id', $inquiry->id)->get();

        $generalCharges = GeneralCharge::where('status', 'active')->get();
        $inquiryGeneralCharges = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->groupBy('general_charge_id')->get();
        $generalChargesData = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)
            ->select('general_charge_id', 'status')
            ->get()->keyBy('general_charge_id')->toArray();

        $generalTermConditionsCategories = [];
        $generalTermConditionDecodedArray = json_decode($inquiry->general_term_condition_categories, true);
        if (is_array($generalTermConditionDecodedArray)) {
            $generalTermConditionsCategories = GeneralTermConditionCategory::whereIn('id', $generalTermConditionDecodedArray)->get();
        }

        $termConditionsCategories = [];
        $termConditionDecodedArray = json_decode($inquiry->term_condition_categories_id, true);
        if (is_array($termConditionDecodedArray)) {
            $termConditionsCategories = TermConditionCategory::whereIn('id', $termConditionDecodedArray)->get();
        }

        $termConditionsDocuments = [];
        $termConditionDocumentDecodedArray = json_decode($inquiry->term_condition_documents_id, true);
        if (is_array($termConditionDocumentDecodedArray)) {
            $termConditionsDocuments = Document::whereIn('id', $termConditionDocumentDecodedArray)->get();
        }

        foreach ($vendorDetails as $vt) {
            $vendorArr[] = $vt->vendor_id;
        }

        $showApprovalStatusButton = "0";
        if ($inquiry->approval_status == "pending") {
            $checkShowApprovalStatusButton = InquiryApproval::where('inquiry_id', $inquiry->id)->where('status', 'pending')->where('approval_user_id', Auth::id())->first();
            if (!empty($checkShowApprovalStatusButton) && $checkShowApprovalStatusButton != null) {
                $previousPriorityNumber = $checkShowApprovalStatusButton->priority_number - 1;
                if ($previousPriorityNumber == "0") {
                    $showApprovalStatusButton = "1";
                }
                $previousStatusCheck = InquiryApproval::where('inquiry_id', $inquiry->id)->where('status', 'approved')->where('priority_number', $previousPriorityNumber)->first();
                if (!empty($previousStatusCheck) && $previousStatusCheck != null) {
                    $showApprovalStatusButton = "1";
                }
            }
        }
        $inquiryApprovals = InquiryApproval::where('inquiry_id', $inquiry->id)->get();
        $inquiryAward = InquiryAward::where('inquiry_id', $inquiry->id)->first();
        $inquiryContactDetails = InquiryContactDetail::where('inquiry_id', $inquiry->id)->get();
        return view('backend.inquiry-product-detail.index', compact('inquiry', 'vendorDetails', 'vendors', 'vendorArr', 'productInquires', 'products', 'images', 'generalCharges', 'inquiryGeneralCharges', 'generalChargesData', 'generalTermConditionsCategories', 'termConditionsCategories', 'termConditionsDocuments', 'inquiryApprovals', 'showApprovalStatusButton', 'inquiryAward', 'inquiryContactDetails'));
    }

    public function vendorInquiry(ResInquiryMaster $inquiry)
    {
        $inquires = InquiryVendorRateDetail::select('vendor_id')->where('inquiry_id', $inquiry->id)->groupBy('vendor_id')->get();
        $vendors = Vendor::whereIn('id', $inquires)->get();

        return view('backend.inquiry-product-detail.vendor-inquiry', compact('inquiry', 'vendors'));
    }

    public function vendorProductDetails(ResInquiryMaster $inquiry, Vendor $vendor)
    {
        $vendorProductDetail = InquiryVendorRateDetail::with('product')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();

        $data = VendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();

        foreach ($vendorProductDetail as $detail) {
            $vendorVersion = VendorVersion::select('version', 'rate', 'remarks')->groupBy('version', 'rate', 'remarks')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->where('ipd_id', $detail->ipd_id)->get();
            foreach ($vendorVersion as $version) {
                $detail->{'version_' . $version->version . '_' . 'price'} = $version->rate;
                $detail->{'version_' . $version->version . '_' . 'remarks'} = $version->remarks;
            }
        }
        return view('backend.inquiry-product-detail.product', compact('vendorProductDetail', 'vendor', 'inquiry', 'data'));
    }

    public function store(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'item_description' => 'nullable|max:600',
            'additional_info'  => 'nullable|max:600',
            'qty'              => 'required',
        ]);

        try {
            if ($request->price == null) {
                $price = 0;
            } else {
                $price = $request->price;
            }
            $inquiryProductDetail = new InquiryProductDetail;
            $inquiryProductDetail->inquiry_id = $inquiry->id;
            $inquiryProductDetail->item_description = $request->item_description;
            $inquiryProductDetail->additional_info = $request->additional_info;
            $inquiryProductDetail->qty = $request->qty;
            $inquiryProductDetail->price = $price;
            $inquiryProductDetail->unit = $request->unit;
            $inquiryProductDetail->save();

            $inquiryVendorDetails = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->get();
            if ($inquiryVendorDetails->isNotEmpty()) {
                foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                    $productId = json_decode($inquiryVendorDetail->product_id, true);
                    if (!is_array($productId)) {
                        $productId = [];
                    }
                    $productId[] = $inquiryProductDetail->id;
                    $inquiryVendorDetail->product_id = json_encode($productId);
                    $inquiryVendorDetail->save();
                }
            }

            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $inquiryProductDetail);

            if (Auth::user()->hasRole('drafter')) {
                $inquiryAdmin = InquiryAdmin::where('inquiry_id', $inquiry->id)->first();

                if ($inquiryAdmin->admin_id == "all") {
                    $admins = User::role('admin')->get();
                } else {
                    $admins = User::where('id', $inquiryAdmin->admin_id)->get();
                }

                foreach ($admins as $admin) {
                    $notification = Notification::where('user_id', Auth::id())->where('vendor_id', $admin->id)->where('inquiry_id', $inquiry->id)->where('from', 'drafter')->first();
                    if ($notification == null) {
                        $notification = new Notification();
                    }

                    $notification->user_id = Auth::id();
                    $notification->vendor_id = $admin->id;
                    $notification->inquiry_id = $inquiry->id;
                    $notification->admin_status = 'pending';
                    $notification->from = 'drafter';
                    $notification->module = 'new_inquiry';
                    $notification->title = "You have get inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inquiry->name . ' ' . "Please review the details and respond accordingly.";
                    $notification->status = "Open";
                    $notification->save();
                }

            }
            return response()->json([
                'status'  => true,
                'message' => 'Inquiry store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $inquiry = InquiryProductDetail::find($request->id);

            return response()->json([
                'status'  => true,
                'data'    => $inquiry,
                'message' => 'inquiry fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, InquiryProductDetail $inquiryProductDetail, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'item_description' => 'nullable|max:600',
            'additional_info'  => 'nullable|max:600',
            'qty'              => 'required',
        ]);
        try {
            $inquiryProductDetail = InquiryProductDetail::find($request->product_id);

            $inquiryProductDetail->item_description = $request->item_description;
            $inquiryProductDetail->additional_info = $request->additional_info;
            $inquiryProductDetail->qty = $request->qty;
            $inquiryProductDetail->price = $request->price;
            $inquiryProductDetail->unit = $request->unit;

            $updatedValues = $inquiryProductDetail->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiryProductDetail->getOriginal($field);
            }
            $inquiryProductDetail->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry Update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(ResInquiryMaster $inquiry, InquiryProductDetail $inquiryProductDetail)
    {
        try {
            if ($inquiryProductDetail->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'inquiry deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "inquiry not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function import(ProductImportRequest $request, ResInquiryMaster $inquiry)
    {
        try {
            Excel::import(new ProductImport($inquiry->id), $request->file('file'));
            if (Auth::user()->hasRole('drafter')) {
                $inquiryAdmin = InquiryAdmin::where('inquiry_id', $inquiry->id)->first();

                if ($inquiryAdmin->admin_id == "all") {
                    $admins = User::role('admin')->get();
                } else {
                    $admins = User::find($inquiryAdmin->admin_id);
                }

                foreach ($admins as $admin) {
                    $notification = Notification::where('user_id', Auth::id())->where('vendor_id', $admin->id)->where('inquiry_id', $inquiry->id)->where('from', 'drafter')->first();
                    if ($notification == null) {
                        $notification = new Notification();
                    }

                    $notification->user_id = Auth::id();
                    $notification->vendor_id = $admin->id;
                    $notification->inquiry_id = $inquiry->id;
                    $notification->admin_status = 'pending';
                    $notification->from = 'drafter';
                    $notification->module = 'new_inquiry';
                    $notification->title = "You have get inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inquiry->name . ' ' . "Please review the details and respond accordingly.";
                    $notification->status = "Open";
                    $notification->save();
                }
            }
            return response()->json([
                'status'  => true,
                'message' => 'Product import successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateStore(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'vendor_id' => 'required',
            'branch_id' => 'required',
        ]);

        try {
            $user = User::find($request->vendor_id);
            $product = InquiryProductDetail::select('id')->where('inquiry_id', $inquiry->id)->get()->toArray();
            $inquiryVendorDetail = new InquiryVendorDetail();
            $inquiryVendorDetail->inquiry_id = $inquiry->id;
            $inquiryVendorDetail->vendor_id = $user->id;
            $inquiryVendorDetail->city_id = $request->branch_id;
            if (count($product) > 0) {
                $value = [];
                foreach ($product as $pd) {
                    $value[] = $pd['id'];
                }
                $inquiryVendorDetail->product_id = json_encode($value);
            }
            $inquiryVendorDetail->save();

            $vendor = Vendor::where('user_id', $user->id)->first();
            $city = City::where('id', $request->branch_id)->first();

            $allocation = [
                'inquiry_allocation' => 'inquiry allocation to ' . (isset($vendor->business_name) ? $vendor->business_name : '') . '(' . (isset($city->name) ? $city->name : '') . ')'
            ];

            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $allocation);

            $inq = ResInquiryMaster::find($inquiry->id);

            $inquiryVendorDetails = InquiryGeneralCharge::
            groupBy('general_charge_id')->where('inquiry_id', $inq->id)->get();

            foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                $inquiryGeneralCharge = new InquiryGeneralCharge;
                $inquiryGeneralCharge->user_id = Auth::id();
                $inquiryGeneralCharge->inquiry_id = $inquiry->id;
                $inquiryGeneralCharge->vendor_id = $user->id;
                $inquiryGeneralCharge->general_charge_id = $inquiryVendorDetail->general_charge_id;
                $inquiryGeneralCharge->status = $inquiryVendorDetail->status;
                $inquiryGeneralCharge->save();
            }

            InquiryGeneralCharge::where('inquiry_id', $inq->id)->whereNull('vendor_id')->delete();

            $notification = new Notification();
            $notification->user_id = Auth::id();
            $notification->vendor_id = $user->id;
            $notification->inquiry_id = $inquiry->id;
            $notification->admin_status = 'Pending';
            $notification->from = 'admin';
            $notification->module = 'Inquiry';
            $notification->title = "You have get inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inq->name . ' ' . "Please review the details and respond accordingly.";
            $notification->status = "Open";
            $notification->save();

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateEdit(Request $request)
    {
        try {
            $inquiry = InquiryVendorDetail::find($request->id);
            $branches = Branch::select('city_id')->where('vendor_id', $request->id)->get();
            $cities = City::whereIn('id', $branches)->get();
            $products = json_decode($inquiry->product_id);

            return response()->json([
                'status'   => true,
                'data'     => $inquiry,
                'cities'   => $cities,
                'products' => $products,
                'message'  => 'inquiry fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateUpdate(Request $request, ResInquiryMaster $inquiry)
    {
        $request->validate([
            'vendor_id' => 'required',
            'branch_id' => 'required',
        ]);

        try {
            $inquiryVendorDetail = InquiryVendorDetail::find($request->allocate_id);
            $inquiryVendorDetail->vendor_id = $request->vendor_id;
            $inquiryVendorDetail->city_id = $request->branch_id;

            $updatedValues = $inquiryVendorDetail->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiryVendorDetail->getOriginal($field);
            }
            $vendorOldValue = Vendor::where('user_id', $oldValues['vendor_id'])->first();

            if (isset($oldValues['city_id'])) {
                $cityOldValue = City::where('id', $oldValues['city_id'])->first();
            }

            $inquiryVendorDetail->save();

            $vendor = Vendor::where('user_id', $request->vendor_id)->first();
            $city = City::where('id', $request->branch_id)->first();

            $allocationOldValue = [
                'inquiry_allocation' => 'inquiry allocation to ' . (isset($vendorOldValue->business_name) ? $vendorOldValue->business_name : '') . (isset($cityOldValue->name) ?? '(' . $cityOldValue->name . ')')
            ];

            $allocation = [
                'inquiry_allocation' => 'inquiry allocation to ' . (isset($vendor->business_name) ? $vendor->business_name : '') . '(' . (isset($city->name) ? $city->name : '') . ')'
            ];

            AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $allocationOldValue, $allocation);

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function allocateDelete(Request $request, ResInquiryMaster $inquiry)
    {
        try {
            $vendor = Vendor::with('user')->where('user_id', $request->id)->first();
            $oldValues = [];
            $updatedValues = ['inquiry_allocation_deleted' => (isset($vendor->business_name) ? $vendor->business_name : '') . "'s inquiry allocation deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $inquiry->id, $oldValues, $updatedValues);

            InquiryGeneralCharge::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();
            GeneralChargesVendorVersion::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();
            InquiryVendorDetail::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();
            InquiryVendorRateDetail::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();
            VendorVersion::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();
            Notification::where('vendor_id', $request->id)->where('inquiry_id', $inquiry->id)->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteProduct(ResInquiryMaster $inquiry, Request $request)
    {
        try {
            $inquiryVendorDetails = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->get();
            if ($inquiryVendorDetails->isNotEmpty()) {
                foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                    $productId = json_decode($inquiryVendorDetail->product_id, true);
                    if (!is_array($productId)) {
                        $productId = [];
                    }
                    if (($key = array_search($request->id, $productId)) !== false) {
                        unset($productId[$key]);
                    }
                    $inquiryVendorDetail->product_id = json_encode(array_values($productId));
                    $inquiryVendorDetail->save();
                }
            }

            $inquiryVendorDetails = InquiryProductDetail::find($request->id);

            $oldValues = [];
            $updatedValues = ['inquiry_product_deleted' => (isset($inquiryVendorDetails->item_description) ? $inquiryVendorDetails->item_description : '') . " inquiry product deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $inquiryVendorDetails->inquiry_id, $oldValues, $updatedValues);

            $inquiryVendorDetails->delete();

            $inquiryVendorRateDetails = InquiryVendorRateDetail::where('ipd_id', $request->id)->get();
            foreach ($inquiryVendorRateDetails as $inquiryVendorRateDetail) {
                $inquiryVendorRateDetail->delete();
            }

            $vendorVersions = VendorVersion::where('ipd_id', $request->id)->get();
            foreach ($vendorVersions as $vendorVersion) {
                $vendorVersion->delete();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCity(Request $request)
    {
        try {
            $vendor = Vendor::Where('user_id', $request->vendorId)->first();
            if (!$vendor) {
                return response()->json([
                    'status'  => false,
                    'message' => 'City data not found'
                ]);
            }

            $branches = Branch::select('city_id')->where('status', 'active')->orWhere('status', 'partially_active')->where('vendor_id', $vendor->id)->get();
            $cities = City::whereIn('id', $branches)->get();
            return response()->json([
                'status'  => true,
                'data'    => $cities,
                'message' => 'Get City Data Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $vendor = InquiryVendorDetail::where('inquiry_id', $request->inquiry_id)->where('vendor_id', $request->vendor_id)->first();

            if ($vendor != null) {
                $vendor->status = $request->status;
                $vendor->save();

                $vendorName = Vendor::where('user_id', $request->vendor_id)->first();
                $allocation = [
                    'inquiry_allocation' => 'inquiry allocation status ' . $request->status . ' ' . (isset($vendorName->business_name) ? $vendorName->business_name : '')
                ];

                AuditLogHelper::storeLog('updated', 'inquiry', $request->inquiry_id, [], $allocation);
            }
            return response()->json([
                'status'  => true,
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function changeInquiryStatus(Request $request)
    {
        try {
            $inquiry = ResInquiryMaster::find($request->inquiry_id);
            if ($inquiry != null) {
                $inquiry->status = $request->status;
                $inquiry->save();
            }
            $vendor = InquiryVendorDetail::where('inquiry_id', $request->inquiry_id)->get();

            foreach ($vendor as $data) {
                $data->status = $request->status;
                $data->save();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function inquiryWiseDetail(ResInquiryMaster $inquiry, Vendor $vendor)
    {
        $vendorVersion = VendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();
        $versionArr = [];
        foreach ($vendorVersion as $version) {
            $versionData = VendorVersion::with('product')->where('version', $version->version)->where('inquiry_id', $inquiry->id)->where('vendor_id', $vendor->id)->get();
            $versionArr[$version->version][] = $versionData;
        }
        return view('backend.inquiry-product-detail.version', compact('versionArr'));
    }

    public function updateProduct(Request $request)
    {
        try {
            $inquiryVendorDetail = InquiryVendorDetail::with('vendor')->where('vendor_id', $request->vendor_id)->where('inquiry_id', $request->inquiry_id)->first();
            if ($inquiryVendorDetail) {

                $oldSelectedProductIds = json_decode($inquiryVendorDetail->product_id);

                $vendorName = isset($inquiryVendorDetail->vendor->business_name) ? $inquiryVendorDetail->vendor->business_name : '';
                $updatedValues = [];
                $oldValues = [];

                if ($oldSelectedProductIds == null) {
                    if (is_array($request->product_id) && count($request->product_id) > 0) {
                        $requestProducts = InquiryProductDetail::whereIn('id', $request->product_id)->get();
                        foreach ($requestProducts as $requestProduct) {
                            $updatedValues[$requestProduct->item_description] = "product allocated to " . $vendorName;
                        }
                    }
                }

                if (is_array($oldSelectedProductIds) && count($oldSelectedProductIds) > 0) {
                    if (is_array($request->product_id) && count($request->product_id) > 0) {
                        $newProductIds = array_diff($request->product_id, $oldSelectedProductIds);
                        if (count($newProductIds) > 0) {
                            $requestProducts = InquiryProductDetail::whereIn('id', $newProductIds)->get();
                            foreach ($requestProducts as $requestProduct) {
                                $updatedValues[$requestProduct->item_description] = "product allocated to " . $vendorName;
                            }
                        }
                    }
                }

                if (is_array($oldSelectedProductIds) && count($oldSelectedProductIds) > 0 && $request->product_id == "") {
                    $requestRemovedProducts = InquiryProductDetail::whereIn('id', $oldSelectedProductIds)->get();
                    foreach ($requestRemovedProducts as $requestRemovedProduct) {
                        $updatedValues[$requestRemovedProduct->item_description] = "product removed from allocated to " . $vendorName;
                    }
                }

                $inquiryVendorDetail->product_id = json_encode($request->product_id);
                $inquiryVendorDetail->save();

                if (count($updatedValues) > 0) {
                    AuditLogHelper::storeLog('updated', 'inquiry', $request->inquiry_id, $oldValues, $updatedValues);
                }

                /*if (Auth::user()->hasRole('admin')) {
                    $status = 'Approved';
                } else {
                    $status = 'Pending';
                }
                $inquiry = ResInquiryMaster::find($request->inquiry_id);

                if ($inquiry) {
                    $inquiry->admin_status = $status;
                    $inquiry->save();
                }*/

                return response()->json([
                    'status'  => true,
                    'message' => 'Product Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Record not Found"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProduct(Request $request)
    {
        $products = InquiryVendorDetail::where('vendor_id', $request->vendor_id)->where('inquiry_id', $request->inquiry_id)->first();
        $totalProducts = [];
        if ($products) {
            if ($products->product_id != null) {
                $totalProducts = json_decode($products->product_id, true);
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => $totalProducts,
            'message' => 'Product Updated Successfully'
        ]);
    }

    public function productList(ResInquiryMaster $inquiry, Vendor $vendor)
    {
        $products = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();
        $vendorProducts = InquiryVendorDetail::where('vendor_id', $vendor->id)->where('inquiry_id', $inquiry->id)->first();
        $totalProducts = [];
        if ($vendorProducts != null) {
            $totalProducts = json_decode($vendorProducts->product_id, true);
        }
        return view('backend.inquiry-product-detail.product-list', compact('totalProducts', 'products', 'vendor', 'inquiry'));
    }
}
