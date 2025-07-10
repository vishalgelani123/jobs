<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GeneralTermConditionDataTable;
use App\DataTables\GeneralTermConditionDescriptionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionStoreRequest;
use App\Http\Requests\GeneralTermCondition\GeneralTermConditionUpdateRequest;
use App\Models\GeneralTermCondition;
use App\Models\GeneralTermConditionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralTermConditionController extends Controller
{
    public function create(GeneralTermConditionCategory $generalTermConditionCategory)
    {
        return view('backend.general-term-condition.create', compact('generalTermConditionCategory'));
    }

    public function store(GeneralTermConditionStoreRequest $request, GeneralTermConditionCategory $generalTermConditionCategory)
    {
        try {
            $generalTermCondition = new GeneralTermCondition;
            $generalTermCondition->user_id = Auth::id();
            $generalTermCondition->category_id = $generalTermConditionCategory->id;
            $generalTermCondition->title = $request->title;
            $generalTermCondition->description = $request->description;
            $generalTermCondition->save();
            return redirect()->route('general-term-condition-categories.details', $generalTermConditionCategory)->with(['success' => 'General term condition store successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit(GeneralTermConditionCategory $generalTermConditionCategory, GeneralTermCondition $generalTermCondition)
    {
        return view('backend.general-term-condition.edit', compact('generalTermConditionCategory', 'generalTermCondition'));
    }

    public function update(GeneralTermConditionUpdateRequest $request, GeneralTermConditionCategory $generalTermConditionCategory, GeneralTermCondition $generalTermCondition)
    {
        try {
            $generalTermCondition->user_id = Auth::id();
            $generalTermCondition->category_id = $generalTermConditionCategory->id;
            $generalTermCondition->title = $request->title;
            $generalTermCondition->description = $request->description;
            $generalTermCondition->save();
            return redirect()->route('general-term-condition-categories.details', $generalTermConditionCategory)->with(['success' => 'General term condition update successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function delete(GeneralTermConditionCategory $generalTermConditionCategory, GeneralTermCondition $generalTermCondition)
    {
        try {
            if ($generalTermCondition->delete()) {
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

    public function categoryDescription(GeneralTermConditionCategory $generalTermConditionCategory, GeneralTermCondition $generalTermCondition)
    {
        return view('backend.general-term-condition.description', compact('generalTermConditionCategory','generalTermCondition'));
    }
}
