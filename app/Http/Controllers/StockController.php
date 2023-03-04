<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Item;

class StockController extends Controller
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

        $data['items'] = Item::getWithCategoryBrandUOMStock($data['input']);

        $itemTotal = Item::countWithCategoryBrandUOMStock($data['input']);

        $data['pageTotal'] = intval(ceil($itemTotal / 10));

        return view('pages.stock.index', $data);
    }

    private function getInputParameter($request)
    {
        return [
            'page'=> intval($request->page),
            'order_by'=> strval($request->order_by),
            'order_direction'=> strval($request->order_direction),
            'keyword'=> strval($request->keyword),
            'category' => strval($request->category),
            'brand' => strval($request->brand),
            'uom' => strval($request->uom),
            'start_stock' => $request->start_stock,
            'end_stock' => $request->end_stock,
        ];
    }
}
