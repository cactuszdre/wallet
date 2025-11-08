<?php

namespace App\Http\Controllers;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected WalletRepository $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Display the home page.
     */
    public function index()
    {
        $stats = $this->walletRepository->getUserStats(Auth::id());
        $recentWallets = $this->walletRepository->getUserWallets(Auth::id());

        return view('home', compact('stats', 'recentWallets'));
    }
}
