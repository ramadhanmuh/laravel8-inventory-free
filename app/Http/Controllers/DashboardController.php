<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
 
class DashboardController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['application'] = Application::getOne();

        return view('pages.dashboard.index', $data);
    }
}