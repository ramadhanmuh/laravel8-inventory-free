<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        if (!request()->is('income-transactions/*') || !request()->is('income-transactions')) {
            session()->forget([
                'create-income-transaction-item',
                'edit-income-transaction-item'
            ]);
        }
    }
}
