<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class APIResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Show the password reset view.
     *
     * @param  Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Illuminate\Contracts\View\View
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return view('auth.passwords.reset_success');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string|null
     */
    protected function redirectTo()
    {
        // Return null to prevent redirect
        return null;
    }
}
