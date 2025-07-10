<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CountryStateCityController extends Controller
{

    public function states(Request $request)
    {
        try {
            $states = State::where('country_id', $request->country_id)->get();
            return response()->json([
                'status'  => true,
                'data'    => $states,
                'message' => 'States retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => true,
                'data'    => [],
                'message' => $e->getMessage()
            ]);
        }
    }
    public function cities(Request $request)
    {
        try {
            $cities = City::where('state_id', $request->state_id)->get();
            return response()->json([
                'status'  => true,
                'data'    => $cities,
                'message' => 'Cities retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => true,
                'data'    => [],
                'message' => $e->getMessage()
            ]);
        }
    }
}
