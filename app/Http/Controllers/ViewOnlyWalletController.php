<?php

namespace App\Http\Controllers;

use App\Models\ViewOnlyWallet;
use App\Services\WalletService;
use App\Services\WalletStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewOnlyWalletController extends Controller
{
    protected WalletService $walletService;
    protected WalletStatisticsService $statisticsService;

    public function __construct(
        WalletService $walletService,
        WalletStatisticsService $statisticsService
    ) {
        $this->walletService = $walletService;
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display a listing of view-only wallets.
     */
    public function index()
    {
        $viewOnlyWallets = ViewOnlyWallet::where('user_id', Auth::id())
            ->with('statistics')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('view-only-wallets.index', compact('viewOnlyWallets'));
    }

    /**
     * Store a newly created view-only wallet.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|in:base,base-sepolia,ethereum,sepolia',
            'address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
            'description' => 'nullable|string|max:1000',
        ]);

        // Vérifier si l'adresse existe déjà pour cet utilisateur
        $existing = ViewOnlyWallet::where('user_id', Auth::id())
            ->where('address', strtolower($request->address))
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Cette adresse existe déjà dans vos wallets view-only.');
        }

        // Obtenir la balance initiale
        $balanceData = $this->walletService->getBalance($request->address, $request->network);
        $balance = $balanceData['success'] ? $balanceData['balance'] : 0;

        // Créer le wallet view-only
        $viewOnlyWallet = ViewOnlyWallet::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => strtolower($request->address),
            'network' => $request->network,
            'balance' => $balance,
            'description' => $request->description,
            'is_active' => true,
        ]);

        // Créer les statistiques en arrière-plan (optionnel)
        try {
            $this->statisticsService->updateStatistics($viewOnlyWallet);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer la création
            \Log::warning('Impossible de récupérer les statistiques pour le wallet view-only', [
                'wallet_id' => $viewOnlyWallet->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('view-only-wallets.show', $viewOnlyWallet->id)
            ->with('success', 'Wallet view-only ajouté avec succès!');
    }

    /**
     * Display the specified view-only wallet.
     */
    public function show(ViewOnlyWallet $viewOnlyWallet)
    {
        // Vérifier que le wallet appartient à l'utilisateur
        if ($viewOnlyWallet->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $viewOnlyWallet->load('statistics');

        return view('view-only-wallets.show', compact('viewOnlyWallet'));
    }

    /**
     * Update the specified view-only wallet.
     */
    public function update(Request $request, ViewOnlyWallet $viewOnlyWallet)
    {
        // Vérifier que le wallet appartient à l'utilisateur
        if ($viewOnlyWallet->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $viewOnlyWallet->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('view-only-wallets.show', $viewOnlyWallet->id)
            ->with('success', 'Wallet view-only mis à jour avec succès!');
    }

    /**
     * Remove the specified view-only wallet.
     */
    public function destroy(ViewOnlyWallet $viewOnlyWallet)
    {
        // Vérifier que le wallet appartient à l'utilisateur
        if ($viewOnlyWallet->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $viewOnlyWallet->delete();

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet view-only supprimé avec succès!');
    }

    /**
     * Refresh balance from blockchain.
     */
    public function refreshBalance(ViewOnlyWallet $viewOnlyWallet)
    {
        // Vérifier que le wallet appartient à l'utilisateur
        if ($viewOnlyWallet->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $balanceData = $this->walletService->getBalance(
            $viewOnlyWallet->address,
            $viewOnlyWallet->network
        );

        if ($balanceData['success']) {
            $viewOnlyWallet->update([
                'balance' => $balanceData['balance'],
                'last_balance_update' => now(),
            ]);

            return back()->with('success', 'Balance mise à jour: ' . $balanceData['balance'] . ' ETH');
        }

        return back()->with('error', 'Erreur lors de la mise à jour de la balance: ' . $balanceData['error']);
    }

    /**
     * Refresh statistics from blockchain.
     */
    public function refreshStatistics(ViewOnlyWallet $viewOnlyWallet)
    {
        // Vérifier que le wallet appartient à l'utilisateur
        if ($viewOnlyWallet->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $statistics = $this->statisticsService->updateStatistics($viewOnlyWallet);

        if ($statistics) {
            return back()->with('success', 'Statistiques mises à jour avec succès!');
        }

        return back()->with('error', 'Erreur lors de la mise à jour des statistiques.');
    }
}
