<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword\ResetPasswordStoreRequest;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    public function index()
    {
        return view('auth.reset-password');
    }

    public function resetPasswordStore(ResetPasswordStoreRequest $request)
    {
        try {
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->is_admin_password_reset = '1';
            $user->save();

            return redirect()->route('dashboard')->with(['success' => 'Password reset successfully']);
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
