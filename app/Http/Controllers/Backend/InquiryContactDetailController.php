<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryContactDetail\InquiryContactDetailStoreRequest;
use App\Http\Requests\InquiryContactDetail\InquiryContactDetailUpdateRequest;
use App\Models\InquiryContactDetail;
use App\Models\ResInquiryMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryContactDetailController extends Controller
{
    public function store(InquiryContactDetailStoreRequest $request, ResInquiryMaster $inquiry)
    {
        try {
            $inquiryContactDetail = new InquiryContactDetail;
            $inquiryContactDetail->user_id = Auth::id();
            $inquiryContactDetail->inquiry_id = $inquiry->id;
            $inquiryContactDetail->name = $request->name;
            $inquiryContactDetail->email = $request->email;
            $inquiryContactDetail->country_code = $request->country_code;
            $inquiryContactDetail->mobile_number = $request->mobile_number;
            $inquiryContactDetail->save();

            AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $inquiryContactDetail);

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry contact detail store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, ResInquiryMaster $inquiry)
    {
        try {
            $inquiryContactDetail = InquiryContactDetail::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $inquiryContactDetail,
                'message' => 'Inquiry contact detail fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(ResInquiryMaster $inquiry, InquiryContactDetailUpdateRequest $request)
    {
        try {
            $inquiryContactDetail = InquiryContactDetail::find($request->inquiry_contact_detail_id);
            $inquiryContactDetail->inquiry_id = $inquiry->id;
            $inquiryContactDetail->name = $request->name;
            $inquiryContactDetail->email = $request->email;
            $inquiryContactDetail->country_code = $request->country_code;
            $inquiryContactDetail->mobile_number = $request->mobile_number;

            $updatedValues = $inquiryContactDetail->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiryContactDetail->getOriginal($field);
            }

            $inquiryContactDetail->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry contact detail updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(ResInquiryMaster $inquiry, Request $request)
    {
        try {
            $inquiryContactDetail = InquiryContactDetail::find($request->id);

            $updatedValues = ['inquiry_contact_detail_deleted' => $inquiryContactDetail->name . " inquiry contact detail deleted"];

            AuditLogHelper::storeLog('deleted', 'inquiry', $inquiry->id, [], $updatedValues);

            if ($inquiryContactDetail->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Inquiry contact detail deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Inquiry contact detail not found!"
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
