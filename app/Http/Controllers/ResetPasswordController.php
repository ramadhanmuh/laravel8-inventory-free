<?php
 
namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Application;
 
class ResetPasswordController extends Controller
{
    /**
     * Show the forgot password form
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($token)
    {
        $data['application'] = Application::getOne();

        $data['token'] = $token;

        return view('pages.auth.reset-password', $data);
    }

    /**
     * Handle an forgot password attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect('/')->with('status', 'Berhasil mengubah kata sandi.')
                    : back()->withErrors(['email' => [__($status)]]);
    }
}