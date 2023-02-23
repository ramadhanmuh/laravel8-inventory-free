<?php
 
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 
class ChangePasswordController extends Controller
{
    /**
     * Show the change password form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $data['application'] = Application::getOne();

        return view('pages.change-password.edit', $data);
    }

    public function update(ChangePasswordRequest $request)
    {
        $validated = $request->safe()->only(['newpassword']);

        $input['password'] = Hash::make($validated['newpassword']);

        User::where('id', Auth::user()->id)
            ->update($input);

        return redirect('change-password')
                ->with('status', '1');
    }
}