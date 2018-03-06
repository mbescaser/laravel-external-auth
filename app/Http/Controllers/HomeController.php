<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $data = [];
        dd([
            Auth::user(),
            $this->request->session()->all()
        ]);
        // return response()->view('home', $data);
    }

    public function attempt() {
        Auth::attempt(['email' => 'melvicbescaser@gmail.com', 'password' => 'frozenheaven123']);
        $this->request->session()->flash('message', 'attempt');
        return redirect()->intended('/');
    }

    public function logout() {
        // $this->request->session()->forget(Auth::user()->userId);
        $this->request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

}
