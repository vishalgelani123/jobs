<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TermsConditionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermsCondition\TermsConditionStoreRequest;
use App\Http\Requests\TermsCondition\TermsConditionUpdateRequest;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsConditionController extends Controller
{
    public function index(TermsConditionDataTable $dataTable)
    {
        return $dataTable->render('backend.terms-condition.index');
    }

    public function store(TermsConditionStoreRequest $request)
    {
        try {
            $termsCondition = new TermsCondition;
            $termsCondition->user_id = Auth::id();
            $termsCondition->title = $request->title;
            $termsCondition->description = $request->description;
            $termsCondition->save();

            return response()->json([
                'status'  => true,
                'message' => 'Terms Condition store successfully',
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
            $termsCondition = TermsCondition::find($request->id);

            return response()->json([
                'status'  => true,
                'data'    => $termsCondition,
                'message' => 'Terms Condition fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(TermsConditionUpdateRequest $request, TermsCondition $termsCondition)
    {
        try {
            $termsCondition->title = $request->title;
            $termsCondition->description = $request->description;
            $termsCondition->save();

            return response()->json([
                'status'  => true,
                'message' => 'Terms Condition update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(TermsCondition $termsCondition)
    {
        try {
            if ($termsCondition->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Terms Condition deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Terms Condition not found!"
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
