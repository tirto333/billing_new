<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CustomerContoller;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\ProductServiceCategoryController;
use App\Http\Controllers\ProductServiceController;
use App\Http\Controllers\ProductServiceUnitController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RetainerController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\RoleContoller;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenderController;
use App\Http\Controllers\WaController;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/asd/{id}/show', [WaController::class, 'customerInvoiceShowNoAuth'])->name('invoices.show');
// Route::middleware('guest')->group(function () {
// });

require __DIR__ . '/auth.php';


Route::get('/register', function () {
    $settings = Utility::settings();
    $lang = $settings['default_language'];

    if ($settings['enable_signup'] == 'on') {
        return view("auth.register", compact('lang'));
        // Route::get('/register', 'Auth\RegisteredUserController@showRegistrationForm')->name('register');
    } else {
        return Redirect::to('login');
    }
});

// Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS']);
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginFormAdmin'])->name('login');

// CONTRACT
Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('contract', ContractController::class);
        // Route::get('contract/duplicate/{id}', 'ContractController@duplicate')->name('contract.duplicate')->middleware(['auth','XSS']);
        // Route::put('contract/duplicatecontract/{id}', 'ContractController@duplicatecontract')->name('contract.duplicatecontract')->middleware(['auth','XSS']);
        // Route::post('contract/{id}/description', 'ContractController@descriptionStore')->name('contract.description.store')->middleware(['auth','XSS']);
        // Route::post('contract/{id}/file', 'ContractController@fileUpload')->name('contract.file.upload')->middleware(['auth','XSS']);
        // Route::get('/contract/{id}/file/{fid}','ContractController@fileDownload')->name('contract.file.download')->middleware(['auth','XSS']);
        // Route::delete('/contract/{id}/file/delete/{fid}','ContractController@fileDelete')->name('contract.file.delete')->middleware(['auth','XSS']);
        // Route::post('/contract/{id}/comment','ContractController@commentStore')->name('comment.store')->middleware(['auth','XSS']);
        // Route::get('/contract/{id}/comment','ContractController@commentDestroy')->name('comment.destroy')->middleware(['auth','XSS']);  
        // Route::post('/contract/{id}/note', 'ContractController@noteStore')->name('contract.note.store')->middleware(['auth','XSS']);
        // Route::get('/contract/{id}/note', 'ContractController@noteDestroy')->name('contract.note.destroy')->middleware(['auth','XSS']);
        // Route::get('contract/pdf/{id}', 'ContractController@pdffromcontract')->name('contract.download.pdf')->middleware(['auth','XSS']);
        // Route::get('contract/{id}/get_contract', 'ContractController@printContract')->name('get.contract')->middleware(['auth','XSS']);
        // Route::get('/signature/{id}', 'ContractController@signature')->name('signature')->middleware(['auth','XSS']);
        // Route::post('/signaturestore', 'ContractController@signatureStore')->name('signaturestore')->middleware(['auth','XSS']);
        // Route::get('/contract/{id}/mail','ContractController@sendmailContract')->name('send.mail.contract')->middleware(['auth','XSS']);
    }
);

// RETAINER

Route::resource('retainer', RetainerController::class);
Route::post('/retainer/template/setting', ['as' => 'retainer.template.setting', 'uses' => [RetainerController::class, 'saveRetainerTemplateSettings']]);
Route::get('/retainer/preview/{template}/{color}', ['as' => 'retainer.preview', 'uses' => [RetainerController::class, 'previewRetainer']]);

// EMAIL TEMPLATE
Route::resource('email_template', EmailTemplateController::class)->middleware(['auth', 'XSS']);
Route::post('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware(['auth']);

// CUSTOMER
Route::prefix('customer')->as('customer.')->group(
    function () {
        Route::get('login/{lang}', [AuthenticatedSessionController::class, 'showCustomerLoginLang'])->name('login.lang')->middleware(['XSS']);
        Route::get('login', [AuthenticatedSessionController::class, 'showCustomerLoginForm'])->name('login')->middleware(['XSS']);
        Route::post('login', [AuthenticatedSessionController::class, 'customerLogin'])->name('login')->middleware(['XSS']);
        Route::get('/password/resets/{lang?}', [AuthenticatedSessionController::class, 'showCustomerLinkRequestForm'])->name('change.langPass');
        // Route::post('/password/email', 'Auth\AuthenticatedSessionController@postCustomerEmail')->name('password.email');

        // Route::get('reset-password/{token}', 'Auth\AuthenticatedSessionController@getCustomerPassword')->name('
        // ');
        // Route::post('reset-password', 'Auth\AuthenticatedSessionController@updateCustomerPassword')->name('password.reset');

        // //================================= Retainer  ====================================//

        Route::get('retainer', [RetainerController::class, 'customerRetainer'])->name('retainer')->middleware(['auth:customer','XSS']);

        // Route::get('retainer/{id}/show', 'RetainerController@customerRetainerShow')->name('retainer.show')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::get('retainer/{id}/send', 'RetainerController@customerRetainerSend')->name('retainer.send')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::post('retainer/{id}/send/mail', 'RetainerController@customerRetainerSendMail')->name('retainer.send.mail')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::post('retainer/{id}/payment', 'StripePaymentController@addretainerpayment')->name('retainer.payment')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        Route::get('dashboard', [CustomerContoller::class, 'dashboard'])->name('dashboard')->middleware(['auth:customer', 'XSS']);

        Route::get('invoice', [InvoiceController::class, 'customerInvoice'])->name('invoice')->middleware(['auth:customer','XSS']);
        // Route::get(
        //     '/invoice/pay/{invoice}', [
        //            'as' => 'pay.invoice',
        //            'uses' => 'InvoiceController@payinvoice',
        //        ]
        // );
        Route::get('proposal', [ProposalController::class, 'customerProposal'])->name('proposal')->middleware(['auth:customer','XSS']);

        // Route::get('proposal/{id}/show', 'ProposalController@customerProposalShow')->name('proposal.show')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        Route::get('invoice/{id}/send', [InvoiceController::class, 'customerInvoiceSend'])->name('invoice.send')->middleware(['auth:customer','XSS']);

        // Route::post('invoice/{id}/send/mail', 'InvoiceController@customerInvoiceSendMail')->name('invoice.send.mail')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        Route::get('invoice/{id}/show', [InvoiceController::class, 'customerInvoiceShow'])->name('invoice.show')->middleware(['auth:customer','XSS']);

        // Route::get('invoice/{id}/show', 'InvoiceController@customerInvoiceShow')->name('invoice.view')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::post('invoice/{id}/payment', 'StripePaymentController@addpayment')->name('invoice.payment')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::post('retainer/{id}/payment', 'StripePaymentController@addretainerpayment')->name('retainer.payment')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        Route::get('payment', [CustomerContoller::class, 'payment'])->name('payment')->middleware(['auth:customer','XSS']);
        Route::get('transaction', [CustomerContoller::class, 'transaction'])->name('transaction')->middleware(['auth:customer','XSS']);
        Route::post('logout', [CustomerContoller::class, 'customerLogout'])->name('logout')->middleware(['auth:customer','XSS']);
        Route::get('profile', [CustomerContoller::class, 'profile'])->name('profile')->middleware(['auth:customer','XSS']);

        // Route::post('update-profile', 'CustomerController@editprofile')->name('update.profile')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );
        // Route::post('billing-info', 'CustomerController@editBilling')->name('update.billing.info')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );
        // Route::post('shipping-info', 'CustomerController@editShipping')->name('update.shipping.info')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );
        // Route::post('change.password', 'CustomerController@updatePassword')->name('update.password')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        Route::get('change-language/{lang}', [CustomerContoller::class, 'changeLanquage'])->name('change.language')->middleware(['auth:customer','XSS']);

        // //================================= contract ====================================//

        // Route::resource('contract', 'ContractController')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );

        // Route::post('contract/{id}/description', 'ContractController@descriptionStore')->name('contract.description.store')->middleware(
        //     [
        //         'auth:customer',
        //         'XSS','revalidate',
        //     ]
        // );
        // Route::post('contract/{id}/file', 'ContractController@fileUpload')->name('contract.file.upload')->middleware(['auth:customer','XSS']);
        // Route::post('/contract/{id}/comment','ContractController@commentStore')->name('comment.store')->middleware(['auth:customer','XSS']);
        // Route::post('/contract/{id}/note', 'ContractController@noteStore')->name('contract.note.store')->middleware(['auth:customer','XSS']);
        // Route::get('contract/pdf/{id}', 'ContractController@pdffromcontract')->name('contract.download.pdf')->middleware(['auth:customer','XSS']);
        // Route::get('contract/{id}/get_contract', 'ContractController@printContract')->name('get.contract')->middleware(['auth:customer','XSS']);
        // Route::get('/signature/{id}', 'ContractController@signature')->name('signature')->middleware(['auth:customer','XSS']);
        // Route::post('/signaturestore', 'ContractController@signatureStore')->name('signaturestore')->middleware(['auth:customer','XSS']);
        // Route::get('contract/pdf/{id}', 'ContractController@pdffromcontract')->name('contract.download.pdf')->middleware(['auth:customer','XSS']);
        // Route::delete('/contract/{id}/file/delete/{fid}','ContractController@fileDelete')->name('contract.file.delete')->middleware(['auth:customer','XSS']);
        // Route::get('/contract/{id}/comment','ContractController@commentDestroy')->name('comment.destroy')->middleware(['auth:customer','XSS']);  
        // Route::get('/contract/{id}/note', 'ContractController@noteDestroy')->name('contract.note.destroy')->middleware(['auth:customer','XSS']);

        // //================================= Invoice Payment Gateways  ====================================//

        // Route::post('/paymentwall' , ['as' => 'invoice.paymentwallpayment','uses' =>'PaymentWallPaymentController@invoicepaymentwall'])->middleware(['XSS','auth:customer']);

        // Route::post('{id}/invoice-with-paypal', 'PaypalController@customerPayWithPaypal')->name('invoice.with.paypal')->middleware(
        //     [
        //         'XSS',
        //         'revalidate',
        //     ]
        // );

        // Route::post('{id}/pay-with-paypal', 'PaypalController@customerretainerPayWithPaypal')->name('pay.with.paypal')->middleware(
        //     [
        //         'XSS',
        //         'revalidate',
        //     ]
        // );

        // Route::get('{id}/get-retainer-payment-status', 'PaypalController@customerGetRetainerPaymentStatus')->name('get.retainer.payment.status')->middleware(
        //     [
        //         'XSS:customer',
        //         'revalidate',
        //     ]
        // );

        // Route::get('{id}/get-payment-status', 'PaypalController@customerGetPaymentStatus')->name('get.payment.status')->middleware(
        //     [
        //         'XSS',
        //         'revalidate',
        //     ]
        // );

        // Route::post('invoice/{id}/payment', 'StripePaymentController@addpayment')->name('invoice.payment')->middleware(
        //     [
        //         'XSS',
        //         'revalidate',
        //     ]
        // );

        // Route::post('/retainer-pay-with-paystack',['as' => 'retainer.pay.with.paystack','uses' =>'PaystackPaymentController@RetainerPayWithPaystack'])->middleware(['XSS:customer']);
        // Route::any('/retainer/paystack/{pay_id}/{retainer_id}', ['as' => 'retainer.paystack','uses' => 'PaystackPaymentController@getRetainerPaymentStatus'])->middleware(['XSS:customer']);

        // Route::post('/invoice-pay-with-paystack',['as' => 'invoice.pay.with.paystack','uses' =>'PaystackPaymentController@invoicePayWithPaystack'])->middleware(['XSS']);
        // Route::any('/invoice/paystack/{pay_id}/{invoice_id}', ['as' => 'invoice.paystack','uses' => 'PaystackPaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-flaterwave',['as' => 'retainer.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@retainerPayWithFlutterwave'])->middleware(['XSS:customer']);
        // Route::get('/retainer/flaterwave/{txref}/{retainer_id}', ['as' => 'retainer.flaterwave','uses' => 'FlutterwavePaymentController@getRetainerPaymentStatus'])->middleware(['XSS']);

        // Route::post('/invoice-pay-with-flaterwave',['as' => 'invoice.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@invoicePayWithFlutterwave'])->middleware(['XSS']);
        // Route::get('/invoice/flaterwave/{txref}/{invoice_id}', ['as' => 'invoice.flaterwave','uses' => 'FlutterwavePaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-razorpay',['as' => 'retainer.pay.with.razorpay','uses' =>'RazorpayPaymentController@retainerPayWithRazorpay'])->middleware(['XSS']);
        // Route::get('/retainer/razorpay/{txref}/{retainer_id}', ['as' => 'retainer.razorpay','uses' => 'RazorpayPaymentController@getRetainerPaymentStatus']);


        // Route::post('/invoice-pay-with-razorpay',['as' => 'invoice.pay.with.razorpay','uses' =>'RazorpayPaymentController@invoicePayWithRazorpay'])->middleware(['XSS']);
        // Route::get('/invoice/razorpay/{txref}/{invoice_id}', ['as' => 'invoice.razorpay','uses' => 'RazorpayPaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-paytm',['as' => 'retainer.pay.with.paytm','uses' =>'PaytmPaymentController@retainerPayWithPaytm'])->middleware(['XSS']);
        // Route::post('/retainer/paytm/{retainer}/{amount}', ['as' => 'retainer.paytm','uses' => 'PaytmPaymentController@getRetainerPaymentStatus'])->middleware(['XSS','auth:customer']);


        // Route::post('/invoice-pay-with-paytm',['as' => 'invoice.pay.with.paytm','uses' =>'PaytmPaymentController@invoicePayWithPaytm'])->middleware(['XSS']);
        // Route::post('/invoice/paytm/{invoice}/{amount}', ['as' => 'invoice.paytm','uses' => 'PaytmPaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-mercado',['as' => 'retainer.pay.with.mercado','uses' =>'MercadoPaymentController@retainerPayWithMercado'])->middleware(['XSS']);
        // Route::any('/retainer/mercado/{retainer}', ['as' => 'retainer.mercado','uses' => 'MercadoPaymentController@getRetainerPaymentStatus']);
        // Route::any('/retainer/mercado/{retainer}', ['as' => 'retainer.mercado','uses' => 'MercadoPaymentController@getRetainerPaymentStatus'])->middleware(['XSS','auth:customer']);


        // Route::post('/invoice-pay-with-mercado',['as' => 'invoice.pay.with.mercado','uses' =>'MercadoPaymentController@invoicePayWithMercado'])->middleware(['XSS']);
        // Route::any('/invoice/mercado/{invoice}', ['as' => 'invoice.mercado','uses' => 'MercadoPaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-mollie',['as' => 'retainer.pay.with.mollie','uses' =>'MolliePaymentController@retainerPayWithMollie'])->middleware(['XSS']);
        // Route::get('/retainer/mollie/{invoice}/{amount}', ['as' => 'retainer.mollie','uses' => 'MolliePaymentController@getRetainerPaymentStatus']);

        // Route::post('/invoice-pay-with-mollie',['as' => 'invoice.pay.with.mollie','uses' =>'MolliePaymentController@invoicePayWithMollie'])->middleware(['XSS']);
        // Route::get('/invoice/mollie/{invoice}/{amount}', ['as' => 'invoice.mollie','uses' => 'MolliePaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-skrill',['as' => 'retainer.pay.with.skrill','uses' =>'SkrillPaymentController@retainerPayWithSkrill'])->middleware(['XSS']);
        // Route::get('/retainer/skrill/{retainer}/{amount}', ['as' => 'retainer.skrill','uses' => 'SkrillPaymentController@getRetainerPaymentStatus']);

        // Route::post('/invoice-pay-with-skrill',['as' => 'invoice.pay.with.skrill','uses' =>'SkrillPaymentController@invoicePayWithSkrill'])->middleware(['XSS']);
        // Route::get('/invoice/skrill/{invoice}/{amount}', ['as' => 'invoice.skrill','uses' => 'SkrillPaymentController@getInvoicePaymentStatus']);

        // Route::post('/retainer-pay-with-coingate',['as' => 'retainer.pay.with.coingate','uses' =>'CoingatePaymentController@retainerPayWithCoingate'])->middleware(['XSS']);
        // Route::get('/retainer/coingate/{retainer}/{amount}', ['as' => 'retainer.coingate','uses' => 'CoingatePaymentController@getRetainerPaymentStatus'])->middleware(['XSS']);

        // Route::post('/invoice-pay-with-coingate',['as' => 'invoice.pay.with.coingate','uses' =>'CoingatePaymentController@invoicePayWithCoingate'])->middleware(['XSS']);
        // Route::get('/invoice/coingate/{invoice}/{amount}', ['as' => 'invoice.coingate','uses' => 'CoingatePaymentController@getInvoicePaymentStatus'])->middleware(['XSS']);
    }
);

Route::get('kirim-aktifasi', [WaController::class, 'kirimaktifasi'])->name('kirim.aktifasi');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'XSS']);

Route::resource('users', UserController::class)->middleware(['auth', 'XSS']);

Route::resource('roles', RoleContoller::class)->middleware(['auth', 'XSS']);
Route::resource('permissions', PermissionController::class)->middleware(['auth', 'XSS']);
Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
Route::any('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('users.reset');
Route::post('user-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');

Route::resource('plans', PlanController::class)->middleware(['auth', 'XSS']);

Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth', 'XSS',]);
Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth', 'XSS',]);
Route::get('request_send/{id}', 'PlanRequestController@userRequest')->name('send.request')->middleware(['auth', 'XSS',]);
Route::get('request_response/{id}/{response}', 'PlanRequestController@acceptRequest')->name('response.request')->middleware(['auth', 'XSS',]);
Route::get('request_cancel/{id}', 'PlanRequestController@cancelRequest')->name('request.cancel')->middleware(['auth', 'XSS',]);

Route::get('productservice/index', [ProductServiceController::class, 'index'])->name('productservice.index');
Route::resource('productservice', ProductServiceController::class)->middleware(['auth', 'XSS']);

Route::resource('productstock', ProductStockController::class)->middleware(['auth', 'XSS']);
Route::get('export/productservice', [ProductServiceController::class, 'export'])->name('productservice.export');
Route::get('import/productservice/file', [ProductServiceController::class, 'importFile'])->name('productservice.file.import');

Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);
Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware(['auth', 'XSS']);

Route::resource('coupons', CouponController::class)->middleware(['auth', 'XSS']);
Route::get('order', [PaymentController::class, 'index'])->name('order.index');
Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware(['auth', 'XSS']);
Route::resource('systems', SystemController::class);
Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);
Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account')->middleware(['auth', 'XSS']);
Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
Route::any('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('users.reset');
Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);
// Route::get('kirim-whatsapp', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanguage'])->name('change.language');
        Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
        Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
        Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
        Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
        Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('systems', SystemController::class);
        Route::post('email-settings', [SystemController::class, 'saveEmailSettings'])->name('email.settings');
        // Route::post('company-settings', [SystemController::class, 'saveCompanySettings'])->name('company.settings');
        Route::post('stripe-settings', [SystemController::class, 'savePaymentSettings'])->name('payment.settings');
        Route::post('system-settings', [SystemController::class, 'saveSystemSettings'])->name('system.settings');
        Route::post('recaptcha-settings', ['as' => 'recaptcha.settings.store', 'uses' => [SystemController::class, 'recaptchaSettingStore']])->middleware(['auth', 'XSS']);
        Route::post('storage-settings', [SystemController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);

        Route::get('company-setting', [SystemController::class, 'companyIndex'])->name('company.setting');
        // Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');
        // Route::post('twilio-settings', 'SystemController@saveTwilioSettings')->name('twilio.settings');
        Route::post('company-payment-setting', [SystemController::class, 'saveCompanyPaymentSettings'])->name('company.payment.settings');
        Route::get('test-mail', [SystemController::class, 'testMail'])->name('test.mail');
        // Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');

    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('invoice/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
        // Route::get('invoice/{id}/shipping/print', 'InvoiceController@shippingDisplay')->name('invoice.shipping.print');
        Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
        Route::get('invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::post('invoice/product/destroy', [InvoiceController::class, 'productDestroy'])->name('invoice.product.destroy');
        Route::post('invoice/product', [InvoiceController::class, 'product'])->name('invoice.product');
        Route::post('invoice/customer', [InvoiceController::class, 'customer'])->name('invoice.customer');
        Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
        Route::get('invoice/{id}/resent', [InvoiceController::class, 'resent'])->name('invoice.resent');
        Route::get('invoice/{id}/payment', [InvoiceController::class, 'payment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment', [InvoiceController::class, 'createPayment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment/{pid}/destroy', [InvoiceController::class, 'paymentDestroy'])->name('invoice.payment.destroy');
        Route::get('invoice/items', [InvoiceController::class, 'items'])->name('invoice.items');

        Route::resource('invoice', InvoiceController::class);
        Route::get('invoice/create/{cid}', [InvoiceController::class, 'create'])->name('invoice.create');
    }
);

Route::get('/invoices/preview/{template}/{color}', ['as' => 'invoice.preview', 'uses' => [InvoiceController::class, 'previewInvoice']]);
Route::post('/invoices/template/setting', ['as' => 'invoice.template.setting', 'uses' => [InvoiceController::class, 'saveTemplateSettings']]);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        // Route::get('credit-note', 'CreditNoteController@index')->name('credit.note');
        Route::get('custom-credit-note', [CreditNoteController::class, 'customCreate'])->name('invoice.custom.credit.note');
        // Route::post('custom-credit-note', 'CreditNoteController@customStore')->name('invoice.custom.credit.note');
        // Route::get('credit-note/invoice', 'CreditNoteController@getinvoice')->name('invoice.get');
        Route::get('invoice/{id}/credit-note', [CreditNoteController::class, 'create'])->name('invoice.credit.note');
        Route::post('invoice/{id}/credit-note', 'CreditNoteController@store')->name('invoice.credit.note');
        // Route::get('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@edit')->name('invoice.edit.credit.note');
        // Route::post('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@update')->name('invoice.edit.credit.note');
        // Route::delete('invoice/{id}/credit-note/delete/{cn_id}', 'CreditNoteController@destroy')->name('invoice.delete.credit.note');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('systems', 'SystemController');
        // Route::post('email-settings', 'SystemController@saveEmailSettings')->name('email.settings');
        Route::post('company-settings', [SystemController::class, 'saveCompanySettings'])->name('company.settings');
        // Route::post('stripe-settings', 'SystemController@savePaymentSettings')->name('payment.settings');
        // Route::post('system-settings', 'SystemController@saveSystemSettings')->name('system.settings');
        // Route::post('recaptcha-settings',['as' => 'recaptcha.settings.store','uses' =>'SystemController@recaptchaSettingStore'])->middleware(['auth','XSS']);
        // Route::post('storage-settings',['as' => 'storage.setting.store','uses' =>'SystemController@storageSettingStore'])->middleware(['auth','XSS']);

        // Route::get('company-setting', 'SystemController@companyIndex')->name('company.setting');
        Route::post('business-setting', [SystemController::class, 'saveBusinessSettings'])->name('business.setting');
        // Route::post('twilio-settings', 'SystemController@saveTwilioSettings')->name('twilio.settings');
        // Route::post('company-payment-setting', 'SystemController@saveCompanyPaymentSettings')->name('company.payment.settings');
        // Route::get('test-mail', 'SystemController@testMail')->name('test.mail');
        // Route::post('test-mail', 'SystemController@testSendMail')->name('test.send.mail');

    }
);

Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf')->middleware(['XSS']);

// Route::group(
//     ['middleware' => ['auth', 'XSS'],],
//     function () {
//         Route::resource('customer', CustomerContoller::class);
//     }
// );

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('vender', VenderController::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {

        Route::resource('proposal', ProposalController::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('bank-account', BankAccountController::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {

        Route::get('transfer/index', [TransferController::class, 'index'])->name('transfer.index');
        Route::resource('transfer', TransferController::class);
    }
);

// Route::group(
//     ['middleware' => ['auth', 'XSS'],],
//     function () {
//         Route::get('invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
//     }
// );

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('credit-note', [CreditNoteController::class, 'index'])->name('credit.note');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('bill/index', [BillController::class, 'index'])->name('bill.index');
        Route::resource('bill', BillController::class);
    }
);

Route::post('/bill/template/setting', ['as' => 'bill.template.setting', 'uses' => [BillController::class, 'saveBillTemplateSettings']]);
Route::get('/bill/preview/{template}/{color}', ['as' => 'bill.preview', 'uses' => [BillController::class, 'previewBill']]);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('debit-note', [DebitNoteController::class, 'index'])->name('debit.note');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('chart-of-account', ChartOfAccountController::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        // Route::post('journal-entry/account/destroy', 'JournalEntryController@accountDestroy')->name('journal.account.destroy');
        Route::resource('journal-entry', JournalEntryController::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {

        Route::get('report/income-summary', [ReportController::class, 'incomeSummary'])->name('report.income.summary');
        Route::get('report/expense-summary', [ReportController::class, 'expenseSummary'])->name('report.expense.summary');
        Route::get('report/income-vs-expense-summary', [ReportController::class, 'incomeVsExpenseSummary'])->name('report.income.vs.expense.summary');
        Route::get('report/tax-summary', [ReportController::class, 'taxSummary'])->name('report.tax.summary');
        Route::get('report/profit-loss-summary', [ReportController::class, 'profitLossSummary'])->name('report.profit.loss.summary');
        Route::get('report/invoice-summary', [ReportController::class, 'invoiceSummary'])->name('report.invoice.summary');
        Route::get('report/bill-summary', [ReportController::class, 'billSummary'])->name('report.bill.summary');
        Route::get('report/product-stock-report', [ReportController::class, 'productStock'])->name('report.product.stock.report');


        // Route::get('report/invoice-report', 'ReportController@invoiceReport')->name('report.invoice');
        Route::get('report/account-statement-report', [ReportController::class, 'accountStatement'])->name('report.account.statement');
        Route::get('report/balance-sheet', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');
        Route::get('report/ledger', [ReportController::class, 'ledgerSummary'])->name('report.ledger');
        Route::get('report/trial-balance', [ReportController::class, 'trialBalanceSummary'])->name('trial.balance');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('report/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::get('customer/{id}/show', [CustomerContoller::class, 'show'])->name('customer.show');
        Route::ANY('customer/{id}/statement', [CustomerContoller::class, 'statement'])->name('customer.statement');
        Route::any('customer-reset-password/{id}', [CustomerContoller::class, 'customerPassword'])->name('customer.reset');
        Route::post('customer-reset-password/{id}', [CustomerContoller::class, 'customerPasswordReset'])->name('customer.password.update');
        Route::resource('customer', CustomerContoller::class);
    }
);

Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        // Route::get('credit-note', 'CreditNoteController@index')->name('credit.note');
        // Route::get('custom-credit-note', 'CreditNoteController@customCreate')->name('invoice.custom.credit.note');
        // Route::post('custom-credit-note', 'CreditNoteController@customStore')->name('invoice.custom.credit.note');
        Route::get('credit-note/invoice', [CreditNoteController::class, 'getinvoice'])->name('invoice.get');
        // Route::get('invoice/{id}/credit-note', 'CreditNoteController@create')->name('invoice.credit.note');
        // Route::post('invoice/{id}/credit-note', 'CreditNoteController@store')->name('invoice.credit.note');
        // Route::get('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@edit')->name('invoice.edit.credit.note');
        // Route::post('invoice/{id}/credit-note/edit/{cn_id}', 'CreditNoteController@update')->name('invoice.edit.credit.note');
        // Route::delete('invoice/{id}/credit-note/delete/{cn_id}', 'CreditNoteController@destroy')->name('invoice.delete.credit.note');
    }
);

Route::resource('taxes', TaxController::class)->middleware(['auth', 'XSS']);
Route::resource('product-category', ProductServiceCategoryController::class)->middleware(['auth', 'XSS']);
Route::resource('product-unit', ProductServiceUnitController::class)->middleware(['auth', 'XSS']);
Route::resource('custom-field', CustomFieldController::class)->middleware(['auth', 'XSS']);
Route::group(
    ['middleware' => ['auth', 'XSS'],],
    function () {
        Route::resource('contractType', ContractTypeController::class);
    }
);

Route::get('/proposal/preview/{template}/{color}', ['as' => 'proposal.preview', 'uses' => [ProposalController::class, 'previewProposal']]);
Route::post('/proposal/template/setting', ['as' => 'proposal.template.setting', 'uses' => [ProposalController::class, 'saveProposalTemplateSettings']]);

Route::resource('goal', GoalController::class)->middleware(['auth', 'XSS']);

Route::resource('account-assets', AssetController::class)->middleware(['auth', 'XSS']);

Route::resource('budget', BudgetController::class)->middleware(['auth', 'XSS']);

Route::get('payment/index', [PaymentController::class, 'index'])->name('payment.index')->middleware(['auth', 'XSS']);
Route::resource('payment', PaymentController::class)->middleware(['auth', 'XSS']);

Route::get('revenue/index', [RevenueController::class, 'index'])->name('revenue.index')->middleware(['auth', 'XSS']);
Route::resource('revenue', RevenueController::class)->middleware(['auth', 'XSS']);

Route::get('export/customer', [CustomerContoller::class, 'export'])->name('customer.export');
Route::get('import/customer/file', [CustomerContoller::class, 'importFile'])->name('customer.file.import');
Route::get('export/invoice', [InvoiceController::class, 'export'])->name('invoice.export');

Route::resource('expenses', ExpenseController::class)->middleware(['auth', 'XSS',]);

Route::get('/invoice/pay/{invoice}', ['as' => 'pay.invoice', 'uses' => [InvoiceController::class, 'payinvoice'],]);
