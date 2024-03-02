<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use App\Models\Vender;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    // public function showLoginForm()
    // {
    //     return view('auth.login');
    // }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */

    public function __construct()
    {
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        // return view('auth.login');
    }

    public function username()
    {
        return 'email';
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {

        //ReCpatcha
        if (env('RECAPTCHA_MODULE') == 'yes') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->delete_status == 0) {
            auth()->logout();
        }

        if ($user->is_active == 0) {
            auth()->logout();
        }

        if ($user->type == 'company') {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            if ($user->plan != $free_plan->id) {
                if (date('Y-m-d') > $user->plan_expire_date) {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $users     = User::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $venders   = Vender::where('created_by', '=', \Auth::user()->creatorId())->get();

                    if ($free_plan->max_users == -1) {
                        foreach ($users as $user) {
                            $user->is_active = 1;
                            $user->save();
                        }
                    } else {
                        $userCount = 0;
                        foreach ($users as $user) {
                            $userCount++;
                            if ($userCount <= $free_plan->max_users) {
                                $user->is_active = 1;
                                $user->save();
                            } else {
                                $user->is_active = 0;
                                $user->save();
                            }
                        }
                    }

                    if ($free_plan->max_customers == -1) {
                        foreach ($customers as $customer) {
                            $customer->is_active = 1;
                            $customer->save();
                        }
                    } else {
                        $customerCount = 0;
                        foreach ($customers as $customer) {
                            $customerCount++;
                            if ($customerCount <= $free_plan->max_customers) {
                                $customer->is_active = 1;
                                $customer->save();
                            } else {
                                $customer->is_active = 0;
                                $customer->save();
                            }
                        }
                    }

                    if ($free_plan->max_venders == -1) {
                        foreach ($venders as $vender) {
                            $vender->is_active = 1;
                            $vender->save();
                        }
                    } else {
                        $venderCount = 0;
                        foreach ($venders as $vender) {
                            $venderCount++;
                            if ($venderCount <= $free_plan->max_venders) {
                                $vender->is_active = 1;
                                $vender->save();
                            } else {
                                $vender->is_active = 0;
                                $vender->save();
                            }
                        }
                    }
                    return redirect()->route('dashboard')->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showCustomerLoginForm($lang = '')
    {
        echo "ini halaman user";
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function customerLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]
        );

        if (\Auth::guard('customer')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
            $request->get('remember')
        )) {
            if (\Auth::guard('customer')->user()->is_active == 0) {
                \Auth::guard('customer')->logout();
            }

            return redirect()->route('customer.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginFormAdmin($lang = '')
    {
        echo "Login Admin";
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.login');
    }

    public function showCustomerLoginLang($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function getCustomerPassword($token)
    {
        return view('auth.customerReset', ['token' => $token]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('These credentials do not match our records.')],
        ]);
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
}
