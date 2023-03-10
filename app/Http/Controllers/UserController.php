<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['application'] = Application::first();

        $data['input'] = $this->getInputParameter($request);

        $data['input']['page'] = $data['input']['page'] < 1 ? 1 : $data['input']['page'];

        $data['input']['offset'] = $data['input']['page'] > 1 ? ($data['input']['page'] * 10) - 10 : 0;

        $data['number'] = $data['input']['offset'] + 1;

        $itemTotal = User::countData($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        $data['items'] = User::getData($data['input']);

        return view('pages.user.index', $data);
    }

    private function getInputParameter($request)
    {
        return [
            'page'=> intval($request->page),
            'order_by'=> $request->order_by,
            'order_direction'=> $request->order_direction,
            'keyword'=> $request->keyword,
            'role'=> $request->role,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['application'] = Application::first();

        return view('pages.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $input = $request->validated();

        $input['password'] = Hash::make($input['password']);

        $input['created_at'] = time();

        User::create($input);

        return redirect('users')
                ->with('status', 'Berhasil menambah pengguna.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'item' => User::findOrFail($id),
            'application' => Application::first(),
        ];

        return view('pages.user.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['application'] = Application::first();

        $data['item'] = User::findOrFail($id);

        return view('pages.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $data = User::findOrFail($id);

        $input = $request->validated();

        $input['updated_at'] = time();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($input);

        return redirect('users')
                ->with('status', 'Berhasil mengubah pengguna.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::findOrFail($id);

        $data->delete();

        return back()->with('status', 'Berhasil menghapus pengguna.');
    }
}
