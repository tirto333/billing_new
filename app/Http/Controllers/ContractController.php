<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {

        if ((\Auth::user()->can('manage contract')) || (\Auth::user()->can('manage customer contract'))) {
            if (\Auth::user()->can('manage contract')) {
                $contracts = Contract::where('created_by', '=', \Auth::user()->creatorId())->get();
                $curr_month = Contract::where('created_by', '=', \Auth::user()->creatorId())->whereMonth('start_date', '=', date('m'))->get();
                $curr_week = Contract::where('created_by', '=', \Auth::user()->creatorId())->whereBetween(
                    'start_date',
                    [
                        \carbon\Carbon::now()->startOfWeek(),
                        \carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('created_by', '=', \Auth::user()->creatorId())->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
            } else {
                $contracts = Contract::where('customer', '=', \Auth::user()->customer_id)->get();
                $curr_month = Contract::where('customer', '=', \Auth::user()->customer_id)->whereMonth('start_date', '=', date('m'))->get();
                $curr_week = Contract::where('customer', '=', \Auth::user()->customer_id)->whereBetween(
                    'start_date',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('customer', '=', \Auth::user()->customer_id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
            }

            $cnt_contract                = [];
            $cnt_contract['total']       = \App\Models\Contract::getContractSummary($contracts);
            $cnt_contract['this_month']  = \App\Models\Contract::getContractSummary($curr_month);
            $cnt_contract['this_week']   = \App\Models\Contract::getContractSummary($curr_week);
            $cnt_contract['last_30days'] = \App\Models\Contract::getContractSummary($last_30days);

            return view('contract.index', compact('contracts', 'cnt_contract'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
