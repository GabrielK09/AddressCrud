<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Messages\Auth\Request\AuthDefaultMessages;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::controller(AuthController::class)->group(function() {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout');

            Route::post('/forgot-password', function(Request $r) {
                $r->validate([
                    'email' => 'required|email'
                ], [
                    'email.required' => AuthDefaultMessages::EMAIL_REQUIRED->value,
                    'email.email' => AuthDefaultMessages::EMAIL_STRING_FORMAT->value,
                ]);

                $status = Password::sendResetLink(
                    $r->only('email')
                );

                return $status === Password::ResetLinkSent
                                    ? response()->json([
                                        'success' => true,
                                        'message' => __($status)

                                    ], 200)

                                    : response()->json([
                                        'success' => false,
                                        'message' => 'Erro na solicitação: forgot-password',
                                        'error' => [__($status)]
                                    ], 400);

            })->name('password.email');;

            Route::get('/reset-password/{token}', function(string $token) {
                return response()->json([
                    'success' => true,
                    'message' => 'Token para reset da senha',
                    'token' => $token
                ]);
            })->name('password.reset');

            Route::post('/reset-password', function(Request $r) {
                $r->validate([
                    'token' => ['required'],
                    'email' => ['required', 'email'],
                    'password' => ['required', 'min:8'],
                ], [
                    'token.required' => 'O token para redefinição da senha é obrigatório',

                    'email.required' => AuthDefaultMessages::EMAIL_REQUIRED->value,
                    'email.email' => AuthDefaultMessages::EMAIL_STRING_FORMAT->value,
                    
                    'password.required' => AuthDefaultMessages::PASSWORD_REQUIRED->value,
                    'password.min' => AuthDefaultMessages::PASSWORD_MIN->value,              
                ]);

                $status = Password::reset(
                    $r->only('email', 'password', 'token'),
                    function(User $user, string $password)
                    {
                        $user->forceFill([
                            'password' => Hash::make($password)

                        ])->setRememberToken(Str::random(60));

                        $user->save();

                        event(new PasswordReset($user));
                    }
                );

                return $status === Password::PasswordReset
                                    ? response()->json([
                                        'success' => true,
                                        'message' => __($status)
                                    ], 200)

                                    : response()->json([
                                        'success' => false,
                                        'message' => 'Erro na solicitação: /reset-password',
                                        'error' => [__($status)]
                                    ], 400);
            })->name('password.update');

            Route::controller(UserController::class)->group(function() {
                Route::put('/update/user-data', 'update');
                Route::delete('/cancel-account', 'destroy');

            });
        });
    });

    Route::middleware('auth:sanctum')->group(function() { 
        require_once __DIR__.'/address/addressApi.php';
        
    });
});