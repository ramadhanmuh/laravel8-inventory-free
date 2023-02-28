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
            'item' => IncomeTransaction::with('incomeTransactionItems')->findOrFail($id), 
            'application' => Application::first()
        ];

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
        $data = [
            'item' => IncomeTransaction::with([
                'incomeTransactionItems.item.unitOfMeasurement'
            ])
            ->findOrFail($id),
            'application' => Application::first(),
            'items' => Item::orderBy('description')->get()
        ];

        $session = session('edit-income-transaction-item');

        if (!empty($session)) {
            
        }

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

        $input = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = str_shuffle(time() . Str::random(30))
                        . '.' . $request->image->extension();

            $storagePath = 'uploads/images';

            $input['image'] = $storagePath . '/' . $fileName;

            $request->file('image')->move(public_path($storagePath), $fileName);

            if ($data->image !== null) {
                if (File::exists(public_path($data->image))) {
                    File::delete(public_path($data->image));
                }
            }
        }

        IncomeTransaction::where('id', $id)->update($input);

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

        $file = $data->image;

        $data->delete();

        if ($file !== null) {
            if (File::exists(public_path($file))) {
                File::delete(public_path($file));
            }
        }

        return back()->with('status', 'Berhasil menghapus transaksi (masuk).');
    }

    public function openImage($id)
    {
        $data = IncomeTransaction::findOrFail($id);

        return response()->file(public_path($data->image));
    }
}
