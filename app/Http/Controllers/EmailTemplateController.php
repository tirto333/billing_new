<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;
use App\Models\Utility;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $usr = \Auth::user();

        if ($usr->type == 'super admin' || $usr->type == 'company') {
            $EmailTemplates = EmailTemplate::all();

            return view('settings.company', compact('EmailTemplates'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function manageEmailLang($id, $lang = 'en')
    {

        if (\Auth::user()->type == 'super admin') {
            $languages         = Utility::languages();
            $emailTemplate     = EmailTemplate::first();
            // $currEmailTempLang = EmailTemplateLang::where('lang', $lang)->first();
            $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();
            if (!isset($currEmailTempLang) || empty($currEmailTempLang)) {
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();

                $currEmailTempLang->lang = $lang;
            }

            if (\Auth::user()->type == 'super admin') {
                $emailTemplate     = EmailTemplate::where('id', '=', $id)->first();
            } else {

                $settings         = Utility::settings();
                $emailTemplate     = $settings['company_name'];
            }
            $EmailTemplates = EmailTemplate::all();
            return view('email_templates.show', compact('emailTemplate', 'languages', 'currEmailTempLang', 'EmailTemplates'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $usr = \Auth::user();
        if ($usr->type == 'super admin' || $usr->type == 'company') {
            $user_email = UserEmailTemplate::where('id', '=', $id)->where('user_id', '=', $usr->id)->first();
            if (!empty($user_email)) {
                if ($request->status == 1) {
                    $user_email->is_active = 0;
                } else {
                    $user_email->is_active = 1;
                }

                $user_email->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Status successfully updated!'),
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        }
    }
}
