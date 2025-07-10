<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PreVendorCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreVendorCategory\PreVendorCategoryImportRequest;
use App\Http\Requests\PreVendorCategory\PreVendorCategoryStoreRequest;
use App\Http\Requests\PreVendorCategory\PreVendorCategoryUpdateRequest;
use App\Imports\PreVendorCategoryImport;
use App\Models\PreVendorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PreVendorCategoryController extends Controller
{
    public function index(PreVendorCategoryDataTable $dataTable)
    {
        return $dataTable->render('backend.pre-vendor-category.index');
    }

    public function store(PreVendorCategoryStoreRequest $request)
    {
        try {
            $preVendorCategory = new PreVendorCategory;
            $preVendorCategory->user_id = Auth::id();
            $preVendorCategory->name = $request->name;
            $preVendorCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor category store successfully',
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
            $preVendorCategory = PreVendorCategory::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $preVendorCategory,
                'message' => 'Pre vendor category fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(PreVendorCategoryUpdateRequest $request, PreVendorCategory $preVendorCategory)
    {
        try {
            $preVendorCategory->user_id = Auth::id();
            $preVendorCategory->name = $request->name;
            $preVendorCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor category update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(PreVendorCategory $preVendorCategory)
    {
        try {
            if ($preVendorCategory->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Pre vendor category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Pre vendor category not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function import(PreVendorCategoryImportRequest $request)
    {
        try {
            Excel::import(new PreVendorCategoryImport(), $request->file('file'));
            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor category import successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
