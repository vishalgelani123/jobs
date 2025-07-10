<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquirySendMail\InquirySendMailStoreRequest;
use App\Models\InquiryContactDetail;
use App\Models\ResInquiryMaster;
use App\Models\SendMail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class SendMailController extends Controller
{
    public function store(InquirySendMailStoreRequest $request, ResInquiryMaster $inquiry)
    {
        try {
            if (is_array($request->send_mail_to_users)) {
                $vendor = Vendor::where('user_id', $request->send_mail_vendor_id)->first();

                if (isset($vendor->email) && $vendor != null) {
                    if (in_array('vendor', $request->send_mail_to_users)) {
                        $sendMail = new SendMail;
                        $sendMail->user_id = Auth::id();
                        $sendMail->name = $vendor->business_name;
                        $sendMail->email = $vendor->email;
                        $sendMail->subject = "PURCHASE ORDER/WORK ORDER MAIL CONFIRMATION";
                        $sendMail->description = $request->mail_description;
                        $sendMail->save();

                        $updatedValues = ['allocation_mail_sent' => "Inquiry allocation mail sent to " . isset($vendor->business_name) ? $vendor->business_name : ''];
                        AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $updatedValues);
                    }

                    if (in_array('admin_drafter', $request->send_mail_to_users)) {
                        $approveAdmins = array_values(array_unique(array_filter([$inquiry->inquiry_created_by_id, $inquiry->approved_by], fn($value) => $value !== null && $value !== "")));

                        if (count($approveAdmins) > 0) {
                            $admins = User::whereIn('id', $approveAdmins)->get();
                            foreach ($admins as $admin) {
                                $sendMail = new SendMail;
                                $sendMail->user_id = Auth::id();
                                $sendMail->name = $vendor->business_name;
                                $sendMail->email = $admin->email;
                                $sendMail->subject = "PURCHASE ORDER/WORK ORDER MAIL CONFIRMATION";
                                $sendMail->description = $request->mail_description;
                                $sendMail->save();

                                $updatedValues = ['allocation_mail_sent' => "Inquiry allocation mail sent to " . isset($admin->name) ? $admin->name : ''];
                                AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $updatedValues);
                            }
                        }
                    }

                    if (in_array('contact_persons', $request->send_mail_to_users)) {
                        $inquiryContacts = InquiryContactDetail::where('inquiry_id', $inquiry->id)->whereNotNull('email')->get();
                        foreach ($inquiryContacts as $inquiryContact) {
                            $sendMail = new SendMail;
                            $sendMail->user_id = Auth::id();
                            $sendMail->name = $vendor->business_name;
                            $sendMail->email = $inquiryContact->email;
                            $sendMail->subject = "PURCHASE ORDER/WORK ORDER MAIL CONFIRMATION";
                            $sendMail->description = $request->mail_description;
                            $sendMail->save();

                            $updatedValues = ['allocation_mail_sent' => "Inquiry allocation mail sent to " . isset($inquiryContact->name) ? $inquiryContact->name : ''];
                            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $updatedValues);
                        }
                    }

                    return response()->json([
                        'status'  => true,
                        'message' => 'Mail sent successfully',
                    ]);
                }
                return response()->json([
                    'status'  => false,
                    'message' => 'Something went wrong!',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
