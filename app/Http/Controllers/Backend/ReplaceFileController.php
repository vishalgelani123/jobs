<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReplaceFileController extends Controller
{

    public function index()
    {
        return view('backend.replace-file.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'destination_path' => 'required',
            'file'             => 'required|file',
            'destination_type' => 'required|in:app,resources,base',
        ]);
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if ($request->destination_type == 'app') {
                    $destinationPath = app_path($request->destination_path);
                }
                if ($request->destination_type == 'resources') {
                    $destinationPath = resource_path($request->destination_path);
                }
                if ($request->destination_type == 'base') {
                    $destinationPath = base_path($request->destination_path);
                }
                $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);
            }
            return redirect()->back()->with(['success' => 'File replaced successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
