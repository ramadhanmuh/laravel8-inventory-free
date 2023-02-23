<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
 
class ProfileController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $data['application'] = Application::getOne();

        $data['input'] = $request->all();

        return view('pages.profile.edit', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'required',
        ]);

        return back();
    }
}