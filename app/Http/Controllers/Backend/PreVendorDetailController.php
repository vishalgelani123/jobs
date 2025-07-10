<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PreVendorDetailDataTable;
use App\DataTables\PreVendorFollowupDetailDataTable;
use App\Helpers\GenerateStringNumberHelper;
use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreVendorDetail\PreVendorBulkSendRequest;
use App\Http\Requests\PreVendorDetail\PreVendorDetailStoreRequest;
use App\Http\Requests\PreVendorDetail\PreVendorDetailUpdateRequest;
use App\Http\Requests\PreVendorDetail\PreVendorSendRequest;
use App\Mail\PreVendorDetailMail;
use App\Models\Branch;
use App\Models\BranchDocument;
use App\Models\Country;
use App\Models\PreVendorCategory;
use App\Models\PreVendorDetail;
use App\Models\PreVendorDetailItem;
use App\Models\PreVendorFollowupDetail;
use App\Models\PreVendorSendHistory;
use App\Models\PreVendorSubCategory;
use App\Models\SmtpSetting;
use App\Models\State;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocument;
use App\Models\VendorType;
use App\Models\WhatsAppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PreVendorDetailController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function index(PreVendorDetailDataTable $dataTable)
    {
        $preVendorCategories = PreVendorCategory::all();
        $preVendorSubCategories = PreVendorSubCategory::all();
        $smtpSetting = SmtpSetting::first();
        $whatsAppSetting = WhatsAppSetting::first();
        $vendorTypes = VendorType::all();

        $country = Country::where('name', 'India')->first();
        $states = State::where('country_id', $country->id)->get();
        return $dataTable->render('backend.pre-vendor-detail.index', compact('preVendorCategories', 'preVendorSubCategories', 'smtpSetting', 'whatsAppSetting', 'states', 'vendorTypes', 'preVendorSubCategories'));
    }

    public function store(PreVendorDetailStoreRequest $request)
    {
        try {
            $preVendorDetail = new PreVendorDetail;
            $preVendorDetail->user_id = Auth::id();
            $preVendorDetail->name = $request->name;
            $preVendorDetail->mobile = $request->mobile;
            $preVendorDetail->email = $request->email;
            $preVendorDetail->state_id = $request->state;
            $preVendorDetail->city_id = $request->city;
            $preVendorDetail->address = $request->address;
            $preVendorDetail->vendor_type_id = $request->vendor_type;
            $preVendorDetail->save();

            foreach ($request->pre_vendor_sub_category as $subCategory) {
                $preVendorSubCategory = PreVendorSubCategory::where('id', $subCategory)->first();
                $preVendorDetailItem = new PreVendorDetailItem;
                $preVendorDetailItem->pre_vendor_detail_id = $preVendorDetail->id;
                $preVendorDetailItem->pre_vendor_category_id = $preVendorSubCategory->pre_vendor_category_id;
                $preVendorDetailItem->pre_vendor_sub_category_id = $subCategory;
                $preVendorDetailItem->save();
            }

            $preVendorDetail->invitation_code = strtoupper(GenerateStringNumberHelper::generateTimeRandomString(5));
            $preVendorDetail->save();

            $bulkSendTypes = [];
            if ($request->has('send') && is_array($request->send) && count($request->send) > 0) {
                $bulkSendTypes = $request->send;
            }
            if (in_array('mail', $bulkSendTypes)) {
                $this->mailSend($preVendorDetail);
            }
            if (in_array('whatsapp', $bulkSendTypes)) {
                $this->sendWhatsApp($preVendorDetail);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor detail store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function bulkSend(PreVendorBulkSendRequest $request)
    {
        try {
            $bulkSendTypes = $request->bulk_send;
            $preVendorDetails = PreVendorDetail::whereIn('id', explode(",", $request->selected_rows))->get();
            foreach ($preVendorDetails as $preVendorDetail) {
                if (in_array('mail', $bulkSendTypes)) {
                    $this->mailSend($preVendorDetail);
                }
                if (in_array('whatsapp', $bulkSendTypes)) {
                    $this->sendWhatsApp($preVendorDetail);
                }
            }
            if (in_array('mail', $bulkSendTypes) && in_array('whatsapp', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Mail & WhatsApp send successfully',
                ]);
            }
            if (in_array('mail', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Mail send successfully',
                ]);
            }
            if (in_array('whatsapp', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'WhatsApp send successfully',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function send(PreVendorSendRequest $request)
    {
        try {
            $bulkSendTypes = $request->send;
            $preVendorDetail = PreVendorDetail::where('id', $request->pre_vendor)->first();

            if (in_array('mail', $bulkSendTypes)) {
                $this->mailSend($preVendorDetail);
            }
            if (in_array('whatsapp', $bulkSendTypes)) {
                $this->sendWhatsApp($preVendorDetail);
            }

            if (in_array('mail', $bulkSendTypes) && in_array('whatsapp', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Mail & WhatsApp send successfully',
                ]);
            }
            if (in_array('mail', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Mail send successfully',
                ]);
            }
            if (in_array('whatsapp', $bulkSendTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'WhatsApp send successfully',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function mailSend(PreVendorDetail $preVendorDetail)
    {
        if ($preVendorDetail->status == "open") {
            try {
                Mail::to($preVendorDetail->email)->send(new PreVendorDetailMail($preVendorDetail));

                $PreVendorSendHistory = new PreVendorSendHistory;
                $PreVendorSendHistory->user_id = Auth::id();
                $PreVendorSendHistory->pre_vendor_detail_id = $preVendorDetail->id;
                $PreVendorSendHistory->mail_status = '1';
                $PreVendorSendHistory->send_type = 'mail';
                $PreVendorSendHistory->save();
            } catch (\Exception $e) {
                $PreVendorSendHistory = new PreVendorSendHistory;
                $PreVendorSendHistory->user_id = Auth::id();
                $PreVendorSendHistory->pre_vendor_detail_id = $preVendorDetail->id;
                $PreVendorSendHistory->mail_status = '0';
                $PreVendorSendHistory->mail_description = $e->getMessage();
                $PreVendorSendHistory->send_type = 'mail';
                $PreVendorSendHistory->save();
            }
        }
    }

    public function sendWhatsApp(PreVendorDetail $preVendorDetail)
    {
        if ($preVendorDetail->status == "open") {
            try {
                $response = Http::withOptions(['verify' => false]) // Disable SSL verification
                ->post(config('services.whatsapp.api_url'), [
                    'campaing_id' => config('services.whatsapp.campaing_id'),
                    'token'       => config('services.whatsapp.token'),
                    'phone'       => "91" . $preVendorDetail->mobile,
                    'data'        => [
                        'name'    => $preVendorDetail->name,
                        'quote'   => 'invitation here is your Invitation code : ' . $preVendorDetail->invitation_code . ' and URL :  ' . route('pre.vendor.invitation.detail', $preVendorDetail->invitation_code),
                        'company' => config('app.name'),
                    ],
                ]);

                if ($response->failed()) {
                    $PreVendorSendHistory = new PreVendorSendHistory;
                    $PreVendorSendHistory->user_id = Auth::id();
                    $PreVendorSendHistory->pre_vendor_detail_id = $preVendorDetail->id;
                    $PreVendorSendHistory->whatsapp_status = '0';
                    $PreVendorSendHistory->whatsapp_description = $response->body();
                    $PreVendorSendHistory->send_type = 'whatsapp';
                    $PreVendorSendHistory->save();
                } else {
                    $PreVendorSendHistory = new PreVendorSendHistory;
                    $PreVendorSendHistory->user_id = Auth::id();
                    $PreVendorSendHistory->pre_vendor_detail_id = $preVendorDetail->id;
                    $PreVendorSendHistory->whatsapp_status = '1';
                    $PreVendorSendHistory->send_type = 'whatsapp';
                    $PreVendorSendHistory->save();
                }
            } catch (\Exception $e) {
                $PreVendorSendHistory = new PreVendorSendHistory;
                $PreVendorSendHistory->user_id = Auth::id();
                $PreVendorSendHistory->pre_vendor_detail_id = $preVendorDetail->id;
                $PreVendorSendHistory->whatsapp_status = '0';
                $PreVendorSendHistory->whatsapp_description = $e->getMessage();
                $PreVendorSendHistory->send_type = 'whatsapp';
                $PreVendorSendHistory->save();
            }
        }
    }

    public function sendHistory(Request $request)
    {
        try {
            $preVendorSendHistories = PreVendorSendHistory::orderBy('id', 'desc')->where('pre_vendor_detail_id', $request->id)->get();
            $html = view('backend.pre-vendor-detail.partial.send-history', compact('preVendorSendHistories'))->render();

            return response()->json([
                'status'  => true,
                'data'    => $html,
                'message' => 'Pre vendor detail fetched successfully',
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
            $preVendorDetail = PreVendorDetail::with(['preVendorDetailItems.preVendorSubCategory', 'city'])->find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $preVendorDetail,
                'message' => 'Pre vendor detail fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(PreVendorDetailUpdateRequest $request, PreVendorDetail $preVendorDetail)
    {
        try {
            //$preVendorDetail->user_id = Auth::id();
            $preVendorDetail->name = $request->name;
            $preVendorDetail->mobile = $request->mobile;
            $preVendorDetail->email = $request->email;
            $preVendorDetail->state_id = $request->state;
            $preVendorDetail->city_id = $request->city;
            $preVendorDetail->address = $request->address;
            $preVendorDetail->vendor_type_id = $request->vendor_type;
            $preVendorDetail->save();

            PreVendorDetailItem::where('pre_vendor_detail_id', $preVendorDetail->id)->delete();

            foreach ($request->pre_vendor_sub_category as $subCategory) {

                $preVendorSubCategories = PreVendorSubCategory::where('id', $subCategory)->get();

                foreach ($preVendorSubCategories as $preVendorSubCategory) {
                    $preVendorDetailItem = new PreVendorDetailItem;
                    $preVendorDetailItem->pre_vendor_detail_id = $preVendorDetail->id;
                    $preVendorDetailItem->pre_vendor_category_id = $preVendorSubCategory->pre_vendor_category_id;
                    $preVendorDetailItem->pre_vendor_sub_category_id = $subCategory;
                    $preVendorDetailItem->save();
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor detail update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function details(PreVendorFollowupDetailDataTable $dataTable, PreVendorDetail $preVendorDetail)
    {
        return $dataTable->render('backend.pre-vendor-followup-detail.index', compact('preVendorDetail'));
    }

    public function delete(PreVendorDetail $preVendorDetail)
    {
        try {
            $user = User::where('invite_vendor_id', $preVendorDetail->id)->where('is_admin_created', '0')->first();
            $vendor = Vendor::where('user_id', $user->id)->first();

            if (!empty($vendor)) {
                $branchDocuments = BranchDocument::where('vendor_id', $vendor->id)->get();
                foreach ($branchDocuments as $branchDocument) {
                    $branchDocumentPath = public_path("branch_documents" . "/" . $branchDocument->document);
                    if (file_exists($branchDocumentPath)) {
                        unlink($branchDocumentPath);
                    }
                    $branchDocument->delete();
                }

                $vendorDocuments = VendorDocument::where('vendor_id', $vendor->id)->get();
                foreach ($vendorDocuments as $vendorDocument) {
                    $vendorDocumentPath = public_path("vendor_documents" . "/" . $vendorDocument->document);
                    if (file_exists($vendorDocumentPath)) {
                        unlink($vendorDocumentPath);
                    }
                    $vendorDocument->delete();
                }

                Branch::where('vendor_id', $vendor->id)->delete();
            }
            Vendor::where('user_id', $user->id)->delete();
            User::where('id', $user->id)->delete();
            PreVendorFollowupDetail::where('pre_vendor_detail_id', $preVendorDetail->id)->delete();

            if ($preVendorDetail->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Pre vendor detail deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Pre vendor detail not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function status(Request $request)
    {
        try {
            $preVendorDetail = PreVendorDetail::where('id', $request->status_id)->first();
            $preVendorDetail->status = $request->status;
            $preVendorDetail->save();
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
}
