<?php

namespace App\Http\Controllers\Web\Internal;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Repositories\Internal\Dashboard\DashboardRepository;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        $user = auth()->user();
        $employees = Employee::paginate(10);
        return view('internal.dashboard')
        ->with('employees', $employees);
    }

}
