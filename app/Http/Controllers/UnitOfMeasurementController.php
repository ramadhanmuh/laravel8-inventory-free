<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;
use App\Http\Requests\StoreUnitOfMeasurementRequest;
use App\Http\Requests\UpdateUnitOfMeasurementRequest;

class UnitOfMeasurementController extends Controller
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

        $itemTotal = UnitOfMeasurement::countData($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        $data['items'] = UnitOfMeasurement::getData($data['input']);

        return view('pages.unit-of-measurement.index', $data);
    }

    private function getInputParameter($request)
    {
        return [
            'page'=> intval($request->page),
            'order_by'=> strval($request->order_by),
            'order_direction'=> strval($request->order_direction),
            'keyword'=> strval($request->keyword)
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

        $data['id'] = Str::uuid();

        return view('pages.unit-of-measurement.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUnitOfMeasurementRequest $request)
    {
        $input = $request->validated();

        UnitOfMeasurement::insertOrIgnore($input);

        return redirect('unit-of-measurements')
                ->with('status', 'Berhasil menambah satuan barang.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $data['item'] = UnitOfMeasurement::findOrFail($id);

        return view('pages.unit-of-measurement.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUnitOfMeasurementRequest $request, $id)
    {
        $data = UnitOfMeasurement::findOrFail($id);

        $input = $request->validated();

        UnitOfMeasurement::where('id', $id)->update($input);

        return redirect('unit-of-measurements')
                ->with('status', 'Berhasil mengubah satuan barang.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = UnitOfMeasurement::findOrFail($id);

        $data->delete();

        return back()->with('status', 'Berhasil menghapus satuan barang.');
    }
}
