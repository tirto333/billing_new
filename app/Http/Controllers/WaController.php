<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class WaController extends Controller
{

    public static function invoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public function customerInvoiceShowNoAuth($id)
    {
        // if (\Auth::guest()->can('show invoice')) {
        $invoice_id = $id;
        $invoice    = Invoice::where('id', $invoice_id)->first();
        // if ($invoice->created_by == \Auth::user()->creatorId()) {
        $customer = $invoice->customer;
        $iteams   = $invoice->items;

        // $company_payment_setting = Uity::getCompanyPaymentSetting($id);

        return view('invoice.viewnoauth', compact('invoice', 'customer', 'iteams'));
    }

    public function kirimaktifasi(Request $request)
    {

        $no_wa = $request->no_wa;
        $pesan_wa = $request->pesan_wa;

        // $token = 'Erg7tDntv+QbmM!oXSWG';
        $token = 'YFzNzyC4V@8+f9QCU+U7';
        $apiUrl = 'https://api.fonnte.com/send';

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post($apiUrl, [
            'target' => $no_wa,
            'message' => $pesan_wa,
            // Tambahkan parameter lain sesuai kebutuhan
        ]);

        // $whatsappLink = "https://wa.me/" . $no_wa . "?text=" . urlencode($pesan_wa);
        return redirect()->back()->with('success', 'Pesan WhatsApp berhasil terkirim ke ' . $no_wa);
    }
}
