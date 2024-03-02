<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage coupon')) {
            $coupons = Coupon::get();
            return view('coupon.index', compact('coupons'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
