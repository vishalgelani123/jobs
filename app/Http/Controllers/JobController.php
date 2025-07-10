<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return response()->json(Job::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric'
        ]);

        if (!Auth::user()->isEmployer()) {
            return response()->json(['message' => 'Only employers can post jobs'], 403);
        }

        $job = Job::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'salary' => $request->salary
        ]);

        return response()->json($job, 201);
    }

    public function show(Job $job)
    {
        return response()->json($job);
    }

    public function update(Request $request, Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'company' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'salary' => 'nullable|numeric'
        ]);

        $job->update($request->all());

        return response()->json($job);
    }

    public function destroy(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully']);
    }
}