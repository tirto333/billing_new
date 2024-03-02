<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage assets')) {
            $assets = Asset::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('assets.index', compact('assets'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
