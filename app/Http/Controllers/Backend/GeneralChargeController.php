<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GeneralChargeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralCharge\GeneralChargeStoreRequest;
use App\Http\Requests\GeneralCharge\GeneralChargeUpdateRequest;
use App\Models\GeneralCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralChargeController extends Controller
{
    public function index(GeneralChargeDataTable $dataTable)
    {
        return $dataTable->render('backend.general-charge.index');
    }

    public function store(GeneralChargeStoreRequest $request)
    {
        try {
            $generalCharge = new GeneralCharge;
            $generalCharge->user_id = Auth::id();
            $generalCharge->name = $request->name;
            $generalCharge->save();

            return response()->json([
                'status'  => true,
                'message' => 'General charge store successfully',
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
            $generalCharge = GeneralCharge::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $generalCharge,
                'message' => 'General charge fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(GeneralChargeUpdateRequest $request, GeneralCharge $generalCharge)
    {
        try {
            $generalCharge->user_id = Auth::id();
            $generalCharge->name = $request->name;
            $generalCharge->save();

            return response()->json([
                'status'  => true,
                'message' => 'General charge update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(GeneralCharge $generalCharge)
    {
        try {
            if ($generalCharge->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'General charge deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "General charge not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function statusChange(GeneralCharge $generalCharge)
    {
        if ($generalCharge->status == 'active') {
            $generalCharge->status = 'inactive';
        } else {
            $generalCharge->status = 'active';
        }
        $generalCharge->save();

        return response()->json([
            'status'  => true,
            'message' => "Status updated successfully"
        ]);
    }
}
