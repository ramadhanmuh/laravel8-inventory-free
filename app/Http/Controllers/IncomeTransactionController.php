<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\IncomeTransaction;
use App\Models\IncomeTransactionItem;
use App\Models\Item;
use Illuminate\Support\Str;
use App\Http\Requests\StoreIncomeTransactionRequest;
use App\Http\Requests\UpdateIncomeTransactionRequest;

class IncomeTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Item::whereRelation(
            'incomeTransactionItems', 'income_transaction_id', '!=', 2
        )->get();

        $data['application'] = Application::first();

        $data['input'] = $this->getInputParameter($request);

        $data['input']['page'] = $data['input']['page'] < 1 ? 1 : $data['input']['page'];

        $data['input']['offset'] = $data['input']['page'] > 1 ? ($data['input']['page'] * 10) - 10 : 0;

        $data['number'] = $data['input']['offset'] + 1;

        $itemTotal = IncomeTransaction::countData($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        $data['items'] = IncomeTransaction::getData($data['input']);

        $request->session()->forget([
            'create-income-transaction-item', 'edit-income-transaction-item'
        ]);

        return view('pages.income-transaction.index', $data);
    }

    private function getInputParameter($request)
    {
        return [
            'page'=> intval($request->page),
            'order_by'=> strval($request->order_by),
            'order_direction'=> strval($request->order_direction),
            'keyword'=> strval($request->keyword),
            'start_date' => intval($request->start_date),
            'end_date' => intval($request->end_date),
            'string_start_date' => strval($request->string_start_date),
            'string_end_date' => strval($request->string_end_date)
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
            'items' => Item::orderBy('description')->get(),
            'income_transaction_items' => IncomeTransactionItem::getWithSession(
                session('create-income-transaction-item')
            )
        ];

        return view('pages.income-transaction.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncomeTransactionRequest $request)
    {
        $input = $request->validated();

        $incomeTransaction = IncomeTransaction::create($input);

        $incomeTransaction->incomeTransactionItems()->createMany(
            $request->session()->get('create-income-transaction-item')
        );

        return redirect('income-transactions')
                ->with('status', 'Berhasil menambah transaksi (masuk).');
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
            'item' => IncomeTransaction::with('incomeTransactionItems.item.unitOfMeasurement')->findOrFail($id), 
            'application' => Application::first()
        ];

        $data['subitems'] = $data['item']->incomeTransactionItems->sortBy('item.description');

        return view('pages.income-transaction.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $session = session('edit-income-transaction-item');

        $findAllData = false;

        if (!empty($session)) {
            if ($session['id'] != $id) {
                $request->session()->forget('edit-income-transaction-item');
                $findAllData = true;
            } else {
                $findAllData = false;
            }
        } else {
            $findAllData = true;
        }

        if ($findAllData) {
            $data['item'] = IncomeTransaction::with(
                'incomeTransactionItems.item.unitOfMeasurement'
            )
            ->findOrFail($id);
        } else {
            $data['item'] = IncomeTransaction::findOrFail($id);
        }

        $data['application'] = Application::first();

        $data['items'] = Item::orderBy('description')->get();

        return view('pages.income-transaction.edit', $data);
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
        $data = IncomeTransaction::findOrFail($id);

        $input['income_transaction'] = $request->validated();

        $session = $request->session()->get('edit-income-transaction-item');

        IncomeTransaction::where('id', $id)->update($input['income_transaction']);

        if (!empty($session)) {
            $input['income_transaction_items'] = [];

            foreach ($session['incomeTransactionItems'] as $key => $value) {
                array_push($input['income_transaction_items'], new IncomeTransactionItem([
                    'item_id' => $value['item_id'],
                    'amount' => $value['amount']
                ]));
            }

            IncomeTransactionItem::where('income_transaction_id', $id)->delete();

            $data->incomeTransactionItems()->saveMany($input['income_transaction_items']);
        }

        return redirect('income-transactions')
                ->with('status', 'Berhasil mengubah transaksi (masuk).');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = IncomeTransaction::findOrFail($id);

        $data->delete();

        return back()->with('status', 'Berhasil menghapus transaksi (masuk).');
    }
}
