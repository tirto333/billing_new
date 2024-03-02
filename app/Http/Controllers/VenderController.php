<?php

namespace App\Http\Controllers;

use App\Models\Vender;
use Illuminate\Http\Request;

class VenderController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage vender')) {
            $venders = Vender::where('created_by', \Auth::user()->creatorId())->get();

            return view('vender.index', compact('venders'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
