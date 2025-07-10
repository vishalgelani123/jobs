<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryGeneralCharge\InquiryGeneralChargeStoreRequest;
use App\Models\GeneralCharge;
use App\Models\InquiryGeneralCharge;
use App\Models\InquiryVendorDetail;
use App\Models\ResInquiryMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryGeneralChargeController extends Controller
{
    public function store(InquiryGeneralChargeStoreRequest $request, ResInquiryMaster $inquiry)
    {
        try {
            $updatedValues = [];
            $oldValues = [];

            $oldInquiryGeneralCharges = InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->pluck('general_charge_id')->toArray();

            $newGeneralChargeIds = array_diff($request->general_charges_name, $oldInquiryGeneralCharges);
            if (count($newGeneralChargeIds) > 0) {
                $generalCharges = GeneralCharge::whereIn('id', $newGeneralChargeIds)->get();
                foreach ($generalCharges as $generalCharge) {
                    $updatedValues[$generalCharge->name] = "general charge added";
                }
            }

            $removedGeneralChargeIds = array_diff($oldInquiryGeneralCharges, $request->general_charges_name);
            if (count($removedGeneralChargeIds) > 0) {
                $generalCharges = GeneralCharge::whereIn('id', $removedGeneralChargeIds)->get();
                foreach ($generalCharges as $generalCharge) {
                    $updatedValues[$generalCharge->name] = "general charge removed";
                }
            }

            if (count($updatedValues) > 0) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->delete();

            foreach ($request->general_charges_name as $name) {
                $chargeType = $request->general_charge_type_[$name];

                $inquiryGeneralCharge = new InquiryGeneralCharge;
                $inquiryGeneralCharge->user_id = Auth::id();
                $inquiryGeneralCharge->inquiry_id = $inquiry->id;
                $inquiryGeneralCharge->general_charge_id = $name;
                $inquiryGeneralCharge->status = $chargeType;
                $inquiryGeneralCharge->save();

                AuditLogHelper::storeLog('created', 'inquiry', $inquiry->id, [], $inquiryGeneralCharge);
            }

            if (Auth::user()->hasRole('admin')) {
                $inquiryVendorDetails = InquiryVendorDetail::where('inquiry_id', $inquiry->id)->get();
                if (count($inquiryVendorDetails) == 0) {
                    return response()->json([
                        'status'  => true,
                        'message' => 'Inquiry general charge store successfully',
                    ]);
                }
                InquiryGeneralCharge::where('inquiry_id', $inquiry->id)->delete();

                foreach ($inquiryVendorDetails as $inquiryVendorDetail) {
                    foreach ($request->general_charges_name as $name) {
                        $chargeType = $request->general_charge_type_[$name];

                        $inquiryGeneralCharge = new InquiryGeneralCharge;
                        $inquiryGeneralCharge->user_id = Auth::id();
                        $inquiryGeneralCharge->inquiry_id = $inquiry->id;
                        $inquiryGeneralCharge->vendor_id = $inquiryVendorDetail->vendor_id;
                        $inquiryGeneralCharge->general_charge_id = $name;
                        $inquiryGeneralCharge->status = $chargeType;
                        $inquiryGeneralCharge->save();
                    }
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Inquiry general charge store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(ResInquiryMaster $inquiry, InquiryGeneralCharge $inquiryGeneralCharge)
    {
        try {
            if ($inquiryGeneralCharge->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Inquiry general charge deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Inquiry general charge not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function descriptionStore(Request $request, ResInquiryMaster $inquiry)
    {
        try {
            $request->validate([
                'description_term_condition' => 'required',
            ]);

            $inquiry->description_term_condition = $request->description_term_condition;

            $updatedValues = $inquiry->getDirty();

            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $inquiry->getOriginal($field);
            }
            $inquiry->save();

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'inquiry', $inquiry->id, $oldValues, $updatedValues);
            }

            return redirect()->back()->with(['success' => 'Description/Term condition store successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

    }
}
