<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (\Auth::user()->can('manage constant custom field')) {
            $custom_fields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('customFields.index', compact('custom_fields'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
