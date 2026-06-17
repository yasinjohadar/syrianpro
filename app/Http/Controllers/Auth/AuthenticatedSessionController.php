<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if ($request->filled('redirect')) {
            session(['url.intended' => $request->input('redirect')]);
        }

        return view('auth.login', [
            'demoAccounts' => config('demo-accounts.enabled') ? config('demo-accounts.accounts') : [],
            'demoPassword' => config('demo-accounts.password'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // التحقق من أن المستخدم نشط
        $user = Auth::user();
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'تم إلغاء تفعيل حسابك. يرجى التواصل مع الإدارة.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route(auth()->user()->dashboardRouteName(), absolute: false));
    }

    /**
     * One-click demo login for local development.
     */
    public function quickLogin(string $account): RedirectResponse
    {
        if (! config('demo-accounts.enabled')) {
            abort(404);
        }

        $accounts = config('demo-accounts.accounts', []);

        if (! isset($accounts[$account])) {
            abort(404);
        }

        $demo = $accounts[$account];
        $password = $demo['password'] ?? config('demo-accounts.password');

        if (! Auth::attempt(['email' => $demo['email'], 'password' => $password], true)) {
            return back()->withErrors([
                'email' => 'حساب التجربة غير متوفر. شغّل: php artisan db:seed --class=DemoUserSeeder',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'تم إلغاء تفعيل حسابك. يرجى التواصل مع الإدارة.',
            ]);
        }

        request()->session()->regenerate();

        return redirect()->intended(route($user->dashboardRouteName(), absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
