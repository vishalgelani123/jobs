<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GeneralTermConditionCategoryDataTable;
use App\DataTables\GeneralTermConditionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionImportRequest;
use App\Http\Requests\GeneralTermConditionCategory\GeneralTermConditionCategoryStoreRequest;
use App\Http\Requests\GeneralTermConditionCategory\GeneralTermConditionCategoryUpdateRequest;
use App\Imports\GeneralTermConditionImport;
use App\Models\GeneralTermConditionCategory;
use App\Models\TermCondition;
use App\Models\TermConditionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GeneralTermConditionCategoryController extends Controller
{
    public function index(GeneralTermConditionCategoryDataTable $dataTable)
    {
        $termConditionCategories = GeneralTermConditionCategory::all();
        return $dataTable->render('backend.general-term-condition-category.index', compact('termConditionCategories'));
    }

    public function store(GeneralTermConditionCategoryStoreRequest $request)
    {
        try {
            $generalTermConditionCategory = new GeneralTermConditionCategory;
            $generalTermConditionCategory->user_id = Auth::id();
            $generalTermConditionCategory->name = $request->name;
            $generalTermConditionCategory->save();

            return response()->json([
                'status'  => true,
                'message' => 'General term condition category store successfully',
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
            $category = GeneralTermConditionCategory::find($request->id);
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

    public function update(GeneralTermConditionCategoryUpdateRequest $request, GeneralTermConditionCategory $generalTermConditionCategory)
    {
        try {
            $generalTermConditionCategory->user_id = Auth::id();
            $generalTermConditionCategory->name = $request->name;
            $generalTermConditionCategory->save();

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

    public function details(GeneralTermConditionDataTable $dataTable, GeneralTermConditionCategory $generalTermConditionCategory)
    {
        return $dataTable->render('backend.general-term-condition.index', compact('generalTermConditionCategory'));
    }

    public function delete(GeneralTermConditionCategory $generalTermConditionCategory)
    {
        try {
            if ($generalTermConditionCategory->delete()) {
                TermCondition::where('category_id', $generalTermConditionCategory->id)->delete();
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
