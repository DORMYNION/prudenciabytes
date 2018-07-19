<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Sessions\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Sessions\Requests\SessionRequest;

class SessionController extends Controller
{
    public function login()
    {
        deleteTempFiles();
        deleteViewCache();

        return view('sessions.login')->with('skin', config('fi.skin'));
    }

    public function attempt(SessionRequest $request)
    {
        $rememberMe = ($request->input('remember_me')) ? true : false;

        if (!auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $rememberMe)) {
            return redirect()->route('session.login')->with('error', trans('fi.invalid_credentials'));
        }

        if (!auth()->user()->client_id) {
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('clientCenter.dashboard');

    }

    public function logout()
    {
        auth()->logout();

        session()->flush();

        return redirect()->route('session.login');
    }
}