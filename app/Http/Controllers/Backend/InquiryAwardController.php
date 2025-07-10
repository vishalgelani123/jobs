<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\Models\InquiryAward;
use App\Models\ResInquiryMaster;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryAwardController extends Controller
{
    public function store(Request $request, ResInquiryMaster $inquiry)
    {
        try {
            $inquiryAward = InquiryAward::where('inquiry_id', $inquiry->id)->first();
            if ($inquiryAward == null) {
                $inquiryAward = new InquiryAward;
            }
            $inquiryAward->user_id = Auth::id();
            $inquiryAward->inquiry_id = $inquiry->id;
            $inquiryAward->vendor_id = $request->vendor_id;
            $inquiryAward->save();

            $vendor = Vendor::where('user_id', $request->vendor_id)->first();

            $allocation = [
                'inquiry_allocation' => 'inquiry allocation to ' . (isset($vendor->business_name) ? $vendor->business_name : '') . ' by (' .  Auth::user()->name  . ')'
            ];

            AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, [], $allocation);

            return response()->json([
                'status'  => true,
                'message' => 'Allocation successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
