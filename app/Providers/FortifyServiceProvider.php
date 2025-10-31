<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

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
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views with error handling.
     */
    private function configureViews(): void
    {
        Fortify::loginView(function (Request $request) {
            try {
                return Inertia::render('auth/login', [
                    'canResetPassword' => Features::enabled(Features::resetPasswords()),
                    'canRegister' => Features::enabled(Features::registration()),
                    'status' => $request->session()->get('status'),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Error en vista de login: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::resetPasswordView(function (Request $request) {
            try {
                return Inertia::render('auth/reset-password', [
                    'email' => $request->email,
                    'token' => $request->route('token'),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Error en reset password: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::requestPasswordResetLinkView(function (Request $request) {
            try {
                return Inertia::render('auth/forgot-password', [
                    'status' => $request->session()->get('status'),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Error en forgot password: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::verifyEmailView(function (Request $request) {
            try {
                return Inertia::render('auth/verify-email', [
                    'status' => $request->session()->get('status'),
                ]);
            } catch (\Throwable $e) {
                logger()->error('Error en verify email: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::registerView(function () {
            try {
                return Inertia::render('auth/register');
            } catch (\Throwable $e) {
                logger()->error('Error en register: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::twoFactorChallengeView(function () {
            try {
                return Inertia::render('auth/two-factor-challenge');
            } catch (\Throwable $e) {
                logger()->error('Error en 2FA challenge: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });

        Fortify::confirmPasswordView(function () {
            try {
                return Inertia::render('auth/confirm-password');
            } catch (\Throwable $e) {
                logger()->error('Error en confirm password: ' . $e->getMessage());
                return Inertia::render('error', ['status' => 500]);
            }
        });
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
