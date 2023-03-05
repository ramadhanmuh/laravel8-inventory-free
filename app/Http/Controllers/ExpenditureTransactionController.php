<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ExpenditureTransaction;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;
use App\Http\Requests\StoreExpenditureTransactionRequest;
use App\Http\Requests\UpdateExpenditureTransactionRequest;

class ExpenditureTransactionController extends Controller
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

        $itemTotal = ExpenditureTransaction::countData($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        $data['items'] = ExpenditureTransaction::getData($data['input']);

        $request->session()->forget([
            'create-expenditure-transaction-item', 'edit-expenditure-transaction-item'
        ]);

        return view('pages.expenditure-transaction.index', $data);
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
            'items' => Item::getAvailableItem(),
            'expenditure_transaction_items' => ExpenditureTransactionItem::getWithSession(
                session('create-expenditure-transaction-item')
            )
        ];

        return view('pages.expenditure-transaction.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExpenditureTransactionRequest $request)
    {
        $input = $request->validated();

        $expenditureTransaction = ExpenditureTransaction::create($input);

        $expenditureTransaction->expenditureTransactionItems()->createMany(
            $request->session()->get('create-expenditure-transaction-item')
        );

        return redirect('expenditure-transactions')
                ->with('status', 'Berhasil menambah transaksi (keluar).');
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
            'item' => ExpenditureTransaction::with(['expenditureTransactionItems.item.unitOfMeasurement'])
                                            ->findOrFail($id), 
            'application' => Application::first()
        ];

        $data['subitems'] = $data['item']->expenditureTransactionItems->sortBy('item.description');

        return view('pages.expenditure-transaction.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $session = session('edit-expenditure-transaction-item');

        $findAllData = false;

        if (!empty($session)) {
            if ($session['id'] != $id) {
                $request->session()->forget('edit-expenditure-transaction-item');
                $session = null;
                $findAllData = true;
            } else {
                $findAllData = false;
            }
        } else {
            $findAllData = true;
        }

        if ($findAllData) {
            $data['item'] = ExpenditureTransaction::with([
                'expenditureTransactionItems.item.unitOfMeasurement',
            ])
            ->findOrFail($id);
        } else {
            $data['item'] = ExpenditureTransaction::findOrFail($id);
        }

        $data['application'] = Application::first();

        if (empty($session)) {
            $data['items'] = Item::getAvailableItem();
        } else {
            $ids = array_column($session['deletedItems'], 'item_id');
            if (empty($ids)) {
                $data['items'] = Item::getAvailableItem();
            } else {
                $data['items'] = Item::getAvailableItemIncludeIds($ids);
            }
        }

        return view('pages.expenditure-transaction.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExpenditureTransactionRequest $request, $id)
    {
        $data = ExpenditureTransaction::findOrFail($id);

        $input['expenditure_transaction'] = $request->validated();

        $session = $request->session()->get('edit-expenditure-transaction-item');

        ExpenditureTransaction::where('id', $id)->update($input['expenditure_transaction']);

        if (!empty($session)) {
            $input['expenditure_transaction_items'] = [];

            foreach ($session['expenditureTransactionItems'] as $key => $value) {
                array_push($input['expenditure_transaction_items'], new ExpenditureTransactionItem([
                    'item_id' => $value['item_id'],
                    'amount' => $value['amount']
                ]));
            }

            ExpenditureTransactionItem::where('expenditure_transaction_id', $id)->delete();

            $data->expenditureTransactionItems()->saveMany($input['expenditure_transaction_items']);
        }

        return redirect('expenditure-transactions')
                ->with('status', 'Berhasil mengubah transaksi (keluar).');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ExpenditureTransaction::findOrFail($id);

        $data->delete();

        return back()->with('status', 'Berhasil menghapus transaksi (keluar).');
    }
}
