<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {

        if (\Auth::user()->can('manage transfer')) {
            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('Select Account', '');

            $query = Transfer::where('created_by', '=', \Auth::user()->creatorId());

            if (str_contains($request->date, ' to ')) {
                $date_range = explode(' to ', $request->date);
                $query->whereBetween('date', $date_range);
            } elseif (!empty($request->date)) {

                $query->where('date', $request->date);
            }

            if (!empty($request->f_account)) {
                $query->where('from_account', '=', $request->f_account);
            }
            if (!empty($request->t_account)) {
                $query->where('to_account', '=', $request->t_account);
            }
            $transfers = $query->get();

            return view('transfer.index', compact('transfers', 'account'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
