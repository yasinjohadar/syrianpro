<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $accountType = $request->input('account_type', 'talent');

        $rules = [
            'account_type' => ['required', 'in:talent,company'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($accountType === 'talent') {
            $rules['title'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['sector'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules, [
            'account_type.required' => 'اختر نوع الحساب: تقني أو شركة',
            'account_type.in' => 'نوع الحساب غير صالح',
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'هذا البريد مستخدم مسبقاً',
        ]);

        $user = DB::transaction(function () use ($validated, $accountType) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ]);

            $roleName = $accountType === 'company' ? 'company' : 'talent';
            $user->assignRole(Role::firstOrCreate(['name' => $roleName]));

            if ($accountType === 'company') {
                Company::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'sector' => $validated['sector'] ?? 'تقنية',
                    'location' => 'عن بُعد',
                    'is_active' => true,
                    'order' => (Company::max('order') ?? 0) + 1,
                ]);
            } else {
                Talent::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'title' => $validated['title'] ?? 'تقني',
                    'city' => 'دمشق',
                    'is_active' => true,
                    'order' => (Talent::max('order') ?? 0) + 1,
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route($user->dashboardRouteName(), absolute: false));
    }
}
