<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        $targetRoute = 'warga.dashboard';
        if ($user->role == 1) {
            $targetRoute = 'ketua.dashboard';
        } elseif ($user->role == 2) {
            $targetRoute = 'bendahara.dashboard';
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route($targetRoute, absolute: false))
                    : view('auth.verify-email');
    }
}
