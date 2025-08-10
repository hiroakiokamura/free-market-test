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
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\CreatesNewUsers::class,
            CreateNewUser::class
        );

        // 登録後のリダイレクト先を設定
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/mypage/profile');
            }
        });

        // ログイン後のリダイレクト先を設定
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect()->route('home');
            }
        });

        // ログインビューのレスポンスを設定
        $this->app->instance(LoginViewResponse::class, new class implements LoginViewResponse {
            public function toResponse($request)
            {
                return view('auth.login');
            }
        });

        // ログインバリデーションをカスタマイズ
        Fortify::authenticateUsing(function (Request $request) {
            // バリデーションを実行
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスを正しい形式で入力してください',
                'password.required' => 'パスワードを入力してください',
            ]);
            
            $user = \App\Models\User::where('email', $request->email)->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
            
            // 認証失敗時に独自のメッセージを設定
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'パスワードが一致しません',
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // 登録ビューの設定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログインビューの設定
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // メール認証ビューの設定
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // レート制限の設定
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
