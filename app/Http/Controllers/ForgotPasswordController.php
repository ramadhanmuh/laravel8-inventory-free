<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Application;
 
class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $data['application'] = Application::getOne();

        return view('pages.auth.forgot-password', $data);
    }

    /**
     * Handle an forgot password attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => 'Berhasil mengirim email. Silahkan buka email untuk menuju halaman ubah kata sandi.'])
                    : back()->withErrors(['email' => __($status)]);
    }
}