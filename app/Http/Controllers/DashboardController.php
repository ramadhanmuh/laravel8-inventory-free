<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Item;
use App\Models\IncomeTransaction;
use App\Models\ExpenditureTransaction;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
 
class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // if (session()->has('create-income-transaction-item')) {
        //     session()->forget('create-income-transaction-item');
        // }
        // session()->forget('create-income-transaction-item');

        $data['application'] = Application::getOne();

        $data['itemTotal'] = $this->numberFormatting(Item::count());

        $data['incomeTransactionTotal'] = $this->numberFormatting(IncomeTransaction::count());

        $data['expenditureTransactionTotal'] = $this->numberFormatting(ExpenditureTransaction::count());

        $data['categoryTotal'] = $this->numberFormatting(Category::count());

        $data['brandTotal'] = $this->numberFormatting(Brand::count());

        $data['unitOfMeasurementTotal'] = $this->numberFormatting(UnitOfMeasurement::count());

        return view('pages.dashboard.index', $data);
    }

    private function numberFormatting($number)
    {
        return number_format($number, 0, ',', '.');
    }
}