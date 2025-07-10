<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorDocDetail\VendorDocDetailStoreRequest;
use App\Http\Requests\VendorType\VendorTypeStoreRequest;
use App\Http\Requests\VendorType\VendorTypeUpdateRequest;
use App\Models\VendorDocDetail;
use App\Models\VendorDocType;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorTypeController extends Controller
{
    public function index(VendorTypeDataTable $dataTable)
    {
        return $dataTable->render('backend.vendor-type.index');
    }

    public function store(VendorTypeStoreRequest $request)
    {
        try {
            $vendorType = new VendorType;
            $vendorType->user_id = Auth::id();
            $vendorType->name = $request->name;
            $vendorType->save();

            return response()->json([
                'status'  => true,
                'message' => 'Vendor type store successfully',
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
            $vendorType = VendorType::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $vendorType,
                'message' => 'Vendor type fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(VendorTypeUpdateRequest $request, VendorType $vendorType)
    {
        try {
            $vendorType->user_id = Auth::id();
            $vendorType->name = $request->name;
            $vendorType->save();

            return response()->json([
                'status'  => true,
                'message' => 'Vendor type update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function details(VendorType $vendorType)
    {
        $vendorDocTypes = VendorDocType::all();
        $preSelectedVendorDocTypes = VendorDocDetail::where('vendor_type_id', $vendorType->id)->get()->pluck('vendor_doc_type_id')->toArray();
        return view('backend.vendor-doc-detail.index', compact('vendorType', 'vendorDocTypes', 'preSelectedVendorDocTypes'));
    }

    public function delete(VendorType $vendorType)
    {
        try {
            if ($vendorType->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Vendor type deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Vendor type not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function vendorDocStore(VendorDocDetailStoreRequest $request, VendorType $vendorType)
    {
        try {
            VendorDocDetail::where('vendor_type_id', $vendorType->id)->delete();
            if ($request->has('vendor_doc_type') && is_array($request->vendor_doc_type) && count($request->vendor_doc_type) > 0) {
                foreach ($request->vendor_doc_type as $vendorDocType) {
                    $vendorDocDetail = new VendorDocDetail;
                    $vendorDocDetail->user_id = Auth::id();
                    $vendorDocDetail->vendor_type_id = $vendorType->id;
                    $vendorDocDetail->vendor_doc_type_id = $vendorDocType;
                    $vendorDocDetail->save();
                }
            }
            return redirect()->route('vendor-types.index')->with(['success' => 'Vendor doc detail updated successfully']);
        } catch (\Exception $e) {
            return redirect()->route('vendor-types.index')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
