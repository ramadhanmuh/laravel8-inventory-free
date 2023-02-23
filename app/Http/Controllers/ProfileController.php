<?php
 
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
 
class ProfileController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $data['application'] = Application::getOne();

        return view('pages.profile.edit', $data);
    }

    public function update(UpdateProfileRequest $request)
    {
        $input = $request->safe()->only(['name', 'email', 'username']);

        User::where('id', Auth::user()->id)
            ->update($input);

        return redirect('profile')
                ->with('status', '1');
    }
}