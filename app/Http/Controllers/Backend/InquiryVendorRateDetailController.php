<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Helpers\ColorHelper;
use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryDocument\TechnicalDocumentStoreRequest;
use App\Mail\AdminProductMail;
use App\Mail\ProductMail;
use App\Models\GeneralChargesVendorVersion;
use App\Models\GeneralTermConditionCategory;
use App\Models\Image;
use App\Models\InquiryAdmin;
use App\Models\InquiryGeneralCharge;
use App\Models\InquiryVendorDetail;
use App\Models\InquiryProductDetail;
use App\Models\InquiryVendorRateDetail;
use App\Models\Notification;
use App\Models\ResInquiryMaster;
use App\Models\TechnicalDocument;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorVersion;
use App\Models\VendorVersionRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InquiryVendorRateDetailController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function index(ResInquiryMaster $inquiry)
    {
        $inquiry->load('user', 'approval');
        $details = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->first();
        $inquiryProductDetails = [];
        if ($details != null) {
            $productsData = json_decode($details->product_id, true);
            if (is_array($productsData)) {
                $inquiryProductDetails = InquiryProductDetail::whereIn('id', $productsData)->get();
            }
        }
        $documents = TechnicalDocument::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->get();
        $totalGST = 0;
        foreach ($inquiryProductDetails as $details) {
            $rate = \App\Models\InquiryVendorRateDetail::where('vendor_id', Auth::id())->where('ipd_id', $details->id)->where('inquiry_id', $inquiry->id)->first();
            $vendor = null;
            if ($rate != null) {
                $vendor = \App\Models\VendorVersion::where('version', $rate->version)->where('vendor_id', Auth::id())->where('ipd_id', $details->id)->where('inquiry_id', $inquiry->id)->first();
            }
            if (isset($vendor->total_with_gst)) {
                $totalGST += $vendor->total_with_gst;
            }
        }
        $user = Vendor::where('user_id', Auth::id())->first();
        $vendorData = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->first();
        $versionData = InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->first();

        $version = 0;
        if ($versionData != null) {
            $version = $versionData->version;
        }
        $vendorVersions = VendorVersion::select('version')->groupBy('version')->where('user_id', Auth::id())->where('inquiry_id', $inquiry->id)->get()->toArray();
        $images = Image::where('inquiry_id', $inquiry->id)->get();

        $generalTermConditionsCategories = [];
        $generalTermConditionDecodedArray = json_decode($inquiry->general_term_condition_categories, true);
        if (is_array($generalTermConditionDecodedArray)) {
            $generalTermConditionsCategories = GeneralTermConditionCategory::whereIn('id', $generalTermConditionDecodedArray)->get();
        }

        $inquiryGeneralCharges = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->get();
        $InquiryGeneralChargeVersionData = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->where('vendor_id', Auth::id())->first();

        $generalChargeVersion = 0;
        if ($InquiryGeneralChargeVersionData != null) {
            $generalChargeVersion = $InquiryGeneralChargeVersionData->version;
        }
        $chargesVendorVersions = GeneralChargesVendorVersion::select('version')->groupBy('version')->where('inquiry_id', $inquiry->id)->where('user_id', Auth::id())->get()->toArray();
        $vrArr = [];
        foreach ($vendorVersions as $vr) {
            $vrArr[intval($vr['version'])] = ColorHelper::generateHexColor(intval($vr['version']) . $inquiry->id);
        }
        return view('backend.vendor-inquiry.index', compact('inquiry', 'documents', 'inquiryProductDetails', 'version', 'vendorData', 'user', 'images', 'generalTermConditionsCategories', 'totalGST', 'inquiryGeneralCharges', 'generalChargeVersion', 'vendorVersions', 'chargesVendorVersions', 'vrArr'));
    }

    public function store(Request $request)
    {
        try {
            if (isset($request->data[0]['productInquiry'])) {
                foreach ($request->data[0]['productInquiry'] as $data) {
                    if (isset($data['inquiry_id']) && $data['inquiry_id'] != "" && isset($data['product_id']) && $data['product_id'] != "") {
                        $inquiryVendor = InquiryVendorRateDetail::where('inquiry_id', $data['inquiry_id'])->where('ipd_id', $data['product_id'])->where('vendor_id', Auth::id())->first();

                        if ($inquiryVendor == null) {
                            $inquiryVendor = new InquiryVendorRateDetail();
                            $version = 1;
                        } else {
                            if ($inquiryVendor->version == null) {
                                $version = 1;
                            } else {
                                $version = $inquiryVendor->version + 1;
                            }
                        }

                        if (!isset($data['vendor_price'])) {
                            \Log::info("Vendor price error : not set - Inquiry id : " . $data['inquiry_id'] . " Vendor id : " . Auth::id());
                        }
                        if ($data['vendor_price'] == null) {
                            \Log::info("Vendor price error : null - Inquiry id : " . $data['inquiry_id'] . " Vendor id : " . Auth::id());
                        }
                        if ($data['vendor_price'] == "") {
                            \Log::info("Vendor price error : space - Inquiry id : " . $data['inquiry_id'] . " Vendor id : " . Auth::id());
                        }

                        //if (isset($data['vendor_price']) && $data['vendor_price'] != null) {
                        $inquiryVendor->inquiry_id = $data['inquiry_id'];
                        $inquiryVendor->vendor_id = Auth::id();
                        $inquiryVendor->ipd_id = $data['product_id'];
                        $inquiryVendor->rate = $data['vendor_price'];
                        $inquiryVendor->version = $version;
                        $inquiryVendor->remarks = $data['vendor_description'];
                        $inquiryVendor->gst_rate = $data['gst_rate'];
                        $inquiryVendor->gst_amount = $data['gst_amount'];
                        $inquiryVendor->total_with_gst = $data['total_amount_gst'];
                        $inquiryVendor->save();

                        $vendorVersion = new VendorVersion();
                        $vendorVersion->inquiry_id = $data['inquiry_id'];
                        $vendorVersion->vendor_id = Auth::id();
                        $vendorVersion->ipd_id = $data['product_id'];
                        $vendorVersion->user_id = Auth::id();
                        $vendorVersion->rate = $data['vendor_price'];
                        $vendorVersion->version = $version;
                        $vendorVersion->remarks = $data['vendor_description'];
                        $vendorVersion->gst_rate = $data['gst_rate'];
                        $vendorVersion->gst_amount = $data['gst_amount'];
                        $vendorVersion->total_with_gst = $data['total_amount_gst'];
                        $vendorVersion->save();

                        $inquiryProductDetail = InquiryProductDetail::find($data['product_id']);
                        $createdValues[$inquiryProductDetail->item_description] = $inquiryVendor;
                        //}
                    }
                }
                if (count($createdValues) > 0) {
                    AuditLogHelper::storeLog('created', 'inquiry', $data['inquiry_id'], [], $createdValues);
                }
            }
            if (isset($request->data[1]['generalChargesInquiry'])) {
                foreach ($request->data[1]['generalChargesInquiry'] as $data) {
                    if (isset($data['general_charges_id']) && $data['general_charges_id'] != "" && isset($data['inquiry_id']) && $data['inquiry_id'] != "") {
                        $inquiryGeneralChargeVendor = InquiryGeneralCharge::where('inquiry_id', $data['inquiry_id'])->where('id', $data['general_charges_id'])->where('vendor_id', Auth::id())->first();

                        if ($inquiryGeneralChargeVendor == null) {
                            $inquiryGeneralChargeVendor = new InquiryGeneralCharge;
                            $generalChargeVersion = 1;
                        } else {
                            if ($inquiryGeneralChargeVendor->version == null) {
                                $generalChargeVersion = 1;
                            } else {
                                $generalChargeVersion = $inquiryGeneralChargeVendor->version + 1;
                            }
                        }

                        $inquiryGeneralChargeVendor->version = $generalChargeVersion;
                        $inquiryGeneralChargeVendor->quantity = $data['general_charges_quantity'] ?? 0;
                        $inquiryGeneralChargeVendor->rate = $data['general_charges_rate'] ?? 0;
                        $inquiryGeneralChargeVendor->gst_rate = $data['general_charges_gst_rate'] ?? 0;
                        $inquiryGeneralChargeVendor->gst_amount = $data['general_charges_gst_amount'] ?? 0;
                        $inquiryGeneralChargeVendor->total_with_gst = $data['general_charges_total_amount_gst'] ?? 0;
                        $inquiryGeneralChargeVendor->remark = $data['general_charges_remark'];
                        $inquiryGeneralChargeVendor->save();

                        $generalChargesVendorVersion = new GeneralChargesVendorVersion;
                        $generalChargesVendorVersion->inquiry_id = $data['inquiry_id'];
                        $generalChargesVendorVersion->vendor_id = Auth::id();
                        $generalChargesVendorVersion->user_id = Auth::id();
                        $generalChargesVendorVersion->inquiry_general_charges_id = $data['general_charges_id'];
                        $generalChargesVendorVersion->version = $generalChargeVersion;
                        $generalChargesVendorVersion->quantity = $data['general_charges_quantity'] ?? 0;
                        $generalChargesVendorVersion->rate = $data['general_charges_rate'] ?? 0;
                        $generalChargesVendorVersion->gst_rate = $data['general_charges_gst_rate'] ?? 0;
                        $generalChargesVendorVersion->gst_amount = $data['general_charges_gst_amount'] ?? 0;
                        $generalChargesVendorVersion->total_with_gst = $data['general_charges_total_amount_gst'] ?? 0;
                        $generalChargesVendorVersion->remark = $data['general_charges_remark'];
                        $generalChargesVendorVersion->save();

                        $generalCreatedValues[$inquiryGeneralChargeVendor->generalCharge->name] = $inquiryGeneralChargeVendor;

                    }
                }
                if (count($generalCreatedValues) > 0) {
                    AuditLogHelper::storeLog('created', 'inquiry', $data['inquiry_id'], [], $generalCreatedValues);
                }
            }

            $inq = ResInquiryMaster::find($data['inquiry_id']);
            if ($request->remarks != null) {
                $remark = new VendorVersionRemark();
                $remark->vendor_id = Auth::id();
                $remark->inquiry_id = $inq->id;
                $remark->version = $version;
                $remark->remarks = $request->remarks;
                $remark->save();
                AuditLogHelper::storeLog('created', 'inquiry', $remark->inquiry_id, [], $remark);
            }

            $vendor = User::where('id', $inq->user_id)->first();
            $message = "You have received a new price inquiry for version" . ' ' . $version . ' ' . "of " . ' ' . Auth::user()->name . ' ' . "The inquiry is titled" . ' ' . $inq->name;

            if (isset($vendor->email) && $vendor->email != "") {
                Mail::to($vendor->email)->send(new ProductMail($message, $inq));
            }

            $notification = new Notification();
            $notification->user_id = Auth::id();
            $notification->vendor_id = isset($vendor->id) ? $vendor->id : '';
            $notification->from = 'vendor';
            $notification->module = 'Inquiry';
            $notification->inquiry_id = $data['inquiry_id'];
            $notification->title = $message;
            $notification->status = "Open";
            $notification->save();
            $inquiryAdmin = InquiryAdmin::where('inquiry_id', $inq->id)->first();

            if ($inquiryAdmin->admin_id == "all") {
                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    if ($inq->user_id == $admin->id) {
                        continue;
                    }
                    if (isset($admin->email) && $admin->email != "") {
                        Mail::to($admin->email)->send(new AdminProductMail($message, $inq));
                    }
                    $notification = new Notification();
                    $notification->user_id = Auth::id();
                    $notification->vendor_id = isset($admin->id) ? $admin->id : '';
                    $notification->from = 'vendor';
                    $notification->module = 'Inquiry';
                    $notification->inquiry_id = $data['inquiry_id'];
                    $notification->title = $message;
                    $notification->status = "Open";
                    $notification->save();
                }
            } else {
                $admin = User::find($inquiryAdmin->admin_id);
                if (isset($admin->email) && $admin->email != "") {
                    Mail::to($admin->email)->send(new AdminProductMail($message, $inq));
                }
                $notification = new Notification();
                $notification->user_id = Auth::id();
                $notification->vendor_id = isset($admin->id) ? $admin->id : '';
                $notification->from = 'vendor';
                $notification->module = 'Inquiry';
                $notification->inquiry_id = $data['inquiry_id'];
                $notification->title = $message;
                $notification->status = "Open";
                $notification->save();
            }

            $notification = new Notification;
            $notification->user_id = Auth::id();
            $notification->vendor_id = isset($admin->id) ? $admin->id : '';
            $notification->from = 'vendor';
            $notification->module = 'drafter_inquiry';
            $notification->inquiry_id = $data['inquiry_id'];
            $notification->title = $message;
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

    public function storeDocument(TechnicalDocumentStoreRequest $request)
    {
        try {
            $version = VendorVersion::where('inquiry_id', $request->inquiry_id)->where('vendor_id', Auth::id())->latest()->first();
            $vendorVersion = 1;
            if ($version != null) {
                $vendorVersion = $version->version + 1;
            }

            foreach ($request->images as $technicalDocument) {
                $originalName = pathinfo($technicalDocument->getClientOriginalName(), PATHINFO_FILENAME);
                $uniqueString = substr(uniqid(), -6);
                $document = preg_replace('/[^A-Za-z0-9]/', '-', $originalName) . '-' . $uniqueString . '.' . $technicalDocument->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $technicalDocument->move($destinationPath, $document);

                $technicalDocumentStore = new TechnicalDocument;
                $technicalDocumentStore->inquiry_id = $request->inquiry_id;
                $technicalDocumentStore->document_name = $request->name;
                $technicalDocumentStore->document = $document;
                $technicalDocumentStore->vendor_id = Auth::id();
                $technicalDocumentStore->version = $vendorVersion;
                $technicalDocumentStore->save();
            }
            AuditLogHelper::storeLog('created', 'inquiry', $technicalDocumentStore->inquiry_id, [], $technicalDocumentStore);

            return response()->json([
                'status'  => true,
                'message' => 'Technical document upload successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            $technicalDocumentFind = TechnicalDocument::find($request->document_id);
            if (!$technicalDocumentFind) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Technical document not found',
                ]);
            }

            $oldValues = [];
            $updatedValues = ['technical_document_deleted' => $technicalDocumentFind->document_name . " technical document deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $request->inquiry_id, $oldValues, $updatedValues);

            $filename = $technicalDocumentFind->document;
            $filePath = public_path('images/' . $filename);

            if (is_file($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }

            $technicalDocumentFind->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Technical document deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
