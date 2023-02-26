<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\IncomeTransactionItem;
use App\Models\Item;
use Illuminate\Support\Str;
use App\Http\Requests\StoreIncomeTransactionItemRequest;

class IncomeTransactionItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncomeTransactionItemRequest $request)
    {
        
        $session = $request->session()->get('create-income-transaction-item');
        
        if (empty($session)) {
            $input = [];

            array_push($input, [
                'item_id' => $request->item_id,
                'amount' => intval($request->amount)
            ]);

            $request->session()->put('create-income-transaction-item', $input);
        } else {
            $itemExists = 0;

            foreach ($session as $key => $value) {
                if ($value['item_id'] == $request->item_id) {
                    $session[$key]['amount'] += intval($request->amount);
                    $itemExists = 1;
                }
            }

            if (!$itemExists) {
                array_push($session, [
                    'item_id' => $request->item_id,
                    'amount' => intval($request->amount)
                ]);
            }

            $request->session()->put('create-income-transaction-item', $session);
        }

        return back()->with('status', 'Berhasil menambahkan barang.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIncomeTransactionRequest $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = IncomeTransactionItem::findOrFail($id);

        $file = $data->image;

        $data->delete();

        if ($file !== null) {
            if (File::exists(public_path($file))) {
                File::delete(public_path($file));
            }
        }

        return back()->with('status', 'Berhasil menghapus barang.');
    }
}