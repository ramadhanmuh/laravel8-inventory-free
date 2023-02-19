<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
 
class LoginController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $data['application'] = Application::getOne();

        return view('auth.login', $data);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // return back()->withErrors([
        //     'username' => 'Test'
        // ]);
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
 
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
 
            // return redirect()->intended('dashboard');
            return 'test';
        }
 
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ]);
        return back();
    }
}