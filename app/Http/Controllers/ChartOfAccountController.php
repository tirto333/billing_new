<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountType;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index()
    {


        if (\Auth::user()->can('manage chart of account')) {
            $types = ChartOfAccountType::get();

            $chartAccounts = [];
            foreach ($types as $type) {
                $accounts = ChartOfAccount::where('type', $type->id)->where('created_by', '=', \Auth::user()->creatorId())->get();

                $chartAccounts[$type->name] = $accounts;
            }

            return view('chartOfAccount.index', compact('chartAccounts', 'types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
