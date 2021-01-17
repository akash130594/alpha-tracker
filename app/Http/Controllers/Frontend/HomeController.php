<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Auth;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if($user = Auth::user())
        {
            $employees = Employee::paginate(10);
            return view('internal.dashboard')
            ->with('employees', $employees);
        } else {
            return redirect()->route('frontend.auth.login');
        }



    }
}
