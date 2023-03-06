<?php
 
namespace App\Http\Controllers;

use App\Models\Application;
use App\Http\Requests\UpdateApplicationRequest;
 
class ApplicationController extends Controller
{
    /**
     * Show the application for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $data['application'] = Application::getOne();

        return view('pages.application.edit', $data);
    }

    public function update(UpdateApplicationRequest $request)
    {
        $data = Application::getOne();

        if (empty($data)) {
            abort(404);
        }

        $input = $request->safe()->only(['name', 'copyright']);

        Application::UpdateById($input, $data->id);

        return redirect('application')
                ->with('status', 'Berhasil mengubah pengaturan aplikasi.');
    }
}