<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ApproverInquiryDataTable;
use App\Exports\InquiryMasterReportExport;
use App\Exports\VendorProductExport;
use App\DataTables\InquiryAuditLogDataTable;
use App\Helpers\AuditLogHelper;
use App\Helpers\ColorHelper;
use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\DataTables\InquiryMasterDataTable;
use App\Helpers\MailSettingHelper;
use App\Http\Requests\Inquiry\InquiryStoreRequest;
use App\Http\Requests\Inquiry\InquiryUpdateRequest;
use App\Http\Requests\InquiryDocument\SupportedDocumentStoreRequest;
use App\Mail\AdminStatusApproveInquiryMail;
use App\Mail\FinalizeMail;
use App\Models\Document;
use App\Models\FinalizeVersion;
use App\Models\GeneralChargesVendorVersion;
use App\Models\GeneralTermConditionCategory;
use App\Models\Image;
use App\Models\InquiryAdmin;
use App\Models\InquiryApproval;
use App\Models\InquiryGeneralCharge;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorDetail;
use App\Models\InquiryVendorRateDetail;
use App\Models\Notification;
use App\Models\ResInquiryMaster;
use App\Models\TechnicalDocument;
use App\Models\TermConditionCategory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Models\VendorVersion;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Exports\ProductComparisonExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HeadInquiryController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function index(InquiryMasterDataTable $dataTable)
    {
        $generalTermConditions = GeneralTermConditionCategory::all();
        $vendorType = VendorType::all();
        $users = User::role('admin')->get();
        $documents = Document::all();

        $resInquiryMaster = ResInquiryMaster::query();
        $adminData = InquiryAdmin::select(['inquiry_id'])->pluck('inquiry_id');
        if (Auth::user()->hasRole('drafter')) {
            $resInquiryMaster->where('inquiry_created_by_id', Auth::id());
        }
        if (Auth::user()->hasRole('admin') && !Auth::user()->hasRole('approver')) {
            $userIds = User::where('user_id', Auth::id())->pluck('id')->toArray();
            $userIds[] = Auth::id(); // Append the authenticated user ID
            $resInquiryMaster->whereIn('inquiry_created_by_id', $userIds);
        }

        if (Auth::user()->hasRole('approver') && !Auth::user()->hasRole('admin')) {
            $inquiryApprovals = InquiryApproval::where('approval_user_id', Auth::id())->pluck('inquiry_id');
            $resInquiryMaster->whereIn('id', $inquiryApprovals);
            $resInquiryMaster->where('status', 'close');
        }
        $projectNames = $resInquiryMaster->whereIn('id', $adminData)->get();

        return $dataTable->render('backend.inquiry.index', compact('vendorType', 'generalTermConditions', 'users', 'documents', 'projectNames'));
    }

    public function approverInquiry(ApproverInquiryDataTable $dataTable)
    {
        return $dataTable->render('backend.inquiry.approver-inquiry');
    }

    public function create()
    {
        $vendorType = VendorType::all();
        return view('backend.inquiry.index', compact('vendorType'));
    }

    public function store(InquiryStoreRequest $request)
    {
        try {
            $inquiry = new ResInquiryMaster;
            $inquiry->inquiry_date = $request->inquiry_date;
            $inquiry->end_date = $request->end_date;
            $inquiry->start_time = $request->start_time;
            $inquiry->end_time = $request->end_time;
            $inquiry->inquiry_created_by_id = Auth::id();
            $inquiry->name = $request->name;
            $inquiry->vendor_type = $request->vendor_type;
            $inquiry->user_id = Auth::id();
            $inquiry->remarks = $request->remarks;
            $inquiry->general_term_condition_categories = json_encode($request->general_term_condition_categories);
            $inquiry->term_condition_categories_id = json_encode($request->term_condition_categories);
            $inquiry->term_condition_documents_id = json_encode($request->term_condition_documents);
            $inquiry->save();

            if (Auth::user()->hasRole('admin')) {
                $adminInquiry = new InquiryAdmin;
                $adminInquiry->inquiry_id = $inquiry->id;
                $adminInquiry->admin_id = 'all';
                $adminInquiry->save();
            } else {
                foreach ($request->admin_id as $admin) {
                    $adminInquiry = new InquiryAdmin;
                    $adminInquiry->inquiry_id = $inquiry->id;
                    $adminInquiry->admin_id = $admin;
                    $adminInquiry->save();
                }
            }

            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $inquiry);

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
            $inquiry = ResInquiryMaster::find($request->id);
            $admin = InquiryAdmin::select('admin_id')->where('inquiry_id', $inquiry->id)->get()->toArray();
            $adminId = [];
            foreach ($admin as $key => $value) {
                $adminId[] = $value['admin_id'];
            }

            return response()->json([
                'status'  => true,
                'data'    => $inquiry,
                'admin'   => $adminId,
                'message' => 'inquiry fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(InquiryUpdateRequest $request, ResInquiryMaster $inquiry)
    {
        try {
            $inquiry->inquiry_date = $request->inquiry_date;
            $inquiry->end_date = $request->end_date;
            $inquiry->start_time = $request->start_time;
            $inquiry->end_time = $request->end_time;
            $inquiry->name = $request->name;
            $inquiry->vendor_type = $request->vendor_type;
            $inquiry->remarks = $request->remarks;
            $inquiry->general_term_condition_categories = json_encode($request->general_term_condition_categories);
            $inquiry->term_condition_categories_id = json_encode($request->term_condition_categories);
            $inquiry->term_condition_documents_id = json_encode($request->term_condition_documents);

            $updatedValues = $inquiry->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiry->getOriginal($field);
            }

            $inquiry->save();

            if ($request->admin_id != null) {
                foreach ($request->admin_id as $admin) {
                    $adminData = InquiryAdmin::where('inquiry_id', $inquiry->id)->where('admin_id', $admin)->first();
                    $adminData->delete();
                    $adminInquiry = new InquiryAdmin();
                    $adminInquiry->inquiry_id = $inquiry->id;
                    $adminInquiry->admin_id = $admin;
                    $adminInquiry->save();
                }
            }

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(ResInquiryMaster $inquiry)
    {
        try {
            $oldValues = [];
            $updatedValues = ['inquiry_deleted' => $inquiry->name . " inquiry deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $inquiry->id, $oldValues, $updatedValues);

            $inquiryVendorDetails = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                $inquiryVendorDetail->delete();
            }

            $inquiryProductDetails = InquiryProductDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryProductDetails as $inquiryProductDetail) {
                $inquiryProductDetail->delete();
            }

            $inquiryRateDetails = InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)->get();

            foreach ($inquiryRateDetails as $inquiryRateDetail) {
                $inquiryRateDetail->delete();
            }

            $vendorVersions = VendorVersion::where('inquiry_id', $inquiry->id)->get();

            foreach ($vendorVersions as $vendorVersion) {
                $vendorVersion->delete();
            }

            $notifications = Notification::where('inquiry_id', $inquiry->id)->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }
            if ($inquiry->delete()) {
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


    public function upload(Request $request)
    {
        $uploadedFiles = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $myImage = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('images');
                $file->move($destinationPath, $myImage);
                $uploadedFiles[] = $myImage;
            }
        }
        return response()->json($uploadedFiles);
    }

    public function revert(Request $request)
    {
        $filename = $request->getContent();
        $fileData = str_replace(['[', ']', '"'], '', $filename);
        $filePath = public_path('images/' . $fileData);

        // Check if the file exists before attempting to delete it
        if (file_exists($filePath)) {
            unlink($filePath);
            return response()->json(['message' => 'File reverted']);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            $image = Image::find($request->image_id);
            if (!$image) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Supported document not found',
                ]);
            }
            $oldValues = [];
            $updatedValues = ['inquiry_supported_document_deleted' => (isset($image->name) ? $image->name : '') . " inquiry supported document deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $request->inquiry_id, $oldValues, $updatedValues);

            $filename = $image->image;
            $filePath = public_path('images/' . $filename);

            if (is_file($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }

            $image->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Supported document deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeProductData(SupportedDocumentStoreRequest $request)
    {
        try {
            foreach ($request->images as $supportedDocument) {
                $originalName = pathinfo($supportedDocument->getClientOriginalName(), PATHINFO_FILENAME);
                $uniqueString = substr(uniqid(), -6);
                $document = preg_replace('/[^A-Za-z0-9]/', '-', $originalName) . '-' . $uniqueString . '.' . $supportedDocument->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $supportedDocument->move($destinationPath, $document);

                $image = new Image;
                $image->inquiry_id = $request->inquiry_id;
                $image->name = $request->name;
                $image->image = $document;
                $image->save();
            }
            AuditLogHelper::storeLog('created', 'inquiry', $request->inquiry_id, [], $image);

            return response()->json([
                'status'  => true,
                'message' => 'Supported document upload successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function notification(Request $request)
    {
        $notifications = Notification::query()->where('vendor_id', null);

        if ($request->inquiry_date != null) {
            $inquiryDate = explode(' - ', $request->inquiry_date);

            if (count($inquiryDate) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[1]))->endOfDay();
                $notifications->whereBetween('created_at', [$startDate, $endDate]);
            } else {
                $date = Carbon::createFromFormat('m/d/Y', trim($inquiryDate[0]))->startOfDay();
                $notifications->where('created_at', '>=', $date);
            }
        }

        if ($request->module != null) {
            $notifications->where('module', $request->module);
        }

        // Default to today's notifications if no date filters are provided
        if (!$request->has('inquiry_date')) {
            $today = Carbon::now()->toDateString();
            $notifications->whereDate('created_at', $today);
        }

        $notifications = $notifications->get();

        return view('backend.inquiry.notification', compact('notifications'));
    }

    public function adminStatus(ResInquiryMaster $inquiry, Request $request)
    {
        try {
            $inquiry->admin_status = $request->admin_status;
            if ($request->admin_status == "Approved") {
                $inquiry->approved_by = Auth::id();
            }
            $inquiry->comment = $request->comment;
            $inquiry->admin_status_updated_at = Carbon::now();

            $updatedValues = $inquiry->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiry->getOriginal($field);
            }

            $inquiry->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            $notifications = Notification::where('inquiry_id', $inquiry->id)->get();
            foreach ($notifications as $notification) {
                $notification->admin_status = $request->admin_status;
                $notification->save();

                if ($request->admin_status == "Approved") {
                    $message = "You have received inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inquiry->name . ' ' . "Please review the details and respond accordingly.";
                    $vendor = Vendor::where('user_id', $notification->vendor_id)->first();
                    if (!empty($vendor)) {
                        Mail::to($vendor->email)->send(new AdminStatusApproveInquiryMail($message, $inquiry, $vendor));

                        $whatsAppMessage = "inquiry *" . $inquiry->name . "* Please review the details and respond accordingly.";
                        Http::withOptions(['verify' => false]) // Disable SSL verification
                        ->post(config('services.whatsapp.api_url'), [
                            'campaing_id' => config('services.whatsapp.campaing_id'),
                            'token'       => config('services.whatsapp.token'),
                            'phone'       => "91" . $vendor->phone_number_1,
                            'data'        => [
                                'name'    => $vendor->business_name,
                                'quote'   => $whatsAppMessage . " *URL :  " . route('vendor-inquiry.inquiry-products', $inquiry) . "*",
                                'company' => config('app.name'),
                            ],
                        ]);
                    }
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Status update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function followUp(ResInquiryMaster $inquiry, Request $request)
    {
        try {
            $vendor = Vendor::where('user_id', $request->id)->first();

            $message = "You have received inquiry from " . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inquiry->name . ' ' . "Please review the details and respond accordingly.";
            Mail::to($vendor->email)->send(new AdminStatusApproveInquiryMail($message, $inquiry, $vendor));

            $whatsAppMessage = "inquiry *" . $inquiry->name . "* Please review the details and respond accordingly.";
            Http::withOptions(['verify' => false]) // Disable SSL verification
            ->post(config('services.whatsapp.api_url'), [
                'campaing_id' => config('services.whatsapp.campaing_id'),
                'token'       => config('services.whatsapp.token'),
                'phone'       => "91" . $vendor->phone_number_1,
                'data'        => [
                    'name'    => $vendor->business_name,
                    'quote'   => $whatsAppMessage . " *URL :  " . route('vendor-inquiry.inquiry-products', $inquiry) . "*",
                    'company' => config('app.name'),
                ],
            ]);

            $createdValues['follow_up_sent'] = $vendor->business_name;
            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $createdValues);

            return response()->json([
                'status'  => true,
                'message' => 'Follow up send successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function finalizeQuotation(Request $request)
    {
        $oldValues = [];

        $version = InquiryVendorRateDetail::where('inquiry_id', $request->inquiry_id)->where('vendor_id', $request->vendor_id)->latest()->first();
        if ($version != null) {
            $finalizeProduct = new FinalizeVersion();
            $finalizeProduct->inquiry_id = $request->inquiry_id;
            $finalizeProduct->vendor_id = $request->vendor_id;
            $finalizeProduct->version = $version->version;
            $finalizeProduct->save();
            $vendor = Vendor::where('user_id', $request->vendor_id)->first();
            $user = User::find($request->vendor_id);
            $product = InquiryProductDetail::find($finalizeProduct->product_id);
            $message = "Congratulations!!Your Version " . $version->version . " rates is approved";
            $inq = ResInquiryMaster::find($request->inquiry_id);
            $inquiryVendor = InquiryVendorDetail::where('inquiry_id', $request->inquiry_id)->where('vendor_id', $request->vendor_id)->first();
            if ($inquiryVendor != null) {
                $inquiryVendor->status = 'close';
                $inquiryVendor->save();
            }

            $notification = new Notification();
            $notification->user_id = Auth::id();
            $notification->vendor_id = $user->id;
            $notification->inquiry_id = $inq->id;
            $notification->admin_status = 'Approved';
            $notification->from = 'admin';
            $notification->module = 'finalize_version';
            $notification->title = $message;
            $notification->status = "Open";
            $notification->save();

            $updatedValues = ['version_approved' => $user->name . " version " . $version->version . " rates is approved"];

            AuditLogHelper::storeLog('updated', 'inquiry', $inq->id, $oldValues, $updatedValues);

            Mail::to($user->email)->send(new FinalizeMail($message, $inq, $vendor));

            return response()->json([
                'status'  => true,
                'message' => "Product Approved Successfully",
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => "Something Went Wrong",
        ]);
    }

    public function compareProduct(Request $request)
    {
        $request->validate([
            'products' => 'required',
            'vendor'   => 'required',
        ]);

        $inquiryId = $request->inquiry_id;
        $vendorsId = $request->vendor;
        session()->forget('products');
        session()->forget('vendors');
        session()->forget('inquiry_id');
        Session::put('products', $request->products);
        Session::put('vendors', $request->vendor);
        Session::put('inquiry_id', $request->inquiry_id);

        if (in_array("all", $request->products)) {
            $inquiryProductDetails = InquiryProductDetail::where('inquiry_id', $inquiryId)->get();
        } else {
            $inquiryProductDetails = InquiryProductDetail::whereIn('id', $request->products)->get();
        }
        if (in_array("all", $request->vendor)) {
            $inquiryVendors = InquiryVendorDetail::select('vendor_id')->where('inquiry_id', $inquiryId)->get();
            $inquiryVendorsData = $inquiryVendors->pluck('vendor_id')->toArray();
            $vendors = Vendor::whereIn('user_id', $inquiryVendorsData)->get();
        } else {
            $vendors = Vendor::whereIn('user_id', $vendorsId)->get();
        }
//        $vendors = Vendor::whereIn('user_id', $vendorsId)->get();

        $vendorArr = [];
        $vendorColorArr = [];
        $vendorIdArr = [];
        $vendorVersions = [];
        $documentComparison = [];

        foreach ($vendors as $vendor) {
            $vendorColorArr[$vendor->user_id] = ColorHelper::generateHexColor($vendor->user_id . $inquiryId);
            $vendorArr[] = $vendor->business_name;
            $vendorIdArr[] = $vendor->user_id;
            $vendorVersions[$vendor->user_id] = '';
        }

        $productArr = [];
        $vendorTotals = [];

        foreach ($vendorIdArr as $vendorId) {
            $vendorTotals[$vendorId] = 0;
            $compareData = TechnicalDocument::where('inquiry_id', $inquiryId)->where('vendor_id', $vendorId)->get();
            $documentComparison[$vendorId] = $compareData;

            // Initialize as empty arrays
            $vendorTotals[$vendorId] = [];
            $vendorVersions[$vendorId] = [];
        }

        foreach ($inquiryProductDetails as $inquiryProductDetail) {
            $tempProductArr = [
                'product_item_description' => $inquiryProductDetail->item_description,
                'product_additional_info'  => $inquiryProductDetail->additional_info,
                'product_qty'              => $inquiryProductDetail->qty,
                'product_unit'             => $inquiryProductDetail->unit,
                'budget'                   => $inquiryProductDetail->price,
                'budget_rate'              => $inquiryProductDetail->qty * $inquiryProductDetail->price,
                'vendors'                  => []
            ];

            foreach ($vendorIdArr as $vendorId) {
                $rates = VendorVersion::where('vendor_id', $vendorId)
                    ->where('ipd_id', $inquiryProductDetail->id)
                    ->where('inquiry_id', $inquiryId)
                    ->orderBy('version', 'asc')
                    ->get();

                // If there are rates for this vendor
                if ($rates->count()) {
                    foreach ($rates as $rate) {
                        $totalWithGst = $rate->total_with_gst;
                        if ($rate->rate > 0 && $inquiryProductDetail->qty) {
                            $price = $rate->rate / $inquiryProductDetail->qty;
                        } else {
                            $price = 0;
                        }

                        // Append version-specific details under the correct vendor
                        $tempProductArr['vendors'][$vendorId][$rate->version] = [
                            'color'          => $vendorColorArr[$vendorId],
                            'price'          => $rate->rate,
                            'gst_amount'     => $rate->gst_amount,
                            'gst'            => $rate->gst_rate,
                            'remark'         => $rate->remarks,
                            'total_with_gst' => $totalWithGst,
                            'product_price'  => $price
                        ];

                        // Accumulate vendor's total amounts
                        if (!isset($vendorTotals[$vendorId][$rate->version])) {
                            $vendorTotals[$vendorId][$rate->version] = 0;
                        }
                        $vendorTotals[$vendorId][$rate->version] += $totalWithGst;

                        // Track vendor versions
                        if (!in_array($rate->version, $vendorVersions[$vendorId])) {
                            $vendorVersions[$vendorId][] = $rate->version;
                        }
                    }
                } else {
                    $tempProductArr['vendors'][$vendorId] = []; // Handle vendors without rates
                }
            }
            $productArr[] = $tempProductArr;
        }

        /********* General Charge *********/
        $inquiryGeneralChargesArr = [];
        $inquiryGeneralCharges = GeneralChargesVendorVersion::with('inquiryCharge.generalCharge')
            ->where('inquiry_id', $inquiryId)
            ->whereIn('vendor_id', $vendorIdArr)
            ->get();

        foreach ($inquiryGeneralCharges as $inquiryGeneralCharge) {
            $generalChargeId = $inquiryGeneralCharge->inquiryCharge->general_charge_id ?? "";
            $vendorId = $inquiryGeneralCharge->vendor->user_id ?? "";

            // Initialize the array for this general charge ID if not already set
            if (!isset($inquiryGeneralChargesArr[$generalChargeId])) {
                $inquiryGeneralChargesArr[$generalChargeId] = [
                    'general_charges_name' => $inquiryGeneralCharge->inquiryCharge->generalCharge->name ?? "",
                    'status'               => $inquiryGeneralCharge->inquiryCharge->status ?? "",
                    'vendors'              => []
                ];
            }

            // Ensure that we have a nested array for this vendor ID
            if (!isset($inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId])) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId] = [];
            }

            // Get the version of the current charge
            $version = $inquiryGeneralCharge->version;

            // If there's a version, populate the array, otherwise leave it as an empty array
            if ($version) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId][$version] = [
                    'id'             => $vendorId,
                    'name'           => $inquiryGeneralCharge->vendor->business_name ?? "",
                    'version'        => $version,
                    'color'          => ColorHelper::generateHexColor($vendorId . $inquiryId),
                    'quantity'       => $inquiryGeneralCharge->quantity,
                    'price'          => $inquiryGeneralCharge->rate,
                    'gst_amount'     => $inquiryGeneralCharge->gst_amount,
                    'gst'            => $inquiryGeneralCharge->gst_rate,
                    'total_with_gst' => $inquiryGeneralCharge->total_with_gst,
                    'remark'         => $inquiryGeneralCharge->remark,
                ];
            }

            // Sort the vendor keys (vendorId) in ascending order
            ksort($inquiryGeneralChargesArr[$generalChargeId]['vendors']);
        }

        // Ensure vendors with no general charge data are initialized as empty arrays
        foreach ($vendorIdArr as $vendorId) {
            foreach ($inquiryGeneralChargesArr as &$generalCharge) {
                if (!isset($generalCharge['vendors'][$vendorId])) {
                    $generalCharge['vendors'][$vendorId] = [];
                }
            }
        }

        return view('backend.inquiry-product-detail.comparison', compact('vendorArr',
            'productArr', 'inquiryId', 'vendorsId', 'vendorTotals', 'vendorColorArr', 'vendorIdArr',
            'vendorVersions', 'documentComparison', 'inquiryGeneralCharges', 'inquiryGeneralChargesArr'));
    }


    public function downloadProductComparison(Request $request)
    {
        // Fetch data from session
        $inquiryId = Session::get('inquiry_id');
        $vendorsId = Session::get('vendors');
        $products = Session::get('products');


        // Ensure $products is an array
        if (!is_array($products)) {
            $products = explode(',', $products);
        }

        if (in_array("all", $products)) {
            $inquiryProductDetails = InquiryProductDetail::where('inquiry_id', $inquiryId)->get();
        } else {
            $inquiryProductDetails = InquiryProductDetail::whereIn('id', $products)->get();
        }

        if (in_array("all", $vendorsId)) {
            $inquiryVendors = InquiryVendorDetail::select('vendor_id')->where('inquiry_id', $inquiryId)->get();
            $inquiryVendorsData = $inquiryVendors->pluck('vendor_id')->toArray();
            $vendors = Vendor::whereIn('user_id', $inquiryVendorsData)->get();
        } else {
            $vendors = Vendor::whereIn('user_id', $vendorsId)->get();
        }

        // Fetch vendors
//        $vendors = Vendor::whereIn('user_id', $vendorsId)->get();

        $vendorArr = [];
        $vendorColorArr = [];
        $vendorIdArr = [];
        $vendorVersions = [];
        $documentComparison = [];
        $vendorTotalRates = [];
        $vendorTotalGstAmounts = [];
        $vendorTotalAmounts = [];

        foreach ($vendors as $vendor) {
            $vendorColorArr[$vendor->user_id] = ColorHelper::generateHexColor($vendor->user_id . $inquiryId);
            $vendorArr[] = $vendor->business_name;
            $vendorIdArr[] = $vendor->user_id;

            // Initialize vendorVersions as an array
            $vendorVersions[$vendor->user_id] = [];
        }

        $productArr = [];
        foreach ($inquiryProductDetails as $inquiryProductDetail) {
            $tempProductArr = [
                'product_item_description' => $inquiryProductDetail->item_description,
                'product_additional_info'  => $inquiryProductDetail->additional_info,
                'product_qty'              => $inquiryProductDetail->qty,
                'product_unit'             => $inquiryProductDetail->unit,
                'budget'                   => $inquiryProductDetail->price,
                'budget_rate'              => $inquiryProductDetail->qty * $inquiryProductDetail->price,
                'vendors'                  => []
            ];

            foreach ($vendorIdArr as $vendorId) {
                $rates = VendorVersion::where('vendor_id', $vendorId)
                    ->where('ipd_id', $inquiryProductDetail->id)
                    ->where('inquiry_id', $inquiryId)
                    ->orderBy('version', 'asc')
                    ->get();

                foreach ($rates as $rate) {
                    $totalWithGst = $rate->total_with_gst;
                    $tempProductArr['vendors'][$vendorId][$rate->version] = [
                        'color'          => $vendorColorArr[$vendorId],
                        'price'          => $rate->rate,
                        'gst_amount'     => $rate->gst_amount,
                        'gst'            => $rate->gst_rate,
                        'remark'         => $rate->remarks,
                        'total_with_gst' => $totalWithGst,
                        'product_price'  => ($inquiryProductDetail->qty > 0) ? ($rate->rate / $inquiryProductDetail->qty) : $rate->rate,
                    ];

                    if (!isset($vendorTotalAmounts[$vendorId][$rate->version])) {
                        $vendorTotalAmounts[$vendorId][$rate->version] = 0;
                    }
                    $vendorTotalAmounts[$vendorId][$rate->version] += $totalWithGst;

                    // Fix here: Ensure $vendorVersions[$vendorId] is an array
                    if (!in_array($rate->version, $vendorVersions[$vendorId])) {
                        $vendorVersions[$vendorId][] = $rate->version;
                    }
                }
            }

            $productArr[] = $tempProductArr;
        }


        // Assuming totalMinRate and totalMinAmount are derived from the vendor totals
        $totalMinRate = 0;
        if (count($vendorTotalAmounts) > 0) {
            $totalMinRate = min(array_map('min', $vendorTotalAmounts));
        }
        $totalMinAmount = 0;
        if (count($vendorTotalAmounts) > 0) {
            $totalMinAmount = min(array_map('min', $vendorTotalAmounts));
        }

        $inquiryGeneralChargesArr = [];
        $inquiryGeneralCharges = GeneralChargesVendorVersion::with('inquiryCharge.generalCharge')
            ->where('inquiry_id', $inquiryId)
            ->whereIn('vendor_id', $vendorIdArr)
            ->get();

        foreach ($inquiryGeneralCharges as $inquiryGeneralCharge) {
            $generalChargeId = $inquiryGeneralCharge->inquiryCharge->general_charge_id ?? "";
            $vendorId = $inquiryGeneralCharge->vendor->user_id ?? "";

            // Initialize the array for this general charge ID if not already set
            if (!isset($inquiryGeneralChargesArr[$generalChargeId])) {
                $inquiryGeneralChargesArr[$generalChargeId] = [
                    'general_charges_name' => $inquiryGeneralCharge->inquiryCharge->generalCharge->name ?? "",
                    'status'               => $inquiryGeneralCharge->inquiryCharge->status ?? "",
                    'vendors'              => []
                ];
            }

            // Ensure that we have a nested array for this vendor ID
            if (!isset($inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId])) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId] = [];
            }

            // Get the version of the current charge
            $version = $inquiryGeneralCharge->version;

            // If there's a version, populate the array, otherwise leave it as an empty array
            if ($version) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId][$version] = [
                    'id'             => $vendorId,
                    'name'           => $inquiryGeneralCharge->vendor->business_name ?? "",
                    'version'        => $version,
                    'color'          => ColorHelper::generateHexColor($vendorId . $inquiryId),
                    'quantity'       => $inquiryGeneralCharge->quantity,
                    'price'          => $inquiryGeneralCharge->rate,
                    'gst_amount'     => $inquiryGeneralCharge->gst_amount,
                    'gst'            => $inquiryGeneralCharge->gst_rate,
                    'total_with_gst' => $inquiryGeneralCharge->total_with_gst,
                    'remark'         => $inquiryGeneralCharge->remark,
                ];
            }

            // Sort the vendor keys (vendorId) in ascending order
            ksort($inquiryGeneralChargesArr[$generalChargeId]['vendors']);
        }


        // Ensure vendors with no general charge data are initialized as empty arrays
        foreach ($vendorIdArr as $vendorId) {
            foreach ($inquiryGeneralChargesArr as &$generalCharge) {
                if (!isset($generalCharge['vendors'][$vendorId])) {
                    $generalCharge['vendors'][$vendorId] = [];
                }
            }
        }

        // Pass the data to the Excel export class
        return Excel::download(
            new ProductComparisonExport(
                $vendorArr,
                $vendorVersions,
                $productArr,
                $vendorTotalRates,
                $vendorTotalGstAmounts,
                $vendorTotalAmounts,
                $totalMinRate,
                $totalMinAmount,
                $vendorIdArr,
                $vendorColorArr,
                $inquiryGeneralChargesArr,
                $inquiryId,

            ),
            'product_comparison.xlsx'
        );
    }

    public function downloadFile($file): BinaryFileResponse
    {
        $filePath = public_path('images/' . $file);
        return response()->download($filePath);
    }

    public function fetchTermConditionCategory(Request $request)
    {
        try {
            $termConditionCategory = TermConditionCategory::where('vendor_type_id', $request->id)->get();

            return response()->json([
                'status'  => true,
                'data'    => $termConditionCategory,
                'message' => 'Term condition category fetch successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadVendorProducts($inquiryId, $vendorsId)
    {
        $inquiryProductDetails = InquiryProductDetail::where('inquiry_id', $inquiryId)->get();

        $vendors = Vendor::where('user_id', $vendorsId)->get();

        $vendorArr = [];
        $vendorColorArr = [];
        $vendorIdArr = [];
        $vendorVersions = [];
        $documentComparison = [];
        $vendorTotalRates = [];
        $vendorTotalGstAmounts = [];
        $vendorTotalAmounts = [];

        foreach ($vendors as $vendor) {
            $vendorColorArr[$vendor->user_id] = ColorHelper::generateHexColor($vendor->user_id . $inquiryId);
            $vendorArr[] = $vendor->business_name;
            $vendorIdArr[] = $vendor->user_id;

            // Initialize vendorVersions as an array
            $vendorVersions[$vendor->user_id] = [];
        }

        $productArr = [];
        foreach ($inquiryProductDetails as $inquiryProductDetail) {
            $tempProductArr = [
                'product_item_description' => $inquiryProductDetail->item_description,
                'product_additional_info'  => $inquiryProductDetail->additional_info,
                'product_qty'              => $inquiryProductDetail->qty . ' ' . $inquiryProductDetail->unit,
                'budget'                   => $inquiryProductDetail->price,
                'budget_rate'              => $inquiryProductDetail->qty * $inquiryProductDetail->price,
                'vendors'                  => []
            ];

            foreach ($vendorIdArr as $vendorId) {
                $rates = VendorVersion::where('vendor_id', $vendorId)
                    ->where('ipd_id', $inquiryProductDetail->id)
                    ->where('inquiry_id', $inquiryId)
                    ->orderBy('version', 'asc')
                    ->get();

                foreach ($rates as $rate) {
                    $totalWithGst = $rate->total_with_gst;
                    $tempProductArr['vendors'][$vendorId][$rate->version] = [
                        'color'          => $vendorColorArr[$vendorId],
                        'price'          => $rate->rate,
                        'gst_amount'     => $rate->gst_amount,
                        'gst'            => $rate->gst_rate,
                        'remark'         => $rate->remarks,
                        'total_with_gst' => $totalWithGst,
                        'product_price'  => ($inquiryProductDetail->qty > 0) ? ($rate->rate / $inquiryProductDetail->qty) : $rate->rate,
                    ];

                    if (!isset($vendorTotalAmounts[$vendorId][$rate->version])) {
                        $vendorTotalAmounts[$vendorId][$rate->version] = 0;
                    }
                    $vendorTotalAmounts[$vendorId][$rate->version] += $totalWithGst;

                    // Fix here: Ensure $vendorVersions[$vendorId] is an array
                    if (!in_array($rate->version, $vendorVersions[$vendorId])) {
                        $vendorVersions[$vendorId][] = $rate->version;
                    }
                }
            }
            $productArr[] = $tempProductArr;
        }

        // Assuming totalMinRate and totalMinAmount are derived from the vendor totals
        $totalMinRate = 0;
        if (count($vendorTotalAmounts) > 0) {
            $totalMinRate = min(array_map('min', $vendorTotalAmounts));
        }
        $totalMinAmount = 0;
        if (count($vendorTotalAmounts) > 0) {
            $totalMinAmount = min(array_map('min', $vendorTotalAmounts));
        }

        $inquiryGeneralChargesArr = [];
        $inquiryGeneralCharges = GeneralChargesVendorVersion::with('inquiryCharge.generalCharge')
            ->where('inquiry_id', $inquiryId)
            ->whereIn('vendor_id', $vendorIdArr)
            ->get();

        foreach ($inquiryGeneralCharges as $inquiryGeneralCharge) {
            $generalChargeId = $inquiryGeneralCharge->inquiryCharge->general_charge_id;
            $vendorId = $inquiryGeneralCharge->vendor->user_id;

            // Initialize the array for this general charge ID if not already set
            if (!isset($inquiryGeneralChargesArr[$generalChargeId])) {
                $inquiryGeneralChargesArr[$generalChargeId] = [
                    'general_charges_name' => $inquiryGeneralCharge->inquiryCharge->generalCharge->name,
                    'status'               => $inquiryGeneralCharge->inquiryCharge->status,
                    'vendors'              => []
                ];
            }

            // Ensure that we have a nested array for this vendor ID
            if (!isset($inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId])) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId] = [];
            }

            // Get the version of the current charge
            $version = $inquiryGeneralCharge->version;

            // If there's a version, populate the array, otherwise leave it as an empty array
            if ($version) {
                $inquiryGeneralChargesArr[$generalChargeId]['vendors'][$vendorId][$version] = [
                    'id'             => $vendorId,
                    'name'           => $inquiryGeneralCharge->vendor->business_name ?? "",
                    'version'        => $version,
                    'color'          => ColorHelper::generateHexColor($vendorId . $inquiryId),
                    'quantity'       => $inquiryGeneralCharge->quantity,
                    'price'          => $inquiryGeneralCharge->rate,
                    'gst_amount'     => $inquiryGeneralCharge->gst_amount,
                    'gst'            => $inquiryGeneralCharge->gst_rate,
                    'total_with_gst' => $inquiryGeneralCharge->total_with_gst,
                    'remark'         => $inquiryGeneralCharge->remark,
                ];
            }

            // Sort the vendor keys (vendorId) in ascending order
            ksort($inquiryGeneralChargesArr[$generalChargeId]['vendors']);
        }


        // Ensure vendors with no general charge data are initialized as empty arrays
        foreach ($vendorIdArr as $vendorId) {
            foreach ($inquiryGeneralChargesArr as &$generalCharge) {
                if (!isset($generalCharge['vendors'][$vendorId])) {
                    $generalCharge['vendors'][$vendorId] = [];
                }
            }
        }

        // Pass the data to the Excel export class
        return Excel::download(
            new VendorProductExport(
                $vendorArr,
                $vendorVersions,
                $productArr,
                $vendorTotalRates,
                $vendorTotalGstAmounts,
                $vendorTotalAmounts,
                $totalMinRate,
                $totalMinAmount,
                $vendorIdArr,
                $vendorColorArr,
                $inquiryGeneralChargesArr,
                $inquiryId,

            ),
            'vendor_product.xlsx'
        );
    }

    public function approverDetails(ResInquiryMaster $inquiry)
    {
        try {
            $inquiryApproval = InquiryApproval::where('inquiry_id', $inquiry->id)
                ->pluck('approval_user_id')->toArray();
            $approvers = User::role('approver')->get();
            $html = view('backend.inquiry.partial.approvers', compact('approvers', 'inquiryApproval', 'inquiry'))->render();

            return response()->json([
                'status' => true,
                'data'   => ['html' => $html]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function approverSubmit(ResInquiryMaster $inquiry, Request $request)
    {
        try {
            $request->validate([
                'remark' => 'nullable',
                'data'   => 'required',
            ]);

            $updatedValues = [];
            $oldValues = [];

            $oldInquiryApprovals = InquiryApproval::where('inquiry_id', $inquiry->id)->pluck('approval_user_id')->toArray();
            $userIds = [];
            foreach ($request->data as $data) {
                $userIds[] = $data['id'];
            }

            $newApprovalIds = array_diff($userIds, $oldInquiryApprovals);
            if (count($newApprovalIds) > 0) {
                $users = User::whereIn('id', $newApprovalIds)->get();
                foreach ($users as $user) {
                    $updatedValues['approver_added'] = $user->name;
                }
            }

            $removedApprovalIds = array_diff($oldInquiryApprovals, $userIds);
            if (count($removedApprovalIds) > 0) {
                $users = User::whereIn('id', $removedApprovalIds)->get();
                foreach ($users as $user) {
                    $updatedValues['approver_removed'] = $user->name;
                }
            }

            if (count($updatedValues) > 0) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            $inquiry->approval_remark = $request->remark;
            $inquiry->approval_status = 'pending';
            $inquiry->save();

            InquiryApproval::where('inquiry_id', $inquiry->id)->delete();

            if (is_array($request->data)) {
                foreach ($request->data as $key => $data) {
                    $inquiryApproval = new InquiryApproval;
                    $inquiryApproval->inquiry_id = $inquiry->id;
                    $inquiryApproval->approval_user_id = $data['id'];
                    $inquiryApproval->priority_number = $key + 1;
                    $inquiryApproval->save();
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry approvers store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function auditLog(ResInquiryMaster $inquiry, InquiryAuditLogDataTable $dataTable)
    {
        return $dataTable->render('backend.inquiry.audit-log', compact('inquiry'));
    }


    public function export(Request $request)
    {
        $resInquiryMasterQuery = ResInquiryMaster::query();
        $resInquiryMasterQuery->orderBy('inquiry_date', 'desc')->with(['user', 'vendorType', 'approval', 'inquiryAward.vendor']);

        $adminData = InquiryAdmin::select(['inquiry_id'])->pluck('inquiry_id');

        $inquiryDate = explode('-', $request->inquiry_date_filter);
        if (count($inquiryDate) === 2) {
            $inquiryStartDate = Carbon::createFromFormat('d/m/Y', trim($inquiryDate[0]))->startOfDay();
            $inquiryEndDate = Carbon::createFromFormat('d/m/Y', trim($inquiryDate[1]))->endOfDay();
            $resInquiryMasterQuery->whereBetween('inquiry_date', [$inquiryStartDate, $inquiryEndDate]);
        }
        if ($request->project_name_filter != null) {
            $resInquiryMasterQuery->where('id', $request->project_name_filter);
        }
        if ($request->vendor_type_filter != null) {
            $resInquiryMasterQuery->whereHas('vendorType', function ($q) use ($request) {
                $q->where('id', $request->vendor_type_filter);
            });
        }
        if ($request->status_filter != null) {
            $resInquiryMasterQuery->where('status', $request->status_filter);
        }
        if ($request->admin_status_filter != null) {
            $resInquiryMasterQuery->where('admin_status', $request->admin_status_filter);
        }
        if ($request->approver_status_filter != null) {
            if ($resInquiryMasterQuery->where('status', 'close')->exists()) {
                $resInquiryMasterQuery->where('approval_status', $request->approver_status_filter);
            }
        }

        if (Auth::user()->hasRole('drafter')) {
            $resInquiryMasterQuery->where('inquiry_created_by_id', Auth::id());
        }

        if (Auth::user()->hasRole('admin') && !Auth::user()->hasRole('approver')) {
            $userIds = User::where('user_id', Auth::id())->pluck('id')->toArray();
            $userIds[] = Auth::id(); // Append the authenticated user ID
            $resInquiryMasterQuery->whereIn('inquiry_created_by_id', $userIds);
        }

        if (Auth::user()->hasRole('approver') && !Auth::user()->hasRole('admin')) {
            $inquiryApprovals = InquiryApproval::where('approval_user_id', Auth::id())->pluck('inquiry_id');
            $resInquiryMasterQuery->whereIn('id', $inquiryApprovals);
            $resInquiryMasterQuery->where('status', 'close');
        }

        $inquiryReports = $resInquiryMasterQuery->whereIn('id', $adminData)->get();

        $data = [];
        foreach ($inquiryReports as $inquiryReport) {

            $allocation = "";
            if (isset($inquiryReport->inquiryAward->vendor_id)) {
                $productTotal = InquiryVendorRateDetail::where('inquiry_id', $inquiryReport->id)
                    ->where('vendor_id', $inquiryReport->inquiryAward->vendor_id)
                    ->sum('total_with_gst');
                $generalChargeTotal = InquiryGeneralCharge::where('inquiry_id', $inquiryReport->id)
                    ->where('vendor_id', $inquiryReport->inquiryAward->vendor_id)
                    ->sum('total_with_gst');

                $grandTotal = number_format($productTotal + $generalChargeTotal, 2);
                $vendorName = isset($inquiryReport->inquiryAward->vendor->business_name) ? $inquiryReport->inquiryAward->vendor->business_name : '';
                $allocation = $vendorName . ' (' . $grandTotal . ')';
            }

            $approvalDate = "";
            if ($inquiryReport->approval_status != 'pending' && $inquiryReport->inquiryApprovals->isNotEmpty()) {
                $approvalDate = Carbon::parse($inquiryReport->inquiryApprovals->last()->status_update_date_time)->format('d-m-Y h-i A');
            }
            $data[] = [
                'inquiry_date'    => $inquiryReport->inquiry_date != "" ? Carbon::parse($inquiryReport->inquiry_date)->format('d-m-Y') : "",
                'end_date'        => $inquiryReport->end_date != "" ? Carbon::parse($inquiryReport->end_date)->format('d-m-Y') : "",
                'subject'         => $inquiryReport->remarks ?? "",
                'project_name'    => $inquiryReport->name ?? "",
                'vendor_type'     => $inquiryReport->vendorType->name ?? "",
                'status'          => ucfirst($inquiryReport->status) ?? "",
                'admin_status'    => ucfirst($inquiryReport->admin_status) ?? "",
                'created_by'      => $inquiryReport->user->name ?? "",
                'approved_by'     => $inquiryReport->approval->name ?? "",
                'approver_status' => $inquiryReport->status == 'close' ? ucfirst($inquiryReport->approval_status) : '',
                'approver_date'   => $approvalDate,
                'allocation'      => $allocation,
            ];
        }

        if ($request->type == "excel") {
            return Excel::download(new InquiryMasterReportExport($data), 'InquiryReport.xlsx');
        }

        if ($request->type == "pdf") {
            $pdf = PDF::loadView('backend.inquiry.pdf', compact('data'))->setPaper('a4', 'landscape');;
            return $pdf->download('InquiryReport.pdf');
        }
    }

}
