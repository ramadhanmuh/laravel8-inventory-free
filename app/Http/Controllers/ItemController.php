<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Category;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;

class ItemController extends Controller
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

        $itemTotal = Item::countData($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        $data['items'] = Item::getData($data['input']);

        return view('pages.item.index', $data);
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
        $data = [
            'application' => Application::first(),
            'unit_of_measurements' => UnitOfMeasurement::get(),
            'categories' => Category::get(),
            'brands' => Brand::get()
        ];

        return view('pages.item.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();

        Item::insertOrIgnore($input);

        return redirect('items')
                ->with('status', 'Berhasil menambah barang.');
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
            'item' => Item::findOrFail($id), 
            'application' => Application::first()
        ];

        return view('pages.item.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'item' => Item::findOrFail($id), 
            'application' => Application::first(),
            'unit_of_measurements' => UnitOfMeasurement::get(),
            'categories' => Category::get(),
            'brands' => Brand::get()
        ];

        return view('pages.item.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = Item::findOrFail($id);

        $input = $request->validated();

        Item::where('id', $id)->update($input);

        return redirect('items')
                ->with('status', 'Berhasil mengubah barang.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Item::findOrFail($id);

        $data->delete();

        return back()->with('status', 'Berhasil menghapus barang.');
    }
}
