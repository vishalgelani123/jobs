<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GenerateStringNumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('backend.profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = Auth::User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;

            if ($request->has('password') && $request->password != "") {
                $user->password = Hash::make($request->password);
            }

            if ($request->file('user_profile')) {
                $file = $request->file('user_profile');
                $fileName = GenerateStringNumberHelper::generateTimeRandomString() . '_' . $file->getClientOriginalName();
                $file->move(public_path('user_profile'), $fileName);
                $user->user_profile = $fileName;
            }

            $user->save();

            return back()->with(['success' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }
}
