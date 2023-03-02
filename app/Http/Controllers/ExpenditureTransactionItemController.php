<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;
use App\Models\UnitOfMeasurement;
use App\Http\Requests\StoreExpenditureTransactionItemRequest;
use App\Http\Requests\UpdateExpenditureTransactionItemRequest;

class ExpenditureTransactionItemController extends Controller
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
    public function store(StoreExpenditureTransactionItemRequest $request)
    {
        $session = $request->session()->get('create-expenditure-transaction-item');
        
        if (empty($session)) {
            $input = [];

            array_push($input, [
                'item_id' => $request->item_id,
                'amount' => intval($request->amount)
            ]);

            $request->session()->put('create-expenditure-transaction-item', $input);
        } else {
            $itemExists = 0;

            foreach ($session as $key => $value) {
                if ($value['item_id'] == $request->item_id) {
                    $session[$key]['amount'] += intval($request->amount);
                    $itemExists = 1;
                    break;
                }
            }

            if (!$itemExists) {
                array_push($session, [
                    'item_id' => $request->item_id,
                    'amount' => intval($request->amount)
                ]);
            }

            $request->session()->put('create-expenditure-transaction-item', $session);
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
    public function update(UpdateExpenditureTransactionItemRequest $request, $id)
    {
        $session = $request->session()
                            ->get('edit-expenditure-transaction-item');

        $checkDeletedItem = 1;

        if (empty($session)) {
            $session = $this->createEditSession($id);
            $checkDeletedItem = 0;
        }

        $itemExists = 0;

        if ($checkDeletedItem) {
            $filtered = array_filter($session['deletedItems'], function ($value) {
                return $value['item_id'] == $request->item_id;
            });

            if (count($filtered)) {
                foreach ($filtered as $key => $value) {
                    $filtered[$key]['amount'] += intval($request->amount);
                    $session['expenditureTransactionItems'][] = $filtered[$key];
                }
            } else {
                foreach ($session['expenditureTransactionItems'] as $key => $value) {
                    if ($request->item_id == $value['item_id']) {
                        $session['expenditureTransactionItems'][$key]->amount += $request->amount;
                        $itemExists = 1;
                    }
                }
        
                if (!$itemExists) {
                    $item = Item::select('part_number', 'description', 'unit_of_measurement_id')
                                    ->find($request->item_id);
        
                    $unitOfMeasurement = UnitOfMeasurement::select('short_name')
                                                            ->find($item->unit_of_measurement_id);
        
                    $incomeTransactionItem = [
                        'item_id' => $request->item_id,
                        'amount' => $request->amount,
                        'item' => [
                            'part_number' => $item->part_number,
                            'description' => $item->description,
                            'unitOfMeasurement' => [
                                'short_name' => $unitOfMeasurement->short_name
                            ]
                        ]
                    ];
        
                    array_push($session['expenditureTransactionItems'], $incomeTransactionItem);
                }
            }
        } else {
            foreach ($session['expenditureTransactionItems'] as $key => $value) {
                if ($request->item_id == $value['item_id']) {
                    $session['expenditureTransactionItems'][$key]->amount += $request->amount;
                    $itemExists = 1;
                }
            }
    
            if (!$itemExists) {
                $item = Item::select('part_number', 'description', 'unit_of_measurement_id')
                                ->find($request->item_id);
    
                $unitOfMeasurement = UnitOfMeasurement::select('short_name')
                                                        ->find($item->unit_of_measurement_id);
    
                $incomeTransactionItem = [
                    'item_id' => $request->item_id,
                    'amount' => $request->amount,
                    'item' => [
                        'part_number' => $item->part_number,
                        'description' => $item->description,
                        'unitOfMeasurement' => [
                            'short_name' => $unitOfMeasurement->short_name
                        ]
                    ]
                ];
    
                array_push($session['expenditureTransactionItems'], $incomeTransactionItem);
            }
        }

        $request->session()->put('edit-expenditure-transaction-item', $session);

        return back()->with('status', 'Berhasil menambahkan barang.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function deleteCreateSession(Request $request, $item_id)
    {
        $session = $request->session()->get('create-expenditure-transaction-item');

        if (empty($session)) {
            abort(404);
        }

        $changeKey = 0;

        foreach ($session as $key => $value) {
            if ($value['item_id'] == $item_id) {
                unset($session[$key]);
                $changeKey = 1;
                continue;
            }

            if ($changeKey) {
                $session[$key - 1] = $value;
            }
        }

        if (count($session)) {
            $request->session()->put('create-expenditure-transaction-item', $session);
        } else {
            $request->session()->forget('create-expenditure-transaction-item');
        }

        return back()->with('status', 'Berhasil menghapus barang.');
    }

    public function deleteEditSession(Request $request, $expenditure_transaction_id, $item_id)
    {
        $session = session('edit-expenditure-transaction-item');

        if (empty($session)) {
            $session = $this->createEditSession($expenditure_transaction_id);
        }

        foreach ($session['expenditureTransactionItems'] as $key => $value) {
            if ($value['item_id'] == $item_id) {
                unset($session['expenditureTransactionItems'][$key]);
            }
        }

        $request->session()->put('edit-expenditure-transaction-item', $session);

        return back()->with('status', 'Berhasil menghapus barang.');
    }

    private function createEditSession($expenditure_transaction_id)
    {
        $expenditureTransactionItems = ExpenditureTransactionItem::where('expenditure_transaction_id', '=', $expenditure_transaction_id)
                                                        ->get();
            
        $session['id'] = $expenditure_transaction_id;
        $session['expenditureTransactionItems'] = [];

        foreach ($expenditureTransactionItems as $key => $value) {
            $item = Item::select('part_number', 'description', 'unit_of_measurement_id')
                        ->find($value->item_id);

            $unitOfMeasurement = UnitOfMeasurement::select('short_name')
                                        ->find($item->unit_of_measurement_id);

            $incomeTrasactionItem['expenditure_transaction_id'] = $value->expenditure_transaction_id;
            $incomeTrasactionItem['item_id'] = $value->item_id;
            $incomeTrasactionItem['amount'] = $value->amount;

            $incomeTrasactionItem['item'] = [
                'part_number' => $item->part_number,
                'description' => $item->description,
                'unitOfMeasurement' => [
                    'short_name' => $unitOfMeasurement->short_name
                ]
            ];

            array_push($session['expenditureTransactionItems'], $incomeTrasactionItem);
        }

        $session['deletedItems'] = [];

        return $session;
    }
}