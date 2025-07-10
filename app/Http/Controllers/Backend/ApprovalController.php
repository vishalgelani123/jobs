<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Mail\AdminApprovalMail;
use App\Mail\ApprovalMail;
use App\Mail\DrafterApprovalMail;
use App\Models\InquiryApproval;
use App\Models\ResInquiryMaster;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function approverMailSend(ResInquiryMaster $inquiry)
    {
        try {
            $inquiryApproval = InquiryApproval::with('approvalUser')->where('inquiry_id', $inquiry->id)->where('status', '=', 'pending')->first();
            if (!empty($inquiryApproval)) {
                Mail::to($inquiryApproval->approvalUser->email)->send(new ApprovalMail($inquiryApproval, $inquiry));

                Http::withOptions(['verify' => false])
                    ->post(config('services.whatsapp.api_url'), [
                        'campaing_id' => config('services.whatsapp.campaing_id'),
                        'token'       => config('services.whatsapp.token'),
                        'phone'       => "91" . $inquiryApproval->approvalUser->mobile,
                        'data'        => [
                            'name'    => $inquiryApproval->approvalUser->name,
                            'quote'   => 'request to approve the inquiry. Here below you can find out the inquiry details (Name : ' . $inquiry->name . ') and URL :  ' . route('inquiry-master.detail', $inquiry),
                            'company' => config('app.name'),
                        ],
                    ]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Approval send successfully',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Inquiry approval not found!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function approvalStatus(ResInquiryMaster $inquiry, Request $request)
    {
        $request->validate([
            'remark' => 'nullable',
        ]);
        try {
            $inquiryApprovalStatus = InquiryApproval::with('approvalUser')->where('inquiry_id', $inquiry->id)->where('approval_user_id', Auth::id())->first();
            $inquiryApprovalStatus->status = $request->approval_status;
            $inquiryApprovalStatus->remark = $request->remark;
            $inquiryApprovalStatus->status_update_date_time = Carbon::now();

            $updatedValues = $inquiryApprovalStatus->getDirty();
            foreach ($updatedValues as $key => $updatedValue) {
                $updatedValues[$key] = $updatedValue;
                if ($key == "status") {
                    $updatedValues[$key] = "Changed to " . $updatedValue;
                }
            }
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiryApprovalStatus->getOriginal($field);
            }

            $inquiryApprovalStatus->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            if ($inquiryApprovalStatus->status == 'approved') {
                $inquiryApproval = InquiryApproval::with('approvalUser')->where('inquiry_id', $inquiry->id)->where('status', '=', 'pending')->first();

                /********* This Mail Send Admin & Drafter Last Approval *********/
                /*$user = User::where('id', $inquiry->inquiry_created_by_id)->first();

                if (!empty($user)) {
                    $adminUser = User::where('id', $user->user_id)->first();

                    $message = 'Inquiry approved by ' . Auth::user()->name;
                    Mail::to($user->email)->send(new DrafterApprovalMail($message, $inquiry, $user));

                    if (!empty($adminUser)) {
                        Mail::to($adminUser->email)->send(new AdminApprovalMail($message, $inquiry, $adminUser));
                    }
                }*/
                /********* End *********/

                if (!empty($inquiryApproval)) {
                    Mail::to($inquiryApproval->approvalUser->email)->send(new ApprovalMail($inquiryApproval, $inquiry));

                    /********* All Mail send Admin & Drafter To Remove This Condition Uper Condition Remove *********/
                    $user = User::where('id', $inquiry->inquiry_created_by_id)->first();
                    if (!empty($user)) {
                        $adminUser = User::where('id', $user->user_id)->first();

                        $message = 'Inquiry approved by ' . Auth::user()->name;
                        Mail::to($user->email)->send(new DrafterApprovalMail($message, $inquiry, $user));

                        if (!empty($adminUser)) {
                            Mail::to($adminUser->email)->send(new AdminApprovalMail($message, $inquiry, $adminUser));
                        }
                    }
                    /********* End *********/

                    Http::withOptions(['verify' => false])
                        ->post(config('services.whatsapp.api_url'), [
                            'campaing_id' => config('services.whatsapp.campaing_id'),
                            'token'       => config('services.whatsapp.token'),
                            'phone'       => "91" . $inquiryApproval->approvalUser->mobile,
                            'data'        => [
                                'name'    => $inquiryApproval->approvalUser->name,
                                'quote'   => 'request to approve the inquiry. Here below you can find out the inquiry details (Name : ' . $inquiry->name . ') and URL :  ' . route('inquiry-master.detail', $inquiry),
                                'company' => config('app.name'),
                            ],
                        ]);
                } else {
                    $inquiry->approval_status = $request->approval_status;
                    $inquiry->save();
                }
            } else {
                $inquiry->approval_status = $request->approval_status;
                $inquiry->save();
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

}
