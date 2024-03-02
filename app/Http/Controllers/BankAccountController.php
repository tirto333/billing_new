<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('create bank account')) {
            $accounts = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('bankAccount.index', compact('accounts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
