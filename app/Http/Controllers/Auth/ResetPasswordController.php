<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    protected function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'token'    => 'required'
        ];
    }

    protected function sendResetResponse($request, $response)
    {
        // Check if the request is valid
        if (!$request || !is_object($request)) {
            return redirect($this->redirectTo)->withErrors(['error' => 'Invalid request']);
        }

        // Log out the user if authenticated
        if (Auth::check()) {
            Auth::logout();
        }

        // Ensure $response is valid
        if (!$response || !is_string($response)) {
            return redirect($this->redirectTo)->withErrors(['error' => 'Invalid response from password reset']);
        }

        // Redirect to login page with a success message
        return redirect($this->redirectTo)->with('status', trans($response));
    }
}
