<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorDocTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorDocType\VendorDocTypeStoreRequest;
use App\Http\Requests\VendorDocType\VendorDocTypeUpdateRequest;
use App\Models\VendorDocSubType;
use App\Models\VendorDocType;
use App\Models\VendorDocTypeExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDocTypeController extends Controller
{
    public function index(VendorDocTypeDataTable $dataTable)
    {
        $vendorDocTypeExtensions = VendorDocTypeExtension::all();
        return $dataTable->render('backend.vendor-doc-type.index', compact('vendorDocTypeExtensions'));
    }

    public function store(VendorDocTypeStoreRequest $request)
    {
        try {
            $vendorDocType = new VendorDocType;
            $vendorDocType->user_id = Auth::id();
            $vendorDocType->name = $request->name;
            $vendorDocType->save();

            foreach ($request->doc_type as $docType) {
                $vendorSubDocType = new VendorDocSubType;
                $vendorSubDocType->user_id = Auth::id();
                $vendorSubDocType->vendor_doc_type_id = $vendorDocType->id;
                $vendorSubDocType->doc_type_extension_id = $docType;
                $vendorSubDocType->save();
            }

            return response()->json([
                'status'  => true,
                'message' => 'Vendor doc type store successfully',
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
            $vendorDocType = VendorDocType::with('vendorDocSubTypes')->find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $vendorDocType,
                'message' => 'Vendor type fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(VendorDocTypeUpdateRequest $request, VendorDocType $vendorDocType)
    {
        try {
            $vendorDocType->user_id = Auth::id();
            $vendorDocType->name = $request->name;
            $vendorDocType->save();

            VendorDocSubType::where('vendor_doc_type_id', $vendorDocType->id)->delete();

            foreach ($request->doc_type as $docType) {
                $vendorSubDocType = new VendorDocSubType;
                $vendorSubDocType->user_id = Auth::id();
                $vendorSubDocType->vendor_doc_type_id = $vendorDocType->id;
                $vendorSubDocType->doc_type_extension_id = $docType;
                $vendorSubDocType->save();
            }

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

    public function delete(VendorDocType $vendorDocType)
    {
        try {
            if ($vendorDocType->delete()) {
                VendorDocSubType::where('vendor_doc_type_id', $vendorDocType->id)->delete();
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
}
