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
        $data['application'] = Application::getOne();

        $data['itemTotal'] = currency(Item::count());

        $data['incomeTransactionTotal'] = currency(IncomeTransaction::count());

        $data['expenditureTransactionTotal'] = currency(ExpenditureTransaction::count());

        $data['categoryTotal'] = currency(Category::count());

        $data['brandTotal'] = currency(Brand::count());

        $data['unitOfMeasurementTotal'] = currency(UnitOfMeasurement::count());

        return view('pages.dashboard.index', $data);
    }
}