@extends('layouts.admin')
@section('page-title')
    {{__('Settings')}}
@endsection
@php
$logo=\App\Models\Utility::get_file('uploads/logo/');
$logo_light = \App\Models\Utility::getValByName('company_logo_light');

$logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
$company_favicon = \App\Models\Utility::getValByName('company_favicon');
$EmailTemplates     = App\Models\EmailTemplate::all();
$setting = App\Models\Utility::settings();

@endphp


@push('script-page')
    <script type="text/javascript">

        $(".email-template-checkbox").click(function(){
           
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'post',
                success: function (response) {
                    if (response.is_success) {
                        toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastr('Error', response.error, 'error');
                    } else {
                        toastr('Error', response, 'error');
                    }
                }
            })
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
        
    </script>
    <script>
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{url('/invoices/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function () {
            var template = $("select[name='proposal_template']").val();
            var color = $("input[name='proposal_color']:checked").val();
            $('#proposal_frame').attr('src', '{{url('/proposal/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function () {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{url('/bill/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='retainer_template'], input[name='retainer_color']", function () {
            var template = $("select[name='retainer_template']").val();
            var color = $("input[name='retainer_color']:checked").val();
            $('#retainer_frame').attr('src', '{{url('/retainer/preview')}}/' + template + '/' + color);
        });
    </script>

<script>
    
    $(".list-group-item").click(function(){
        $('.list-group-item').filter(function(){
            return this.href == id;
        }).parent().removeClass('text-primary');
    });

    function check_theme(color_val) {
        $('#theme_color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }

    $(document).on('change','[name=storage_setting]',function(){
    if($(this).val() == 's3'){
        $('.s3-setting').removeClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').addClass('d-none');
    }else if($(this).val() == 'wasabi'){
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').removeClass('d-none');
        $('.local-setting').addClass('d-none');
    }else{
        $('.s3-setting').addClass('d-none');
        $('.wasabi-setting').addClass('d-none');
        $('.local-setting').removeClass('d-none');
    }
});
</script>



@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Settings')}}</li>
@endsection
@section('content')

<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        {{-- <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Business Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a> --}}
                        <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{ __('System Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-3" class="list-group-item list-group-item-action border-0">{{ __('Company Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        {{-- <a href="#useradd-4" class="list-group-item list-group-item-action border-0">{{ __('Proposal Print Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a> --}}
                        {{-- <a href="#useradd-10" class="list-group-item list-group-item-action border-0">{{ __('Retainer Print Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>     --}}
                        {{-- <a href="#useradd-5" class="list-group-item list-group-item-action border-0">{{ __('Invoice Print Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-6" class="list-group-item list-group-item-action border-0">{{ __('Bill Print Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a> --}}
                        {{-- <a href="#useradd-7" class="list-group-item list-group-item-action border-0">{{ __('Payment Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-8" class="list-group-item list-group-item-action border-0">{{ __('Twilio Setting') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a> --}}
                        {{-- <a href="#useradd-9" class="list-group-item list-group-item-action border-0">{{ __('Email Notification') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div></a> --}}
                      
                       

                    </div>
                </div>
            </div>
            

            <div class="col-xl-9">

                <!--Business Setting-->
                {{-- <div id="useradd-1" class="card">

                    {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                    <div class="card-header">
                        <h5>{{ __('Business Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                    </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo dark') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($logo_dark) && !empty($logo_dark)? $logo_dark:'logo-dark.png')}}" target="_blank">
                                                        <img id="blah" alt="your image" src="{{$logo.(isset($logo_dark) && !empty($logo_dark)? $logo_dark:'logo-dark.png')}}" width="150px" class="big-logo">
                                                    </a>

                                                 
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo">
                                                        <div class=" bg-primary company_logo_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_dark" id="company_logo" class="form-control file" data-filename="company_logo_update" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">

                                                        <!-- <input type="file" name="company_logo_dark" id="company_logo" class="form-control file" data-filename="company_logo_update"> -->
                                                    </label>
                                                </div>
                                                @error('company_logo')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo Light') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($logo_light) && !empty($logo_light)?$logo_light:'logo-light.png')}}" target="_blank">
                                                        <img id="blah1" alt="your image" src="{{$logo.(isset($logo_light) && !empty($logo_light)?$logo_light:'logo-light.png')}}" width="150px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo_light">
                                                        <div class=" bg-primary dark_logo_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_light" id="company_logo_light" class="form-control file" data-filename="dark_logo_update" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file" name="company_logo_light" id="company_logo_light"
                                                            data-filename="dark_logo_update"> -->
                                                    </label>
                                                </div>
                                                @error('company_logo_light')
                                                <div class="row">
                                                                            <span class="invalid-logo" role="alert">
                                                                                <strong class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{$logo.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" target="_blank">
                                                        <img id="blah2" alt="your image" src="{{$logo.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" width="80px" class="big-logo img_setting">
                                                    </a>

                                                    <!-- <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" width="50px"
                                                        class="big-logo img_setting" width="150px"> -->
                                                </div>
                                                <div class="choose-files mt-4">
                                                    <label for="company_favicon">
                                                        <div class="bg-primary company_favicon_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_favicon" id="company_favicon" class="form-control file" data-filename="company_favicon_update" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file"  id="company_favicon" name="company_favicon"
                                                            data-filename="company_favicon_update"> -->
                                                    </label>
                                                </div>
                                                @error('logo')
                                                <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label('title_text',__('Title Text'),array('class'=>'form-label')) }}
                                    {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                    @error('title_text')
                                    <span class="invalid-title_text" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                    @enderror
                                </div>


                                <div class="col-3 my-auto">
                                    <div class="form-group">
                                        <label class="text-dark mb-1" for="SITE_RTL">{{ __('RTL') }}</label>
                                        <div class="">
                                            <input type="checkbox" name="SITE_RTL" id="SITE_RTL" data-toggle="switchbutton" {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }} data-onstyle="primary">
                                            <label class="form-check-labe" for="SITE_RTL"></label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>



                                <h4 class="small-title">{{__('Theme Customizer')}}</h4>
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="col-4 my-auto">
                                            <h6 class="mt-2">
                                                <i data-feather="credit-card" class="me-2"></i>{{ __('Primary color settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="theme-color themes-color">
                                                <input type="hidden" name="color" id="color_value" value="{{ $settings['color'] }}">
                                                <a href="#!" class="" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-1" style="display: none;">
                                                <a href="#!" class="" data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-2" style="display: none;">
                                                <a href="#!" class="" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-3" style="display: none;">
                                                <a href="#!" class="" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-4" style="display: none;">
                                            </div>
                                        </div>
                                        <div class="col-4 ">
                                            <h6 class="mt-2">
                                                <i data-feather="layout" class="me-2"></i>{{__('Sidebar settings')}}
                                            </h6>
                                            <hr class="mt-1" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg" {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg"
                                                >{{__('Transparent layout')}}</label
                                                >
                                            </div>
                                        </div>
                                        <div class="col-4 ">
                                            <h6 class="mt-2 ">
                                                <i data-feather="sun" class="me-2"></i>{{__('Layout settings')}}
                                            </h6>
                                            <hr class="mt-1" />
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout"{{ Utility::getValByName('cust_darklayout') == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1" for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div> --}}
                <!--System Setting-->
                <div id="useradd-2" class="card">
                    <div class="card-header">
                        <h5>{{ __('System Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                    </div>

                    {{Form::model($settings,array('route'=>'system.settings','method'=>'post'))}}
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::label('site_currency',__('Currency *'),array('class' => 'form-label')) }}
                                {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                                @error('site_currency')
                                <span class="invalid-site_currency" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('site_currency_symbol',__('Currency Symbol *'),array('class' => 'form-label')) }}
                                {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                @error('site_currency_symbol')
                                <span class="invalid-site_currency_symbol" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                    <div class="row px-3">
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="pre" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif
                                            id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{__('Pre')}}
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" name="site_currency_symbol_position" value="post" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif
                                            id="flexCheckChecked" checked>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                {{__('Post')}}
                                            </label>
                                        </div>

                                        {{-- <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">

                                                <input type="radio" id="customRadio5" name="site_currency_symbol_position" value="pre" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">
                                                <input type="radio" id="customRadio6" name="site_currency_symbol_position" value="post" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_date_format" class="form-label">{{__('Date Format')}}</label>
                                <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                    <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                    <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                    <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                    <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_time_format" class="form-label">{{__('Time Format')}}</label>
                                <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                    <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                    <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                    <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('invoice_prefix',__('Invoice Prefix'),array('class'=>'form-label')) }}

                                {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                @error('invoice_prefix')
                                <span class="invalid-invoice_prefix" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                                <div class="form-group col-md-6">
                                    {{Form::label('invoice_starting_number',__('Invoice Starting Number'),array('class'=>'form-label')) }}
                                    {{Form::text('invoice_starting_number',null,array('class'=>'form-control'))}}
                                    @error('invoice_starting_number')
                                    <span class="invalid-invoice_starting_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('proposal_prefix',__('Proposal Prefix'),array('class'=>'form-label')) }}
                                    {{Form::text('proposal_prefix',null,array('class'=>'form-control'))}}
                                    @error('proposal_prefix')
                                    <span class="invalid-proposal_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('proposal_starting_number',__('Proposal Starting Number'),array('class'=>'form-label')) }}
                                    {{Form::text('proposal_starting_number',null,array('class'=>'form-control'))}}
                                    @error('proposal_starting_number')
                                    <span class="invalid-proposal_starting_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>

                            <div class="form-group col-md-6">
                                {{Form::label('bill_prefix',__('Bill Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('bill_prefix',null,array('class'=>'form-control'))}}
                                @error('bill_prefix')
                                <span class="invalid-bill_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                    {{Form::label('retainer_starting_number',__('Retainer Starting Number'),array('class'=>'form-label')) }}
                                    {{Form::text('retainer_starting_number',null,array('class'=>'form-control'))}}
                                    @error('retainer_starting_number')
                                    <span class="invalid-proposal_starting_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>

                            <div class="form-group col-md-6">
                                {{Form::label('retainer_prefix',__('Retainer Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('retainer_prefix',null,array('class'=>'form-control'))}}
                                @error('retainer_prefix')
                                <span class="invalid-bill_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('bill_starting_number',__('Bill Starting Number'),array('class'=>'form-label')) }}
                                {{Form::text('bill_starting_number',null,array('class'=>'form-control'))}}
                                @error('bill_starting_number')
                                <span class="invalid-bill_starting_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('customer_prefix',__('Customer Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('customer_prefix',null,array('class'=>'form-control'))}}
                                @error('customer_prefix')
                                <span class="invalid-customer_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('vender_prefix',__('Vender Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('vender_prefix',null,array('class'=>'form-control'))}}
                                @error('vender_prefix')
                                <span class="invalid-vender_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('footer_title',__('Invoice/Bill Footer Title'),array('class'=>'form-label')) }}
                                {{Form::text('footer_title',null,array('class'=>'form-control'))}}
                                @error('footer_title')
                                <span class="invalid-footer_title" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                {{Form::label('decimal_number',__('Decimal Number Format'),array('class'=>'form-label')) }}
                                {{Form::number('decimal_number', null, ['class'=>'form-control'])}}
                                @error('decimal_number')
                                <span class="invalid-decimal_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                {{Form::label('journal_prefix',__('Journal Prefix'),array('class'=>'form-label')) }}
                                {{Form::text('journal_prefix',null,array('class'=>'form-control'))}}
                                @error('journal_prefix')
                                <span class="invalid-journal_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                {{Form::label('shipping_display',__('Shipping Display in Proposal / Invoice / Bill ?'),array('class'=>'form-label')) }}
                                <div class=" form-switch form-switch-left">
                                    <input type="checkbox" class="form-check-input" name="shipping_display" id="email_tempalte_13" {{($settings['shipping_display']=='on')?'checked':''}} >
                                    <label class="form-check-label" for="email_tempalte_13"></label>
                                </div>

                                @error('shipping_display')
                                <span class="invalid-shipping_display" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>





                            <div class="form-group col-md-6">
                                {{Form::label('footer_notes',__('Invoice/Bill Footer Notes'),array('class'=>'form-label')) }}
                                {{Form::textarea('footer_notes', null, ['class'=>'form-control','rows'=>'3'])}}
                                @error('footer_notes')
                                <span class="invalid-footer_notes" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                        </div>
                    </div>
                    {{Form::close()}}

                </div>

                <!--Company Setting-->
                <div id="useradd-3" class="card">
                    <div class="card-header">
                        <h5>{{ __('Company Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company') }}</small>
                    </div>
                    {{Form::model($settings,array('route'=>'company.settings','method'=>'post'))}}
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::label('company_name *',__('Company Name *'),array('class' => 'form-label')) }}
                                {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                                @error('company_name')
                                <span class="invalid-company_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_address',__('Address'),array('class' => 'form-label')) }}
                                {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                                @error('company_address')
                                <span class="invalid-company_address" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_city',__('City'),array('class' => 'form-label')) }}
                                {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                                @error('company_city')
                                <span class="invalid-company_city" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_state',__('State'),array('class' => 'form-label')) }}
                                {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                                @error('company_state')
                                <span class="invalid-company_state" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_zipcode',__('Zip/Post Code'),array('class' => 'form-label')) }}
                                {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                @error('company_zipcode')
                                <span class="invalid-company_zipcode" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group  col-md-6">
                                {{Form::label('company_country',__('Country'),array('class' => 'form-label')) }}
                                {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                                @error('company_country')
                                <span class="invalid-company_country" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_telephone',__('Telephone'),array('class' => 'form-label')) }}
                                {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                @error('company_telephone')
                                <span class="invalid-company_telephone" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_email',__('System Email *'),array('class' => 'form-label')) }}
                                {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                @error('company_email')
                                <span class="invalid-company_email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_email_from_name',__('Email (From Name) *'),array('class' => 'form-label')) }}
                                {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                                @error('company_email_from_name')
                                <span class="invalid-company_email_from_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('registration_number',__('Company Registration Number *'),array('class' => 'form-label')) }}
                                {{Form::text('registration_number',null,array('class'=>'form-control'))}}
                                @error('registration_number')
                                <span class="invalid-registration_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline form-group mb-3">
                                                <input type="radio" id="customRadio8" name="tax_type" value="VAT" class="form-check-input" {{($settings['tax_type'] == 'VAT')?'checked':''}} >
                                                <label class="form-check-label" for="customRadio8">{{__('VAT Number')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline form-group mb-3">
                                                <input type="radio" id="customRadio7" name="tax_type" value="GST" class="form-check-input" {{($settings['tax_type'] == 'GST')?'checked':''}}>
                                                <label class="form-check-label" for="customRadio7">{{__('GST Number')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    {{Form::text('vat_number',null,array('class'=>'form-control','placeholder'=>__('Enter VAT / GST Number')))}}
                                </div>
                            </div>
                        </div>
                    <div class="card-footer text-end">
                        <div class="form-group">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" id="addSig" value="{{__('Save Changes')}}">
                        </div>
                    </div>
                    {{Form::close()}}

                    </div>
                </div>

                <!--Proposal Print Setting-->
                {{-- <div id="useradd-4" class="card">
                    <div class="card-header">
                        <h5>{{ __('Proposal Print Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company Proposal') }}</small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <form id="setting-form" method="post" action="{{route('proposal.template.setting')}}" enctype ="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="address" class="col-form-label">{{__('Proposal Print Template')}}</label>
                                            <select class="form-control select2" name="proposal_template">
                                                @foreach(App\Models\Utility::templateData()['templates'] as $key => $template)
                                                    <option value="{{$key}}" {{(isset($settings['proposal_template']) && $settings['proposal_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Color Input')}}</label>
                                            <div class="row gutters-xs">
                                                @foreach(App\Models\Utility::templateData()['colors'] as $key => $color)
                                                    <div class="col-auto">
                                                        <label class="colorinput">
                                                            <input name="proposal_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['proposal_color']) && $settings['proposal_color'] == $color) ? 'checked' : ''}}>
                                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Proposal Logo')}}</label>

                                            
                                            <div class="choose-files mt-5 ">
                                                <label for="proposal_logo">
                                                    <div class=" bg-primary proposal_logo_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                    <img id="blah4" class="mt-3" src=""  width="70%"  />
                                                    <input type="file" class="form-control file" name="proposal_logo" id="proposal_logo" data-filename="proposal_logo_update" onchange="document.getElementById('blah4').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="proposal_logo" id="proposal_logo" data-filename="proposal_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if(isset($settings['proposal_template']) && isset($settings['proposal_color']))
                                    <iframe id="proposal_frame" class="w-100 h-100" frameborder="0" src="{{route('proposal.preview',[$settings['proposal_template'],$settings['proposal_color']])}}"></iframe>
                                @else
                                    <iframe id="proposal_frame" class="w-100 h-100" frameborder="0" src="{{route('proposal.preview',['template1','fffff'])}}"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>

                </div> --}}

                <!--Retainer Print Setting-->
                {{-- <div id="useradd-10" class="card">
                    <div class="card-header">
                        <h5>{{ __('Retainer Print Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company Retainer') }}</small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <form id="setting-form" method="post" action="{{route('retainer.template.setting')}}" enctype ="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="address" class="col-form-label">{{__('Retainer Print Template')}}</label>
                                            <select class="form-control select2" name="retainer_template">
                                                @foreach(App\Models\Utility::templateData()['templates'] as $key => $template)
                                                    <option value="{{$key}}" {{(isset($settings['retainer_template']) && $settings['retainer_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Color Input')}}</label>
                                            <div class="row gutters-xs">
                                                @foreach(App\Models\Utility::templateData()['colors'] as $key => $color)
                                                    <div class="col-auto">
                                                        <label class="colorinput">
                                                            <input name="retainer_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['retainer_color']) && $settings['retainer_color'] == $color) ? 'checked' : ''}}>
                                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Retainer Logo')}}</label>
                                            <div class="choose-files mt-5 ">
                                                <label for="retainer_logo">
                                                    <div class=" bg-primary proposal_logo_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                    <img id="blah5" class="mt-3" src=""  width="70%"  />
                                                    <input type="file" class="form-control file" name="retainer_logo" id="retainer_logo" data-filename="retainer_logo_update" onchange="document.getElementById('blah5').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="retainer_logo" id="retainer_logo" data-filename="retainer_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if(isset($settings['retainer_template']) && isset($settings['retainer_color']))
                                    <iframe id="retainer_frame" class="w-100 h-100" frameborder="0" src="{{route('retainer.preview',[$settings['retainer_template'],$settings['retainer_color']])}}"></iframe>
                                @else
                                    <iframe id="retainer_frame" class="w-100 h-100" frameborder="0" src="{{route('retainer.preview',['template1','fffff'])}}"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>

                </div> --}}

                <!--Invoice Setting-->
                {{-- <div id="useradd-5" class="card">
                    <div class="card-header">
                        <h5>{{ __('Invoice Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company invoice') }}</small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <form id="setting-form" method="post" action="{{route('invoice.template.setting')}}" enctype ="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="address" class="col-form-label">{{__('Invoice Template')}}</label>
                                            <select class="form-control select2" name="invoice_template">
                                                @foreach(Utility::templateData()['templates'] as $key => $template)
                                                    <option value="{{$key}}" {{(isset($settings['invoice_template']) && $settings['invoice_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Color Input')}}</label>
                                            <div class="row gutters-xs">
                                                @foreach(Utility::templateData()['colors'] as $key => $color)
                                                    <div class="col-auto">
                                                        <label class="colorinput">
                                                            <input name="invoice_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['invoice_color']) && $settings['invoice_color'] == $color) ? 'checked' : ''}}>
                                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Invoice Logo')}}</label>
                                            <div class="choose-files mt-5 ">
                                                <label for="invoice_logo">
                                                    <div class=" bg-primary invoice_logo_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                    <img id="blah6" class="mt-3" src=""  width="70%"  />
                                                    <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo" data-filename="invoice_logo_update" onchange="document.getElementById('blah6').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo" data-filename="invoice_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if(isset($settings['invoice_template']) && isset($settings['invoice_color']))
                                    <iframe id="invoice_frame" class="w-100 h-100" frameborder="0" src="{{route('invoice.preview',[$settings['invoice_template'],$settings['invoice_color']])}}"></iframe>
                                @else
                                    <iframe id="invoice_frame" class="w-100 h-100" frameborder="0" src="{{route('invoice.preview',['template1','fffff'])}}"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>


                </div> --}}

                <!--Bill Setting-->
                {{-- <div id="useradd-6" class="card">
                    <div class="card-header">
                        <h5>{{ __('Bill Print Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company Bill') }}</small>
                    </div>

                    <div class="bg-none">
                        <div class="row company-setting">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <form id="setting-form" method="post" action="{{route('bill.template.setting')}}" enctype ="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="address" class="form-label">{{__('Bill Template')}}</label>
                                            <select class="form-control" name="bill_template">
                                                @foreach(App\Models\Utility::templateData()['templates'] as $key => $template)
                                                    <option value="{{$key}}" {{(isset($settings['bill_template']) && $settings['bill_template'] == $key) ? 'selected' : ''}}>{{$template}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Color Input')}}</label>
                                            <div class="row gutters-xs">
                                                @foreach(Utility::templateData()['colors'] as $key => $color)
                                                    <div class="col-auto">
                                                        <label class="colorinput">
                                                            <input name="bill_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['bill_color']) && $settings['bill_color'] == $color) ? 'checked' : ''}}>
                                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">{{__('Bill Logo')}}</label>
                                            <div class="choose-files mt-5 ">
                                                <label for="bill_logo">
                                                    <div class=" bg-primary bill_logo_update"> <i class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                                    <img id="blah7" class="mt-3" src=""  width="70%"  />
                                                    <input type="file" class="form-control file" name="bill_logo" id="bill_logo" data-filename="bill_logo_update" onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                                    <!-- <input type="file" class="form-control file" name="bill_logo" id="bill_logo" data-filename="bill_logo_update"> -->
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2 text-end">
                                            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if(isset($settings['bill_template']) && isset($settings['bill_color']))
                                    <iframe id="bill_frame" class="w-100 h-100" frameborder="0" src="{{route('bill.preview',[$settings['bill_template'],$settings['bill_color']])}}"></iframe>
                                @else
                                    <iframe id="bill_frame" class="w-100 h-100" frameborder="0" src="{{route('bill.preview',['template1','fffff'])}}"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>


                </div> --}}

                <!--Payment Setting-->
                {{-- <div id="useradd-7" class="card">
                    <div class="card-header">
                        <h5>{{ __('Payment Setting') }}</h5>
                        <small class="text-muted">{{ __('This detail will use for collect payment on invoice from clients. On invoice client will find out pay now button based on your below configuration.') }}</small>
                    </div>
                    <div class="card-body">
                            {{Form::model($settings,['route'=>'company.payment.settings', 'method'=>'POST'])}}

                            @csrf

                            <div class="faq justify-content-center">
                                <div class="col-sm-12 col-md-10 col-xxl-12">
                                    <div class="accordion accordion-flush" id="accordionExample">

                                        <!-- Strip -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-2">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Stripe') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse"aria-labelledby="heading-2-2"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">

                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_stripe_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($company_payment_setting['is_stripe_enabled']) && $company_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label" for="is_stripe_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="stripe_key" class="col-form-label">{{__('Stripe Key')}}</label>
                                                                <input class="form-control" placeholder="{{__('Stripe Key')}}" name="stripe_key" type="text" value="{{(!isset($company_payment_setting['stripe_key']) || is_null($company_payment_setting['stripe_key'])) ? '' : $company_payment_setting['stripe_key']}}" id="stripe_key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="stripe_secret" class="col-form-label">{{__('Stripe Secret')}}</label>
                                                                <input class="form-control " placeholder="{{ __('Stripe Secret') }}" name="stripe_secret" type="text" value="{{(!isset($company_payment_setting['stripe_secret']) || is_null($company_payment_setting['stripe_secret'])) ? '' : $company_payment_setting['stripe_secret']}}" id="stripe_secret">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Paypal -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-3">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Paypal') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse"aria-labelledby="heading-2-3"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>



                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_paypal_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_paypal_enabled" id="is_paypal_enabled"  {{ isset($company_payment_setting['is_paypal_enabled']) && $company_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label" for="is_paypal_enabled">{{__('Enable ')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="paypal-label col-form-label" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark {{isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                                <input type="radio" name="paypal_mode" value="sandbox" class="form-check-input" {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{__('Sandbox')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="paypal_mode" value="live" class="form-check-input" {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>

                                                                                {{__('Live')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id" class="col-form-label">{{ __('Client ID') }}</label>
                                                                <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{(!isset($company_payment_setting['paypal_client_id']) || is_null($company_payment_setting['paypal_client_id'])) ? '' : $company_payment_setting['paypal_client_id']}}" placeholder="{{ __('Client ID') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{(!isset($company_payment_setting['paypal_secret_key']) || is_null($company_payment_setting['paypal_secret_key'])) ? '' : $company_payment_setting['paypal_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paystack -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-4">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Paystack') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse3" class="accordion-collapse collapse"aria-labelledby="heading-2-4"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_paystack_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_paystack_enabled" id="is_paystack_enabled" {{(isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_paystack_enabled">{{__('Enable ')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id" class="col-form-label">{{ __('Public Key')}}</label>
                                                                <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{(!isset($company_payment_setting['paystack_public_key']) || is_null($company_payment_setting['paystack_public_key'])) ? '' : $company_payment_setting['paystack_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{(!isset($company_payment_setting['paystack_secret_key']) || is_null($company_payment_setting['paystack_secret_key'])) ? '' : $company_payment_setting['paystack_secret_key']}}" placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- FLUTTERWAVE -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-5">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Flutterwave') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse4" class="accordion-collapse collapse"aria-labelledby="heading-2-5"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_flutterwave_enabled">{{__('Enable ')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id" class="col-form-label">{{ __('Public Key')}}</label>
                                                                <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{(!isset($company_payment_setting['flutterwave_public_key']) || is_null($company_payment_setting['flutterwave_public_key'])) ? '' : $company_payment_setting['flutterwave_public_key']}}" placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{(!isset($company_payment_setting['flutterwave_secret_key']) || is_null($company_payment_setting['flutterwave_secret_key'])) ? '' : $company_payment_setting['flutterwave_secret_key']}}" placeholder="Secret Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Razorpay -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-6">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Razorpay') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse5" class="accordion-collapse collapse"aria-labelledby="heading-2-6"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label" for="is_razorpay_enabled">{{__('Enable ')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id" class="col-form-label">{{__('Public Key')}}</label>

                                                                <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{(!isset($company_payment_setting['razorpay_public_key']) || is_null($company_payment_setting['razorpay_public_key'])) ? '' : $company_payment_setting['razorpay_public_key']}}" placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key" class="col-form-label">{{__('Secret Key')}}</label>
                                                                <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{(!isset($company_payment_setting['razorpay_secret_key']) || is_null($company_payment_setting['razorpay_secret_key'])) ? '' : $company_payment_setting['razorpay_secret_key']}}" placeholder="Secret Key">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- Mercado Pago-->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-8">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="true" aria-controls="collapse7">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Mercado Pago') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse7" class="accordion-collapse collapse"aria-labelledby="heading-2-8"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_mercado_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_mercado_enabled" id="is_mercado_enabled" {{(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_mercado_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 ">
                                                            <label class="coingate-label col-form-label" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="mercado_mode" value="sandbox" class="form-check-input" {{ isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == '' || isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                {{__('Sandbox')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="mercado_mode" value="live" class="form-check-input" {{ isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{__('Live')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mercado_access_token" class="col-form-label">{{ __('Access Token') }}</label>
                                                                <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($company_payment_setting['mercado_access_token']) ? $company_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>
                                                                @if ($errors->has('mercado_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mercado_access_token') }}
                                                                        </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paytm -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-7">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Paytm') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse6" class="accordion-collapse collapse"aria-labelledby="heading-2-7"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>

                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_paytm_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label" for="is_paytm_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="paypal-label col-form-label" for="paypal_mode">{{__('Paytm Environment')}}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">

                                                                                <input type="radio" name="paytm_mode" value="local" class="form-check-input" {{ !isset($company_payment_setting['paytm_mode']) || $company_payment_setting['paytm_mode'] == '' || $company_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>

                                                                                {{__('Local')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="paytm_mode" value="production" class="form-check-input" {{ isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>

                                                                                {{__('Production')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_public_key" class="col-form-label">{{__('Industry Type')}}</label>
                                                                <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{(!isset($company_payment_setting['paytm_merchant_id']) || is_null($company_payment_setting['paytm_merchant_id'])) ? '' : $company_payment_setting['paytm_merchant_id']}}" placeholder="Merchant ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key" class="col-form-label">{{__('Merchant Key')}}</label>
                                                                <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{(!isset($company_payment_setting['paytm_merchant_key']) || is_null($company_payment_setting['paytm_merchant_key'])) ? '' : $company_payment_setting['paytm_merchant_key']}}" placeholder="Merchant Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_industry_type" class="col-form-label">{{__('Industry Type')}}</label>
                                                                <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{(!isset($company_payment_setting['paytm_industry_type']) || is_null($company_payment_setting['paytm_industry_type'])) ? '' : $company_payment_setting['paytm_industry_type']}}" placeholder="Industry Type">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mollie -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-9">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Mollie') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse8" class="accordion-collapse collapse"aria-labelledby="heading-2-9"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_mollie_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_mollie_enabled" id="is_mollie_enabled" {{(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_mollie_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key" class="col-form-label">{{ __('Mollie Api Key') }}</label>
                                                                <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{(!isset($company_payment_setting['mollie_api_key']) || is_null($company_payment_setting['mollie_api_key'])) ? '' : $company_payment_setting['mollie_api_key']}}" placeholder="Mollie Api Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_profile_id" class="col-form-label">{{ __('Mollie Profile Id') }}</label>
                                                                <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{(!isset($company_payment_setting['mollie_profile_id']) || is_null($company_payment_setting['mollie_profile_id'])) ? '' : $company_payment_setting['mollie_profile_id']}}" placeholder="Mollie Profile Id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_partner_id" class="col-form-label">{{ __('Mollie Partner Id') }}</label>
                                                                <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{(!isset($company_payment_setting['mollie_partner_id']) || is_null($company_payment_setting['mollie_partner_id'])) ? '' : $company_payment_setting['mollie_partner_id']}}" placeholder="Mollie Partner Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Skrill -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-10">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('Skrill') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse9" class="accordion-collapse collapse"aria-labelledby="heading-2-10"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_skrill_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_skrill_enabled" id="is_skrill_enabled" {{(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_skrill_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key" class="col-form-label">{{__('Skrill Email')}}</label>
                                                                <input type="text" name="skrill_email" id="skrill_email" class="form-control" value="{{(!isset($company_payment_setting['skrill_email']) || is_null($company_payment_setting['skrill_email'])) ? '' : $company_payment_setting['skrill_email']}}" placeholder="Enter Skrill Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CoinGate -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-11">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('CoinGate') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse10" class="accordion-collapse collapse"aria-labelledby="heading-2-11"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_coingate_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_coingate_enabled" id="is_coingate_enabled" {{(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_coingate_enabled">{{__('Enable')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="col-form-label" for="coingate_mode">{{__('CoinGate Mode')}}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">

                                                                                <input type="radio" name="coingate_mode" value="sandbox" class="form-check-input" {{ !isset($company_payment_setting['coingate_mode']) || $company_payment_setting['coingate_mode'] == '' || $company_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{__('Sandbox')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="coingate_mode" value="live" class="form-check-input" {{ isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{__('Live')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="coingate_auth_token" class="col-form-label">{{__('CoinGate Auth Token')}}</label>
                                                                <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{(!isset($company_payment_setting['coingate_auth_token']) || is_null($company_payment_setting['coingate_auth_token'])) ? '' : $company_payment_setting['coingate_auth_token']}}" placeholder="CoinGate Auth Token">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PaymentWall -->
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header" id="heading-2-12">
                                                <button class="accordion-button"  type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-credit-card text-primary"></i> {{ __('PaymentWall') }}
                                                        </span>
                                                </button>
                                            </h2>
                                            <div id="collapse11" class="accordion-collapse collapse"aria-labelledby="heading-2-12"data-bs-parent="#accordionExample" >
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                        </div>
                                                        <div class="col-6 py-2 text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{(isset($company_payment_setting['is_paymentwall_enabled']) && $company_payment_setting['is_paymentwall_enabled'] == 'on') ? 'checked' : ''}}>
                                                                <label class="custom-control-label form-label" for="is_paymentwall_enabled">{{__('Enable ')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paymentwall_public_key" class="col-form-label">{{ __('Public Key')}}</label>
                                                                <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{(!isset($company_payment_setting['paymentwall_public_key']) || is_null($company_payment_setting['paymentwall_public_key'])) ? '' : $company_payment_setting['paymentwall_public_key']}}" placeholder="{{ __('Public Key')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paymentwall_secret_key" class="col-form-label">{{ __('Private Key') }}</label>
                                                                <input type="text" name="paymentwall_secret_key" id="paymentwall_secret_key" class="form-control" value="{{(!isset($company_payment_setting['paymentwall_secret_key']) || is_null($company_payment_setting['paymentwall_secret_key'])) ? '' : $company_payment_setting['paymentwall_secret_key']}}" placeholder="{{ __('Private Key') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                                </div>
                            </div>
                        </form>
                    </div>


                </div> --}}

                <!--Twilio Setting-->
                {{-- <div id="useradd-8" class="card">
                    <div class="card-header">
                        <h5>{{ __('Twilio Setting') }}</h5>
                        <small class="text-muted">{{ __('Edit details about your Company twilio setting') }}</small>
                    </div>

                    <div class="card-body">
                        {{Form::model($settings,array('route'=>'twilio.settings','method'=>'post'))}}
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('twilio_sid',__('Twilio SID '),array('class'=>'form-label')) }}
                                    {{ Form::text('twilio_sid', isset($settings['twilio_sid']) ?$settings['twilio_sid'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio SID'), 'required' => 'required']) }}
                                    @error('twilio_sid')
                                    <span class="invalid-twilio_sid" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('twilio_token',__('Twilio Token'),array('class'=>'form-label')) }}
                                    {{ Form::text('twilio_token', isset($settings['twilio_token']) ?$settings['twilio_token'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required']) }}
                                    @error('twilio_token')
                                    <span class="invalid-twilio_token" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('twilio_from',__('Twilio From'),array('class'=>'form-label')) }}
                                    {{ Form::text('twilio_from', isset($settings['twilio_from']) ?$settings['twilio_from'] :'', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio From'), 'required' => 'required']) }}
                                    @error('twilio_from')
                                    <span class="invalid-twilio_from" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-12 mt-4 mb-2">
                                <h5 class="small-title">{{__('Module Setting')}}</h5>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Customer')}}</span>
                                            {{Form::checkbox('customer_notification', '1',isset($settings['customer_notification']) && $settings['customer_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'customer_notification'))}}
                                            <label class="form-check-label" for="customer_notification"></label>
                                        </div>

                                    </li>
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Vendor')}}</span>
                                            {{Form::checkbox('vender_notification', '1',isset($settings['vender_notification']) && $settings['vender_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'vender_notification'))}}
                                            <label class="form-check-label" for="vender_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Invoice')}}</span>
                                            {{Form::checkbox('invoice_notification', '1',isset($settings['invoice_notification']) && $settings['invoice_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'invoice_notification'))}}
                                            <label class="form-check-label" for="invoice_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Revenue')}}</span>
                                            {{Form::checkbox('revenue_notification', '1',isset($settings['revenue_notification']) && $settings['revenue_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'revenue_notification'))}}
                                            <label class="form-check-label" for="revenue_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Bill')}}</span>
                                            {{Form::checkbox('bill_notification', '1',isset($settings['bill_notification']) && $settings['bill_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'bill_notification'))}}
                                            <label class="form-check-label" for="bill_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Proposal')}}</span>
                                            {{Form::checkbox('proposal_notification', '1',isset($settings['proposal_notification']) && $settings['proposal_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'proposal_notification'))}}
                                            <label class="form-check-label" for="proposal_notification"></label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-4 mb-2">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Create Payment')}}</span>
                                            {{Form::checkbox('payment_notification', '1',isset($settings['payment_notification']) && $settings['payment_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'payment_notification'))}}
                                            <label class="form-check-label" for="payment_notification"></label>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class=" form-switch form-switch-right">
                                            <span>{{__('Invoice Reminder')}}</span>
                                            {{Form::checkbox('reminder_notification', '1',isset($settings['reminder_notification']) && $settings['reminder_notification'] == '1' ?'checked':'',array('class'=>'form-check-input','id'=>'reminder_notification'))}}
                                            <label class="form-check-label" for="reminder_notification"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>



                        </div>
                        <div class="card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                </div> --}}

                <!--Email Notification Setting-->
                {{-- <div id="useradd-9" class="card">
                    <!-- <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">  -->
                        <!-- @csrf -->
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('Email Notification') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- <div class=""> -->
                                        @foreach ($EmailTemplates as $EmailTemplate)
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                <div class="list-group">
                                                    <div class="list-group-item form-switch form-switch-right">
                                                        <label class="form-label" style="margin-left:5%;">{{ $EmailTemplate->name }}</label>
                                                       
                                                        <input class="form-check-input email-template-checkbox" id="email_tempalte_{{!empty($EmailTemplate->template)?$EmailTemplate->template->id:''}}" type="checkbox"
                                                        @if(!empty($EmailTemplate->template)?$EmailTemplate->template->is_active:0 == 1) checked="checked" @endif type="checkbox" value="{{!empty($EmailTemplate->template)?$EmailTemplate->template->is_active:1}}"
                                                        data-url="{{route('status.email.language',[!empty($EmailTemplate->template)?$EmailTemplate->template->id:''])}}" />
                                                        <label class="form-check-label" for="email_tempalte_{{!empty($EmailTemplate->template)?$EmailTemplate->template->id:''}}"></label>

                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    <!-- </div> -->
                                </div>
                                <!-- <div class="card-footer p-0">
                                    <div class="col-sm-12 mt-3 px-2">
                                        <div class="text-end">
                                            <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{__('Save Changes')}}">
                                        </div>
                                    </div>

                                </div> -->
                            </div>
                        </div>
                    <!-- </form>  -->
                </div> --}}
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection



@push('script-page')

    <script>
        $(document).on('click', 'input[name="theme_color"]', function () {
            var eleParent = $(this).attr('data-theme');
            $('#themefile').val(eleParent);
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });

        $(document).ready(function () {
            setTimeout(function (e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });

        function check_theme(color_val) {

            $('.theme-color').prop('checked', false);
            $('input[value="'+color_val+'"]').prop('checked', true);
            $('#color_value').val(color_val);
        }
    </script>
@endpush