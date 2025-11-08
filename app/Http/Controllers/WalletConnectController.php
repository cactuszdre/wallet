<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletConnectController extends Controller
{
    /**
     * Display the WalletConnect page.
     */
    public function index()
    {
        return view('walletconnect.index');
    }
}
