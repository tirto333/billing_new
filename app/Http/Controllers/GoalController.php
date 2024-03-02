<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage goal')) {
            $golas = Goal::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('goal.index', compact('golas'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
