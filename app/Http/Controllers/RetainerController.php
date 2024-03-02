<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Retainer;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RetainerController extends Controller
{
    public function index(Request $request)
    {

        if (\Auth::user()->can('manage retainer')) {

            $customer = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('Select Customer', '');

            $status = Retainer::$statues;

            $query = Retainer::where('created_by', '=', \Auth::user()->creatorId());

            if (!empty($request->customer)) {
                $query->where('customer_id', '=', $request->customer);
            }

            if (str_contains($request->issue_date, ' to ')) {
                $date_range = explode(' to ', $request->issue_date);
                $query->whereBetween('issue_date', $date_range);
            } elseif (!empty($request->issue_date)) {

                $query->where('issue_date', $request->issue_date);
            }

            // if (!empty($request->issue_date)) {
            //     $date_range = explode(' to ', $request->issue_date);
            //     $query->whereBetween('issue_date', $date_range);
            // }

            if (!empty($request->status)) {
                $query->where('status', '=', $request->status);
            }

            $retainers = $query->get();
            return view('retainer.index', compact('retainers', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function customerRetainer(Request $request)
    {

        if (\Auth::user()->can('manage customer proposal')) {

            $status = Retainer::$statues;

            $query = Retainer::where('customer_id', '=', \Auth::user()->id)->where('status', '!=', '0')->where('created_by', \Auth::user()->creatorId());

            if (str_contains($request->issue_date, ' to ')) {
                $date_range = explode(' to ', $request->issue_date);
                $query->whereBetween('issue_date', $date_range);
            } elseif (!empty($request->issue_date)) {
                $query->where('issue_date', $request->issue_date);
            }

            if (!empty($request->status)) {
                $query->where('status', '=', $request->status);
            }
            $retainers = $query->get();

            return view('retainer.index', compact('retainers', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function saveRetainerTemplateSettings(Request $request)
    {
        $user = \Auth::user();
        $post = $request->all();
        unset($post['_token']);

        if ($request->retainer_logo) {
            $request->validate(
                [
                    'retainer_logo' => 'image|mimes:png|max:20480',
                ]
            );
            // $path                 = $request->file('retainer_logo')->storeAs('/retainer_logo', $retainer_logo);
            $dir = 'retainer_logo/';
            $retainer_logo         = $user->id . '_retainer_logo.png';

            $path = Utility::upload_file($request, 'retainer_logo', $retainer_logo, $dir, []);
            if ($path['flag'] == 1) {
                $proposal_logo = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }

            $post['retainer_logo'] = $retainer_logo;
        }

        if (isset($post['retainer_template']) && (!isset($post['retainer_color']) || empty($post['retainer_color']))) {
            $post['retainer_color'] = "ffffff";
        }

        foreach ($post as $key => $data) {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                [
                    $data,
                    $key,
                    \Auth::user()->creatorId(),
                ]
            );
        }

        return redirect()->back()->with('success', __('Retainer Setting updated successfully'));
    }

    public function previewRetainer($template, $color)
    {
        $objUser  = \Auth::user();
        $settings = Utility::settings();
        $retainer = new Retainer();

        $customer                   = new \stdClass();
        $customer->email            = '<Email>';
        $customer->shipping_name    = '<Customer Name>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_state   = '<State>';
        $customer->shipping_city    = '<City>';
        $customer->shipping_phone   = '<Customer Phone Number>';
        $customer->shipping_zip     = '<Zip>';
        $customer->shipping_address = '<Address>';
        $customer->billing_name     = '<Customer Name>';
        $customer->billing_country  = '<Country>';
        $customer->billing_state    = '<State>';
        $customer->billing_city     = '<City>';
        $customer->billing_phone    = '<Customer Phone Number>';
        $customer->billing_zip      = '<Zip>';
        $customer->billing_address  = '<Address>';
        $customer->sku         = 'Test123';

        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach ($taxes as $k => $tax) {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if (array_key_exists('Tax ' . $k, $taxesData)) {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                } else {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }

        $retainer->retainer_id = 1;
        $retainer->issue_date  = date('Y-m-d H:i:s');
        $retainer->due_date    = date('Y-m-d H:i:s');
        $retainer->itemData    = $items;

        $retainer->totalTaxPrice = 60;
        $retainer->totalQuantity = 3;
        $retainer->totalRate     = 300;
        $retainer->totalDiscount = 10;
        $retainer->taxesData     = $taxesData;
        $retainer->customField   = [];
        $customFields            = [];

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = Utility::getFontColor($color);

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $retainer_logo = Utility::getValByName('retainer_logo');
        if (isset($retainer_logo) && !empty($retainer_logo)) {
            // $img = Utility::get_file($retainer_logo);
            $img = Utility::get_file('retainer_logo/') . $retainer_logo;
        } else {
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        return view('retainer.templates.' . $template, compact('retainer', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'customFields'));
    }
}
