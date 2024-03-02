<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use Illuminate\Http\Request;

class ContractTypeController extends Controller
{
    public function index()
    {
        if (\Auth::user()->type == 'company') {
            $types = ContractType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('contractType.index', compact('types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

}
