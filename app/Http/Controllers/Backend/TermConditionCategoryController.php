<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GeneralTermConditionCategoryDataTable;
use App\DataTables\GeneralTermConditionDataTable;
use App\DataTables\TermConditionCategoryDataTable;
use App\DataTables\TermConditionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionImportRequest;
use App\Http\Requests\GeneralTermConditionCategory\GeneralTermConditionCategoryStoreRequest;
use App\Http\Requests\GeneralTermConditionCategory\GeneralTermConditionCategoryUpdateRequest;
use App\Http\Requests\TermConditionCategory\TermConditionCategoryStoreRequest;
use App\Http\Requests\TermConditionCategory\TermConditionCategoryUpdateRequest;
use App\Imports\GeneralTermConditionImport;
use App\Models\GeneralTermConditionCategory;
use App\Models\TermCondition;
use App\Models\TermConditionCategory;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TermConditionCategoryController extends Controller
{
    public function index(TermConditionCategoryDataTable $dataTable)
    {
        $termConditionCategories = TermConditionCategory::all();
        $vendorTypes = VendorType::all();
        return $dataTable->render('backend.term-condition-category.index', compact('termConditionCategories', 'vendorTypes'));
    }

    public function store(TermConditionCategoryStoreRequest $request)
    {
        try {
            $termConditionCategory = new TermConditionCategory;
            $termConditionCategory->user_id = Auth::id();
            $termConditionCategory->vendor_type_id = $request->vendor_type;
            $termConditionCategory->name = $request->name;
            $termConditionCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Term condition category store successfully',
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
            $category = TermConditionCategory::find($request->id);
            return response()->json([
                'status'  => true,
                'data'    => $category,
                'message' => 'Category fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(TermConditionCategoryUpdateRequest $request, TermConditionCategory $termConditionCategory)
    {
        try {
            $termConditionCategory->user_id = Auth::id();
            $termConditionCategory->vendor_type_id = $request->vendor_type;
            $termConditionCategory->name = $request->name;
            $termConditionCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'Category update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function details(TermConditionDataTable $dataTable, TermConditionCategory $termConditionCategory)
    {
        return $dataTable->render('backend.term-condition.index', compact('termConditionCategory'));
    }

    public function delete(TermConditionCategory $termConditionCategory)
    {
        try {
            if ($termConditionCategory->delete()) {
                TermCondition::where('category_id', $termConditionCategory->id)->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Category not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function import(GeneralTermConditionImportRequest $request)
    {
        try {
            Excel::import(new GeneralTermConditionImport($request->term_condition_category), $request->file('file'));
            return response()->json([
                'status'  => true,
                'message' => 'Term condition import successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
