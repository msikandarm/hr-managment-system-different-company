<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                return redirect('/admin/login');
            }
        });
    }

    public function boot(): void
    {
        $this->loadAuthViews();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $username = (string) $request->username;

            return Limit::perMinute(5)->by($username.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = $this->checkUser($request);

            if ($user && Hash::check($request->password, $user->password)) {
                $user->last_login_at = now();
                $user->save();

                return $user;
            }
        });
    }

    protected function checkUser(Request $request)
    {
        return User::whereStatus(true)->where(function (Builder $query) use ($request) {
            $query->whereUsername($request->username)->orWhere('email', $request->username);
        })->first();
    }

    protected function loadAuthViews()
    {
        Fortify::loginView(function () {
            return view('admin.auth.login');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('admin.auth.forgot-password');
        });

        Fortify::resetPasswordView(function () {
            return view('admin.auth.reset-password');
        });

        Fortify::confirmPasswordView(function () {
            return view('admin.auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('admin.auth.two-factor-challenge', [
                'page_title' => 'Two Factor Authentication',
            ]);
        });
    }
}
