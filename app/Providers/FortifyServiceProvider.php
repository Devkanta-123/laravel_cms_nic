<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Mews\Captcha\Facades\Captcha;
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     Fortify::loginView(function(){
    //         return view('auth.login');
    //     });
    //     Fortify::createUsersUsing(CreateNewUser::class);
    //     Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    //     Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    //     Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    //     RateLimiter::for('login', function (Request $request) {
    //         $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

    //         return Limit::perMinute(5)->by($throttleKey);
    //     });

    //     RateLimiter::for('two-factor', function (Request $request) {
    //         return Limit::perMinute(5)->by($request->session()->get('login.id'));
    //     });
    // }
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // ✅ Login Audit Logging with proper login return
        Fortify::authenticateUsing(function (Request $request) {
            static $alreadyLogged = false;

            $user = User::where('email', $request->email)->first();
            $ip = $request->ip();
            $agent = $request->userAgent();

            if ($user && Hash::check($request->password, $user->password)) {
                if (!$alreadyLogged) {
                    AuditLog::create([
                        'user_id' => $user->id,
                        'action' => 'login',
                        'status' => 'success',
                        'ip_address' => $ip,
                        'user_agent' => $agent,
                    ]);
                    $alreadyLogged = true;
                }
                return $user; // ✅ Must return to let Fortify continue
            }

            if (!$alreadyLogged) {
                AuditLog::create([
                    'user_id' => optional($user)->id,
                    'action' => 'login',
                    'status' => 'failed',
                    'ip_address' => $ip,
                    'user_agent' => $agent,
                ]);
                $alreadyLogged = true;
            }

            return null;
        });
    }


    // public function boot(): void
    // {
    //     Fortify::loginView(function () {
    //         return view('auth.login');
    //     });

    //     Fortify::createUsersUsing(CreateNewUser::class);
    //     Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    //     Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    //     Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    //     // ✅ CAPTCHA Verification + Login
    //     Fortify::authenticateUsing(function (Request $request) {
    //         // ✅ Verify CAPTCHA
    //         if (!Captcha::check_api($request->captcha, $request->captcha_key)) {
    //             throw ValidationException::withMessages([
    //                 'captcha' => ['The CAPTCHA is incorrect. Please try again.'],
    //             ]);
    //         }

    //         // ✅ Validate user
    //         $user = User::where('email', $request->email)->first();

    //         if ($user && Hash::check($request->password, $user->password)) {
    //             return $user;
    //         }

    //         return null;
    //     });

    //     // ✅ Rate limit login attempts
    //     RateLimiter::for('login', function (Request $request) {
    //         $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
    //         return Limit::perMinute(5)->by($throttleKey);
    //     });

    //     // ✅ Rate limit 2FA
    //     RateLimiter::for('two-factor', function (Request $request) {
    //         return Limit::perMinute(5)->by($request->session()->get('login.id'));
    //     });
    // }
}
