<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PreVendorSubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreVendorSubCategory\PreVendorSubCategoryImportRequest;
use App\Http\Requests\PreVendorSubCategory\PreVendorSubCategoryStoreRequest;
use App\Http\Requests\PreVendorSubCategory\PreVendorSubCategoryUpdateRequest;
use App\Imports\PreVendorSubCategoryImport;
use App\Models\PreVendorCategory;
use App\Models\PreVendorSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PreVendorSubCategoryController extends Controller
{
    public function index(PreVendorSubCategoryDataTable $dataTable)
    {
        $preVendorCategories = PreVendorCategory::all();
        return $dataTable->render('backend.pre-vendor-sub-category.index',compact('preVendorCategories'));
    }

    public function store(PreVendorSubCategoryStoreRequest $request)
    {
        try {
            $preVendorSubCategory = new PreVendorSubCategory;
            $preVendorSubCategory->user_id = Auth::id();
            $preVendorSubCategory->pre_vendor_category_id = $request->pre_vendor_category;
            $preVendorSubCategory->name = $request->name;
            $preVendorSubCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor sub category store successfully',
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
            $preVendorSubCategory = PreVendorSubCategory::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $preVendorSubCategory,
                'message' => 'Pre vendor sub  category fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(PreVendorSubCategoryUpdateRequest $request, PreVendorSubCategory $preVendorSubCategory)
    {
        try {
            $preVendorSubCategory->user_id = Auth::id();
            $preVendorSubCategory->pre_vendor_category_id = $request->pre_vendor_category;
            $preVendorSubCategory->name = $request->name;
            $preVendorSubCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor sub category update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(PreVendorSubCategory $preVendorSubCategory)
    {
        try {
            if ($preVendorSubCategory->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Pre vendor sub category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Pre vendor sub category not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function import(PreVendorSubCategoryImportRequest $request)
    {
        try {
            Excel::import(new PreVendorSubCategoryImport($request->pre_vendor_category), $request->file('file'));
            return response()->json([
                'status'  => true,
                'message' => 'Pre vendor sub category import successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
