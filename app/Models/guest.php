<?php

namespace App\Models;

use Illuminate\Foundation\Auth\Guest as Authenticatable;

class guest extends Authenticatable
{
    public function invoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }
}
