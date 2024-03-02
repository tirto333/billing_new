@extends('layouts.adminnoauth')
@section('page-title')
{{__('Invoice Detail')}}
@endsection
@push('css-page')
<style>
    #card-element {
        border: 1px solid #a3afbb !important;
        border-radius: 10px !important;
        padding: 10px !important;
    }
</style>
@endpush
@push('script-page')
<script type="text/javascript">
</script>
@endpush
@section('content')
<div class="row justify-content-between align-items-center mb-3">
    <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
        <div class="all-button-box mx-2">
            <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" target="_blank"
                class="btn btn-xs btn-primary btn-icon-only width-auto">
                {{__('Download')}}
            </a>
        </div>
        <div class="all-button-box">
            <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto" data-bs-toggle="modal"
                data-bs-target="#paymentModal">
                {{__('Bayar Sekarang')}}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <!-- <div class="col-12"> -->
    <div class="card">
        <div class="card-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row invoice-title mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h2>{{__('Invoice')}}</h2>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h3 class="invoice-number">#INVO-000{{ $invoice->invoice_id }}
                            </h3>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong><br>
                                        {{ $invoice->issue_date }}<br><br>
                                    </small>
                                </div>
                                <div>
                                    <small>
                                        <strong>{{__('Due Date')}} :</strong><br>
                                        {{ $invoice->due_date }}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(!empty($customer->billing_name))
                        <div class="col">
                            <small class="font-style">
                                <strong>{{__('Billed To')}} :</strong><br>
                                {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}
                                {{!empty($customer->billing_state)?$customer->billing_state:'',', '}}
                                {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                <strong>{{__('Tax Number ')}} :
                                </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}

                            </small>
                        </div>
                        @endif
                        @if(App\Models\Utility::getValByName('shipping_display')=='on')
                        <div class="col ">
                            <small>
                                <strong>{{__('Shipped To')}} :</strong><br>
                                {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}
                                {{!empty($customer->shipping_state)?$customer->shipping_state:'' .',
                                '}},{{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                <strong>{{__('Tax Number ')}} :
                                </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}

                            </small>
                        </div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <small>
                                <strong>{{__('Status')}} :</strong><br>
                                @if($invoice->status == 0)
                                <span class="badge fix_badge rounded p-1 px-3 bg-primary">{{
                                    __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                @elseif($invoice->status == 1)
                                <span class="badge fix_badge rounded p-1 px-3 bg-info">{{
                                    __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                @elseif($invoice->status == 2)
                                <span class="badge fix_badge rounded p-1 px-3 bg-secondary">{{
                                    __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                @elseif($invoice->status == 3)
                                <span class="badge fix_badge rounded p-1 px-3 bg-warning">{{
                                    __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                @elseif($invoice->status == 4)
                                <span class="badge fix_badge rounded p-1 px-3 bg-danger">{{
                                    __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                @endif
                            </small>
                        </div>



                        @if(!empty($customFields) && count($invoice->customField)>0)
                        @foreach($customFields as $field)
                        <div class="col text-md-right">
                            <small>
                                <strong>{{$field->name}} :</strong><br>
                                {{!empty($invoice->customField)?$invoice->customField[$field->id]:'-'}}
                                <br><br>
                            </small>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="font-weight-bold">{{__('Product Summary')}}</div>
                            <small>{{__('All items here cannot be deleted.')}}</small>
                            <div class="table-responsive mt-2">
                                <table class="table mb-0 table-striped">
                                    <tr>
                                        <th data-width="40" class="text-dark">#</th>
                                        <th class="text-dark">{{__('Product')}}</th>
                                        <th class="text-dark">{{__('Quantity')}}</th>
                                        <th class="text-dark">{{__('Rate')}}</th>
                                        <th class="text-dark">{{__('Tax')}}</th>
                                        <th class="text-dark">{{__('Discount')}}</th>
                                        <th class="text-dark">{{__('Description')}}</th>
                                        <th class="text-right text-dark" width="12%">{{__('Price')}}<br>
                                            <small class="text-danger font-weight-bold">{{__('before tax &
                                                discount')}}</small>
                                        </th>
                                    </tr>
                                    @php
                                    $totalQuantity=0;
                                    $totalRate=0;
                                    $totalTaxPrice=0;
                                    $totalDiscount=0;
                                    $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                    @if(!empty($iteam->tax))
                                    @php
                                    $taxes=App\Models\Utility::tax($iteam->tax);
                                    $totalQuantity+=$iteam->quantity;
                                    $totalRate+=$iteam->price;
                                    $totalDiscount+=$iteam->discount;
                                    foreach($taxes as $taxe){
                                    $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                    if (array_key_exists($taxe->name,$taxesData))
                                    {
                                    $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                    }
                                    else
                                    {
                                    $taxesData[$taxe->name] = $taxDataPrice;
                                    }
                                    }
                                    @endphp
                                    @endif
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                        <td>{{$iteam->quantity}}</td>
                                        <td>Rp. {{ number_format($iteam->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if(!empty($iteam->tax))
                                            <table>
                                                @php $totalTaxRate = 0;@endphp
                                                @foreach($taxes as $tax)
                                                @php
                                                $taxPrice=App\Models\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                $totalTaxPrice+=$taxPrice;
                                                @endphp
                                                <tr>
                                                    <td>Rp. {{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($taxPrice)}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            Rp. {{ number_format($iteam->discount, 0, ',', '.') }}

                                        </td>
                                        <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                        <td class="text-right">
                                            Rp. {{ number_format($iteam->price*$iteam->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td><b>{{__('Total')}}</b></td>
                                            <td><b>{{$totalQuantity}}</b></td>
                                            <td><b>Rp. {{ number_format($totalRate, 0, ',', '.') }}</b></td>
                                            <td><b>Rp. {{ number_format($totalTaxPrice, 0, ',', '.') }}</b></td>
                                            <td>
                                                <b>Rp. {{ number_format($totalDiscount, 0, ',', '.') }}</b>

                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                            <td class="text-right">
                                                Rp. {{ number_format($invoice->getSubTotal(), 0, ',', '.') }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>{{__('Discount')}}</b></td>
                                            <td class="text-right">
                                                Rp. {{ number_format($invoice->getTotalDiscount(), 0, ',', '.') }}</td>
                                        </tr>

                                        @if(!empty($taxesData))
                                        @foreach($taxesData as $taxName => $taxPrice)
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>Rp.{{ number_format($taxName, 0, ',', '.') }}</b>
                                            </td>
                                            <td class="text-right">Rp. {{ number_format($taxPrice, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                            <td class="blue-text text-right">
                                                Rp. {{ number_format($invoice->getTotal(), 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>{{__('Paid')}}</b></td>
                                            <td class="text-right">
                                                Rp. {{ number_format($invoice->getTotal()-$invoice->getDue() -
                                                ($invoice->invoiceTotalCreditNote()), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>{{__('Credit Note')}}</b></td>
                                            <td class="text-right">
                                                Rp. {{ number_format($invoice->invoiceTotalCreditNote(), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-right"><b>{{__('Due')}}</b></td>
                                            <td class="text-right">Rp. {{ number_format($invoice->getDue(), 0, ',', '.')
                                                }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </div>
    <div class="row"></div>
    <!-- <div class="col-12"> -->
    <h5 class="h4 d-inline-block font-weight-400 mb-2">{{__('Credit Note Summary')}}</h5>
    <div class="card">
        <div class="card-body table-border-style table-border-style">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th class="text-dark">{{__('Date')}}</th>
                        <th class="text-dark" class="">{{__('Amount')}}</th>
                        <th class="text-dark" class="">{{__('Description')}}</th>
                        @if(Gate::check('edit credit note') || Gate::check('delete credit note'))
                        <th class="text-dark">{{__('Action')}}</th>
                        @endif
                    </tr>
                    @forelse($invoice->creditNote as $key =>$creditNote)
                    <tr>
                        <td>{{\Auth::user()->dateFormat($creditNote->date)}}</td>
                        <td class="">{{\Auth::user()->priceFormat($creditNote->amount)}}</td>
                        <td class="">{{$creditNote->description}}</td>
                        <td>
                            @can('edit credit note')
                            <div class="action-btn bg-primary ms-2">
                                <a data-url="{{ route('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}"
                                    data-ajax-popup="true" title="{{__('Edit')}}"
                                    data-original-title="{{__('Credit Note')}}" href="#"
                                    class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                    data-original-title="{{__('Edit')}}">
                                    <i class="ti ti-edit text-white"></i>
                                </a>
                            </div>
                            @endcan
                            @can('delete credit note')
                            <div class="action-btn bg-danger ms-2">
                                {!! Form::open(['method' => 'DELETE', 'route' => array('invoice.delete.credit.note',
                                $creditNote->invoice,$creditNote->id),'id'=>'delete-form-'.$creditNote->id]) !!}
                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para "
                                    data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}"
                                    data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}"
                                    data-confirm-yes="document.getElementById('delete-form-{{$creditNote->id}}').submit();">
                                    <i class="ti ti-trash text-white"></i>
                                </a>
                                {!! Form::close() !!}
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            <p class="text-dark">{{__('No Data Found')}}</p>
                        </td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
    <!-- </div> -->
</div>

<div class="modal fade" id="pesanWhatsapp" tabindex="-1" role="dialog" aria-labelledby="pesanWhatsappLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pesanWhatsappLabel">{{ __('Kirim Pesan') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="card bg-none card-box">
                    <section class="nav-tabs p-2">
                        <div class="tab-content">
                            {{-- @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled']
                            ==
                            'on' && !empty($company_payment_setting['stripe_key']) &&
                            !empty($company_payment_setting['stripe_secret']))) --}}
                            <div class="tab-pane fade active show" id="stripe-payment" role="tabpanel"
                                aria-labelledby="stripe-payment">
                                <form action="{{ route ('kirim.aktifasi') }}" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="card-name-on">{{__('No Whatsappp')}}</label>
                                                <input type="text" name="no_wa" id="card-name-on"
                                                    class="form-control required"
                                                    placeholder="{{ $customer->billing_phone }}"
                                                    value="{{ $customer->billing_phone }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="card-name-on">{{__('isi Pesan')}}</label>
                                                <textarea class="form-control alert alert-info" id="pesan_wa"
                                                    name="pesan_wa" placeholder="Notes" rows="20">Pelanggan Yth

Ini adalah pesan otomatis dari sistem e-billing  layanan PT Data Cyber Nusantara
                            
Salam sejahtera Bapak/Ibu, Kami informasikan data dibawah ini belum melakukan pembayaran
ID INVOICE: {{ $invoice->invoice_id }}
Nama : {{ $customer->shipping_name }}
Alamat : {{ $customer->shipping_address }}
Nama Paket : {{ $iteam->product()->name }}
Bulan : {{ $invoice->issue_date }}
Status : Belum Lunas
Jumlah Yang Harus Dibayar : Rp. {{ number_format($invoice->getDue(), 0, ',', '.') }}
Jatuh tempo : {{ $invoice->due_date }}
                            
Anda bisa bayar Invoice dengan berbagai metode pembayaran Bank Transfer. Kartu Kredit, OVO dan Lainnya.
                            
Lihat Detail dan Bayar Invoice Anda di sini
https://localhost:8000/customer/{{ $invoice->customer_id }}
                               
Segera lakukan pembayaran bisa melalui via transfer ke rekening :
BCA 4731541986
An. PT. Data Cyber Nusantara
Maksimal pembayaran ditunggu sampai 7 hari setelah Invoice ini dikirim
Untuk pembayaran via cash bisa datang ke kantor kami.
                            
PT Data Cyber Nusantara
Jl. Swadaya Blok C No.60 Cinangka – Sawangan
Depok
Tlp/WA : 0857 7576 5295
Email : finance@datacybernusantara.com
                            
Terima Kasih,
e-Billing Staff
                        </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="form-group col-md-12">
                                            <br>
                                            <label for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span class="input-group-text">{{
                                                        App\Models\Utility::getValByName('site_currency')
                                                        }}</span></span>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                    max="{{$invoice->getDue()}}" id="amount">
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>{{__('Please correct the errors and try
                                                    again.')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" type="submit">{{ __('Kirim Pesan')
                                            }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Make Payment') }}</button>
                                                    </div> -->
                                </form>
                            </div>
                            {{-- @endif --}}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@auth('customer')
@if($invoice->getDue() > 0)
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="card bg-none card-box">
                    <section class="nav-tabs p-2">
                        @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on'
                        || $company_payment_setting['is_paypal_enabled'] == 'on' ||
                        $company_payment_setting['is_paystack_enabled'] == 'on' ||
                        $company_payment_setting['is_flutterwave_enabled'] == 'on' ||
                        $company_payment_setting['is_razorpay_enabled'] == 'on' ||
                        $company_payment_setting['is_mercado_enabled'] == 'on' ||
                        $company_payment_setting['is_paytm_enabled'] == 'on' ||
                        $company_payment_setting['is_mollie_enabled'] ==
                        'on' ||
                        $company_payment_setting['is_paypal_enabled'] == 'on' ||
                        $company_payment_setting['is_skrill_enabled'] == 'on' ||
                        $company_payment_setting['is_coingate_enabled'] == 'on' ||
                        $company_payment_setting['is_paymentwall_enabled'] == 'on'))
                        <ul class="nav nav-pills  mb-3" role="tablist">
                            @if($company_payment_setting['is_stripe_enabled'] == 'on' &&
                            !empty($company_payment_setting['stripe_key']) &&
                            !empty($company_payment_setting['stripe_secret']))
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm active" data-bs-toggle="tab"
                                    href="#stripe-payment" role="tab" aria-controls="stripe" aria-selected="true">{{
                                    __('Stripe') }}</a>
                            </li>
                            @endif

                            @if($company_payment_setting['is_paypal_enabled'] == 'on' &&
                            !empty($company_payment_setting['paypal_client_id']) &&
                            !empty($company_payment_setting['paypal_secret_key']))
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{
                                    __('Paypal') }}</a>
                            </li>
                            @endif

                            @if($company_payment_setting['is_paystack_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#paystack-payment" role="tab" aria-controls="paystack"
                                    aria-selected="false">{{ __('Paystack') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_flutterwave_enabled']) &&
                            $company_payment_setting['is_flutterwave_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#flutterwave-payment" role="tab" aria-controls="flutterwave"
                                    aria-selected="false">{{ __('Flutterwave') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_razorpay_enabled']) &&
                            $company_payment_setting['is_razorpay_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#razorpay-payment" role="tab" aria-controls="razorpay"
                                    aria-selected="false">{{ __('Razorpay') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_mercado_enabled']) &&
                            $company_payment_setting['is_mercado_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{
                                    __('Mercado') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_paytm_enabled']) &&
                            $company_payment_setting['is_paytm_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{
                                    __('Paytm') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_mollie_enabled']) &&
                            $company_payment_setting['is_mollie_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{
                                    __('Mollie') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_skrill_enabled']) &&
                            $company_payment_setting['is_skrill_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{
                                    __('Skrill') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_coingate_enabled']) &&
                            $company_payment_setting['is_coingate_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#coingate-payment" role="tab" aria-controls="coingate"
                                    aria-selected="false">{{ __('Coingate') }}</a>
                            </li>
                            @endif

                            @if(isset($company_payment_setting['is_paymentwall_enabled']) &&
                            $company_payment_setting['is_paymentwall_enabled'] == 'on')
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-primary btn-sm ml-1" data-bs-toggle="tab"
                                    href="#paymentwall-payment" role="tab" aria-controls="paymentwall"
                                    aria-selected="false">{{ __('PaymentWall') }}</a>
                            </li>
                            @endif

                        </ul>
                        @endif
                        <div class="tab-content">
                            @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] ==
                            'on' && !empty($company_payment_setting['stripe_key']) &&
                            !empty($company_payment_setting['stripe_secret'])))
                            <div class="tab-pane fade active show" id="stripe-payment" role="tabpanel"
                                aria-labelledby="stripe-payment">
                                <form method="post" action="{{ route('customer.invoice.payment',$invoice->id) }}"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="custom-radio">
                                                <label class="font-16 font-weight-bold">{{__('Credit / Debit
                                                    Card')}}</label>
                                            </div>
                                            <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank
                                                account. We support Mastercard, Visa, Discover and American express.')}}
                                            </p>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="card-name-on">{{__('Name on card')}}</label>
                                                <input type="text" name="name" id="card-name-on"
                                                    class="form-control required" placeholder="{{\Auth::user()->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="card-element">

                                            </div>
                                            <div id="card-errors" role="alert"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <br>
                                            <label for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span class="input-group-text">{{
                                                        App\Models\Utility::getValByName('site_currency')
                                                        }}</span></span>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                    max="{{$invoice->getDue()}}" id="amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>{{__('Please correct the errors and try
                                                    again.')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" type="submit">{{ __('Make
                                            Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Make Payment') }}</button>
                                                    </div> -->
                                </form>
                            </div>
                            @endif

                            @if(!empty($company_payment_setting) && ($company_payment_setting['is_paypal_enabled'] ==
                            'on' && !empty($company_payment_setting['paypal_client_id']) &&
                            !empty($company_payment_setting['paypal_secret_key'])))
                            <div class="tab-pane fade " id="paypal-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form"
                                    action="{{ route('customer.invoice.with.paypal',$invoice->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span class="input-group-text">{{
                                                        App\Models\Utility::getValByName('site_currency')
                                                        }}</span></span>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                    max="{{$invoice->getDue()}}" id="amount">
                                                @error('amount')
                                                <span class="invalid-amount" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" name="submit" type="submit">{{
                                            __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <button class="btn btn-sm btn-primary rounded-pill" name="submit" type="submit">{{ __('Make Payment') }}</button>
                                                    </div> -->
                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_paystack_enabled']) &&
                            $company_payment_setting['is_paystack_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <div class="tab-pane fade " id="paystack-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="paystack-payment-form"
                                    action="{{ route('customer.invoice.pay.with.paystack') }}">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span
                                                    class="input-group-text">{{App\Models\Utility::getValByName('site_currency')
                                                    }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_paystack"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_paystack" type="button" value="{{ __('Make Payment') }}">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_flutterwave_enabled']) &&
                            $company_payment_setting['is_flutterwave_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <div class="tab-pane fade " id="flutterwave-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.flaterwave') }}"
                                    method="post" class="require-validation" id="flaterwave-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_flaterwave"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_flaterwave" type="button" value="{{ __('Make Payment') }}">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_razorpay_enabled']) &&
                            $company_payment_setting['is_razorpay_enabled'] == 'on')
                            <div class="tab-pane fade " id="razorpay-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.razorpay') }}"
                                    method="post" class="require-validation" id="razorpay-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_razorpay"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input class="btn btn-sm btn-primary rounded-pill" id="pay_with_razorpay" type="button" value="{{ __('Make Payment') }}">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_mercado_enabled']) &&
                            $company_payment_setting['is_mercado_enabled'] == 'on')
                            <div class="tab-pane fade " id="mercado-payment" role="tabpanel"
                                aria-labelledby="mercado-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.mercado') }}"
                                    method="post" class="require-validation" id="mercado-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_mercado"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input type="submit" id="pay_with_mercado" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_paytm_enabled']) &&
                            $company_payment_setting['is_paytm_enabled'] == 'on')
                            <div class="tab-pane fade" id="paytm-payment" role="tabpanel"
                                aria-labelledby="paytm-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.paytm') }}" method="post"
                                    class="require-validation" id="paytm-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="flaterwave_coupon" class=" text-dark">{{__('Mobile
                                                Number')}}</label>
                                            <input type="text" id="mobile" name="mobile" class="form-control mobile"
                                                data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_paytm" name="submit"
                                            type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input type="submit" id="pay_with_paytm" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_mollie_enabled']) &&
                            $company_payment_setting['is_mollie_enabled'] == 'on')
                            <div class="tab-pane fade " id="mollie-payment" role="tabpanel"
                                aria-labelledby="mollie-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.mollie') }}" method="post"
                                    class="require-validation" id="mollie-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_mollie" name="submit"
                                            type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input type="submit" id="pay_with_mollie" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_skrill_enabled']) &&
                            $company_payment_setting['is_skrill_enabled'] == 'on')
                            <div class="tab-pane fade " id="skrill-payment" role="tabpanel"
                                aria-labelledby="skrill-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.skrill') }}" method="post"
                                    class="require-validation" id="skrill-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    @php
                                    $skrill_data = [
                                    'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                    'user_id' => 'user_id',
                                    'amount' => 'amount',
                                    'currency' => 'currency',
                                    ];
                                    session()->put('skrill_data', $skrill_data);

                                    @endphp
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_skrill" name="submit"
                                            type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input type="submit" id="pay_with_skrill" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(isset($company_payment_setting['is_coingate_enabled']) &&
                            $company_payment_setting['is_coingate_enabled'] == 'on')
                            <div class="tab-pane fade " id="coingate-payment" role="tabpanel"
                                aria-labelledby="coingate-payment">
                                <form role="form" action="{{ route('customer.invoice.pay.with.coingate') }}"
                                    method="post" class="require-validation" id="coingate-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_coingate"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                        <input type="submit" id="pay_with_coingate" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                    </div> -->

                                </form>
                            </div>
                            @endif

                            @if(!empty($company_payment_setting) && $company_payment_setting['is_paymentwall_enabled']
                            == 'on' && !empty($company_payment_setting['is_paymentwall_enabled']) &&
                            !empty($company_payment_setting['paymentwall_secret_key']))
                            <div class="tab-pane " id="paymentwall-payment">
                                <!-- <div class="card"> -->
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="paymentwall-payment-form"
                                    action="{{ route('customer.invoice.paymentwallpayment') }}">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                    <!-- <div class="border p-3 mb-3 rounded">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    <div class="form-group">
                                                                        <label for="paypal_coupon" class="form-label">{{__('Coupon')}}</label>
                                                                        <input type="text" id="paymentwall_coupon" name="coupon" class="form-control coupon" data-from="paymentwall" placeholder="{{ __('Enter Coupon Code') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto my-auto">
                                                                    <a href="#" class="apply-btn apply-coupon" data-toggle="tooltip" data-title="{{__('Apply')}}"><i class="ti ti-save"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <div class="col-sm-12">
                                                                <div class="text-sm-right">
                                                                    <input type="submit" id="pay_with_paymentwall" value="{{__('Pay Now')}}" class="btn-create badge-blue">
                                                                </div>
                                                            </div>
                                                        </div> -->
                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    App\Models\Utility::getValByName('site_currency') }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{$invoice->getDue()}}" min="0" step="0.01"
                                                max="{{$invoice->getDue()}}" id="amount">
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" id="pay_with_paymentwall"
                                            name="submit" type="submit">{{ __('Make Payment') }} </button>
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                                            <input type="submit" id="pay_with_paymentwall" value="{{__('Make Payment')}}" class="btn btn-sm btn-primary rounded-pill">
                                                        </div> -->
                                </form>
                                <!-- </div> -->
                            </div>
                            @endif

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

@endsection