<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage budget planner')) {
            $budgets = Budget::where('created_by', '=', \Auth::user()->creatorId())->get();
            $periods = Budget::$period;
            return view('budget.index', compact('budgets', 'periods'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
