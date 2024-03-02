<?php

namespace App\Http\Controllers;

use App\Models\ProductService;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function index()
    {

        if (\Auth::user()->can('manage product & service')) {
            $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('productstock.index', compact('productServices'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
