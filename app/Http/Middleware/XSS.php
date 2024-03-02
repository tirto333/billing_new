<?php

namespace App\Http\Middleware;

use App\Models\Utility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (\Auth::check()) {

        //     \App::setLocale(\Auth::user()->lang);

        //     // if (\Auth::user()->type == 'super admin') {
        //     //     // $migrations             = $this->getMigra();
        //     //     // $dbMigrations           = $this->getExecutedMigrations();
        //     //     // $numberOfUpdatesPending = count($migrations) - count($dbMigrations);

        //     //     // if ($numberOfUpdatesPending > 0) {
        //     //     //     Utility::addNewData();
        //     //     //     return redirect()->route('LaravelUpdater::welcome');
        //     //     // }
        //     // }
        // }

        // $input = $request->all();
        // $request->merge($input);
        return $next($request);
    }
}
