<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GeneralTermConditionCategoryDataTable;
use App\DataTables\TermConditionCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionStoreRequest;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionUpdateRequest;
use App\Http\Requests\TermCondition\TermConditionStoreRequest;
use App\Http\Requests\TermCondition\TermConditionUpdateRequest;
use App\Models\TermCondition;
use App\Models\TermConditionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermConditionController extends Controller
{

    public function create(TermConditionCategory $termConditionCategory)
    {
        return view('backend.term-condition.create', compact('termConditionCategory'));
    }

    public function store(TermConditionStoreRequest $request,TermConditionCategory $termConditionCategory)
    {
        try {
            $termCondition = new TermCondition;
            $termCondition->user_id = Auth::id();
            $termCondition->category_id = $termConditionCategory->id;
            $termCondition->title = $request->title;
            $termCondition->description = $request->description;
            $termCondition->save();
            return redirect()->route('term-condition-categories.details', $termConditionCategory)->with(['success' => 'Term condition store successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit(TermConditionCategory $termConditionCategory, TermCondition $termCondition)
    {
        return view('backend.term-condition.edit', compact('termConditionCategory', 'termCondition'));
    }

    public function update(TermConditionUpdateRequest $request, TermConditionCategory $termConditionCategory, TermCondition $termCondition)
    {
        try {
            $termCondition->user_id = Auth::id();
            $termCondition->category_id = $termConditionCategory->id;
            $termCondition->title = $request->title;
            $termCondition->description = $request->description;
            $termCondition->save();
            return redirect()->route('term-condition-categories.details', $termConditionCategory)->with(['success' => 'Term condition update successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function delete(TermConditionCategory $termConditionCategory, TermCondition $termCondition)
    {
        try {
            if ($termCondition->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Term condition successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Term condition not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function categoryDescription(TermConditionCategory $termConditionCategory, TermCondition $termCondition)
    {
        return view('backend.term-condition.description', compact('termConditionCategory', 'termCondition'));
    }
}
