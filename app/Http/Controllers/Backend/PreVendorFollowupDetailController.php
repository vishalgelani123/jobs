<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreVendorFollowupDetail\PreVendorFollowupDetailStoreRequest;
use App\Http\Requests\PreVendorFollowupDetail\PreVendorFollowupDetailUpdateRequest;
use App\Models\PreVendorDetail;
use App\Models\PreVendorFollowupDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreVendorFollowupDetailController extends Controller
{
    public function store(PreVendorFollowupDetailStoreRequest $request, PreVendorDetail $preVendorDetail)
    {
        try {
            $preVendorFollowupDetail = new PreVendorFollowupDetail;
            $preVendorFollowupDetail->user_id = Auth::id();
            $preVendorFollowupDetail->pre_vendor_detail_id = $preVendorDetail->id;
            $preVendorFollowupDetail->type = $request->type;
            $preVendorFollowupDetail->date = $request->date;
            $preVendorFollowupDetail->remarks = $request->remark;
            $preVendorFollowupDetail->next_followup_date = $request->next_followup_date;
            $preVendorFollowupDetail->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor followup detail store successfully',
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
            $preVendorFollowupDetail = PreVendorFollowupDetail::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $preVendorFollowupDetail,
                'message' => 'Pre vendor followup detail fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(PreVendorFollowupDetailUpdateRequest $request,PreVendorDetail $preVendorDetail, PreVendorFollowupDetail $preVendorFollowupDetail)
    {
        try {
            $preVendorFollowupDetail->user_id = Auth::id();
            $preVendorFollowupDetail->pre_vendor_detail_id = $preVendorDetail->id;
            $preVendorFollowupDetail->type = $request->type;
            $preVendorFollowupDetail->date = $request->date;
            $preVendorFollowupDetail->remarks = $request->remark;
            $preVendorFollowupDetail->next_followup_date = $request->next_followup_date;
            $preVendorFollowupDetail->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor followup detail update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(PreVendorDetail $preVendorDetail, PreVendorFollowupDetail $preVendorFollowupDetail)
    {
        try {
            if ($preVendorFollowupDetail->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Pre vendor followup detail deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Pre vendor followup detail not found!"
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
