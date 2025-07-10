<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {

        if (Auth::user()->isEmployer()) {
            $applications = Application::whereHas('job', function($query) {
                $query->where('user_id', Auth::id());
            })->get();
        } else {
            $applications = Application::where('user_id', Auth::id())->get();
        }

        return response()->json($applications);
    }

    public function store(Request $request)
    {
        if (Auth::user()->isEmployer()) {
            return response()->json(['message' => 'Employers cannot apply for jobs'], 403);
        }

        $request->validate([
            'cover_letter' => 'required|string',
            'job_id' => 'required|integer',
        ]);

        $application = Application::create([
            'user_id' => Auth::id(),
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending'
        ]);

        return response()->json($application, 201);
    }

    public function show(Application $application)
    {
        if ($application->user_id !== Auth::id() && $application->job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($application);
    }

    public function update(Request $request, Application $application)
    {
        if ($application->job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Only job poster can update status'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $application->update(['status' => $request->status]);

        return response()->json($application);
    }
}