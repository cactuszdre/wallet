@extends('layouts.app')

@section('title', $viewOnlyWallet->name)

@push('styles')
    @vite('resources/css/view-only-wallets-show.css')
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('wallets.index') }}" class="back-link">← Retour aux wallets</a>
        <div class="wallet-header-info">
            <div class="title-with-badge">
                <h1 class="page-title">{{ $viewOnlyWallet->name }}</h1>
                <span class="view-only-badge-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lecture Seule
                </span>
            </div>
            <div class="wallet-badges">
                @if($viewOnlyWallet->is_active)
                    <span class="status-badge status-active">Actif</span>
                @else
                    <span class="status-badge status-inactive">Inactif</span>
                @endif
                <span class="network-badge">{{ strtoupper($viewOnlyWallet->network) }}</span>
            </div>
        </div>
    </div>
    <div class="page-header-actions">
        <form action="{{ route('view-only-wallets.refresh-balance', $viewOnlyWallet) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Balance
            </button>
        </form>
        <form action="{{ route('view-only-wallets.refresh-statistics', $viewOnlyWallet) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Actualiser Stats
            </button>
        </form>
    </div>
</div>

<!-- Main Content Grid -->
<div class="wallet-details-grid">
    <!-- Left Column - Info Card -->
    <div class="wallet-info-card">
        <h2 class="card-title">Informations du Wallet</h2>

        <!-- Address -->
        <div class="info-group">
            <label class="info-label">Adresse</label>
            <div class="address-display">
                <code class="address-code">{{ $viewOnlyWallet->address }}</code>
                <button class="btn-icon-action" onclick="copyToClipboard('{{ $viewOnlyWallet->address }}')" title="Copier">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <a href="{{ $viewOnlyWallet->explorer_url }}" target="_blank" class="btn-icon-action" title="Voir sur l'explorer">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Balance -->
        <div class="info-group">
            <label class="info-label">Balance</label>
            <div class="balance-display">
                <div class="balance-main">
                    <span class="balance-amount">{{ $viewOnlyWallet->formatted_balance }}</span>
                    <span class="balance-currency">ETH</span>
                </div>
                @if($viewOnlyWallet->balance_usd)
                <div class="balance-usd">
                    ≈ {{ $viewOnlyWallet->formatted_balance_usd }} USD
                </div>
                @endif
            </div>
        </div>

        <!-- Network -->
        <div class="info-group">
            <label class="info-label">Réseau</label>
            <div class="info-value">
                @if($viewOnlyWallet->network === 'base')
                    Base (Mainnet)
                @elseif($viewOnlyWallet->network === 'base-sepolia')
                    Base Sepolia (Testnet)
                @elseif($viewOnlyWallet->network === 'ethereum')
                    Ethereum (Mainnet)
                @else
                    Sepolia (Testnet)
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($viewOnlyWallet->description)
        <div class="info-group">
            <label class="info-label">Description</label>
            <div class="info-value description">
                {{ $viewOnlyWallet->description }}
            </div>
        </div>
        @endif

        <!-- Dates -->
        <div class="info-group">
            <label class="info-label">Ajouté le</label>
            <div class="info-value">
                {{ $viewOnlyWallet->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        @if($viewOnlyWallet->last_balance_update)
        <div class="info-group">
            <label class="info-label">Dernière mise à jour balance</label>
            <div class="info-value">
                {{ $viewOnlyWallet->last_balance_update->diffForHumans() }}
            </div>
        </div>
        @endif

        <!-- Info Zone -->
        <div class="info-zone">
            <h3 class="info-zone-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mode Lecture Seule
            </h3>
            <p class="info-zone-text">Ce wallet est en mode lecture seule. Vous pouvez consulter le solde et les statistiques, mais vous ne pouvez pas effectuer de transactions.</p>
        </div>
    </div>

    <!-- Right Column - Statistics -->
    <div class="wallet-statistics-card">
        <div class="card-header">
            <h2 class="card-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Statistiques Blockchain
            </h2>
            @if($viewOnlyWallet->statistics && $viewOnlyWallet->statistics->last_updated_at)
            <span class="stats-update-time">
                Mis à jour {{ $viewOnlyWallet->statistics->last_updated_at->diffForHumans() }}
            </span>
            @endif
        </div>

        @if($viewOnlyWallet->statistics)
        <div class="statistics-content">
            <!-- Overview Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card stat-card-primary">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-value">{{ number_format($viewOnlyWallet->statistics->total_transactions) }}</div>
                    </div>
                </div>

                <div class="stat-card stat-card-success">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Reçues</div>
                        <div class="stat-value">{{ number_format($viewOnlyWallet->statistics->received_transactions) }}</div>
                    </div>
                </div>

                <div class="stat-card stat-card-warning">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Envoyées</div>
                        <div class="stat-value">{{ number_format($viewOnlyWallet->statistics->sent_transactions) }}</div>
                    </div>
                </div>

                <div class="stat-card stat-card-info">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Interactions Contrats</div>
                        <div class="stat-value">{{ number_format($viewOnlyWallet->statistics->smart_contract_interactions) }}</div>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            @if($viewOnlyWallet->statistics->first_transaction_at || $viewOnlyWallet->statistics->last_transaction_at)
            <div class="activity-timeline">
                <h3 class="section-subtitle">Timeline d'Activité</h3>
                <div class="timeline-grid">
                    @if($viewOnlyWallet->statistics->first_transaction_at)
                    <div class="timeline-item">
                        <div class="timeline-label">Première Transaction</div>
                        <div class="timeline-value">{{ $viewOnlyWallet->statistics->first_transaction_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-relative">{{ $viewOnlyWallet->statistics->first_transaction_at->diffForHumans() }}</div>
                    </div>
                    @endif

                    @if($viewOnlyWallet->statistics->last_transaction_at)
                    <div class="timeline-item">
                        <div class="timeline-label">Dernière Transaction</div>
                        <div class="timeline-value">{{ $viewOnlyWallet->statistics->last_transaction_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-relative">{{ $viewOnlyWallet->statistics->last_transaction_at->diffForHumans() }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Volume Statistics -->
            <div class="volume-stats">
                <h3 class="section-subtitle">Volumes de Transactions</h3>
                <div class="volume-grid">
                    <div class="volume-item volume-sent">
                        <div class="volume-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                            Volume Envoyé
                        </div>
                        <div class="volume-value">{{ number_format($viewOnlyWallet->statistics->total_value_sent, 4) }} ETH</div>
                    </div>

                    <div class="volume-item volume-received">
                        <div class="volume-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                            Volume Reçu
                        </div>
                        <div class="volume-value">{{ number_format($viewOnlyWallet->statistics->total_value_received, 4) }} ETH</div>
                    </div>

                    <div class="volume-item volume-net">
                        <div class="volume-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"></path>
                            </svg>
                            Volume Net
                        </div>
                        <div class="volume-value {{ $viewOnlyWallet->statistics->net_volume >= 0 ? 'positive' : 'negative' }}">
                            {{ $viewOnlyWallet->statistics->net_volume >= 0 ? '+' : '' }}{{ number_format($viewOnlyWallet->statistics->net_volume, 4) }} ETH
                        </div>
                    </div>

                    <div class="volume-item volume-gas">
                        <div class="volume-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Gas Total Dépensé
                        </div>
                        <div class="volume-value">{{ number_format($viewOnlyWallet->statistics->total_gas_spent, 6) }} ETH</div>
                    </div>
                </div>
            </div>

            <!-- Token Activity -->
            @if($viewOnlyWallet->statistics->erc20_transfers > 0 || $viewOnlyWallet->statistics->erc721_transfers > 0 || $viewOnlyWallet->statistics->erc1155_transfers > 0)
            <div class="token-activity">
                <h3 class="section-subtitle">Activité Tokens</h3>
                <div class="token-grid">
                    <div class="token-card">
                        <div class="token-type">ERC-20</div>
                        <div class="token-count">{{ number_format($viewOnlyWallet->statistics->erc20_transfers) }}</div>
                        <div class="token-label">Transferts</div>
                    </div>
                    <div class="token-card">
                        <div class="token-type">ERC-721</div>
                        <div class="token-count">{{ number_format($viewOnlyWallet->statistics->erc721_transfers) }}</div>
                        <div class="token-label">NFTs</div>
                    </div>
                    <div class="token-card">
                        <div class="token-type">ERC-1155</div>
                        <div class="token-count">{{ number_format($viewOnlyWallet->statistics->erc1155_transfers) }}</div>
                        <div class="token-label">Multi-tokens</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Top Contracts -->
            @if($viewOnlyWallet->statistics->unique_contracts_interacted > 0)
            <div class="top-contracts">
                <h3 class="section-subtitle">
                    Contrats les Plus Utilisés
                    <span class="contract-count-badge">{{ $viewOnlyWallet->statistics->unique_contracts_interacted }} contrat(s)</span>
                </h3>
                @if($viewOnlyWallet->statistics->top_contracts_list && count($viewOnlyWallet->statistics->top_contracts_list) > 0)
                <div class="contracts-list">
                    @foreach($viewOnlyWallet->statistics->top_contracts_list as $contract)
                    <div class="contract-item">
                        <div class="contract-info">
                            <div class="contract-address">
                                <code>{{ Str::substr($contract['address'], 0, 8) }}...{{ Str::substr($contract['address'], -6) }}</code>
                                <button class="btn-copy-small" onclick="copyToClipboard('{{ $contract['address'] }}')" title="Copier">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="contract-count">
                            <span class="count-number">{{ $contract['count'] }}</span>
                            <span class="count-label">interaction(s)</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Activity Rate -->
            @if($viewOnlyWallet->statistics->activity_rate !== null)
            <div class="activity-metric">
                <h3 class="section-subtitle">Taux d'Activité</h3>
                <div class="activity-rate-display">
                    <div class="rate-bar">
                        <div class="rate-fill" style="width: {{ min($viewOnlyWallet->statistics->activity_rate * 100, 100) }}%"></div>
                    </div>
                    <div class="rate-value">{{ number_format($viewOnlyWallet->statistics->activity_rate, 2) }} tx/jour</div>
                </div>
            </div>
            @endif

        </div>
        @else
        <!-- No Statistics Available -->
        <div class="no-statistics">
            <div class="no-stats-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="no-stats-title">Aucune statistique disponible</h3>
            <p class="no-stats-text">Les statistiques n'ont pas encore été récupérées pour ce wallet.</p>
            <form action="{{ route('view-only-wallets.refresh-statistics', $viewOnlyWallet) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Récupérer les statistiques
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('✓ Copié dans le presse-papiers!', 'success');
    }).catch(err => {
        showToast('✕ Erreur lors de la copie', 'error');
        console.error('Erreur:', err);
    });
}

function showToast(message, type) {
    const existingToasts = document.querySelectorAll('.toast-modern');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-modern toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-message">${message}</span>
        </div>
        <div class="toast-progress"></div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
/* Toast Notifications */
.toast-modern {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    min-width: 300px;
    background: var(--bg-card);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-xl);
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 99999;
    overflow: hidden;
}

.toast-modern.show {
    transform: translateX(0);
    opacity: 1;
}

.toast-modern.toast-success {
    border-color: var(--success);
}

.toast-modern.toast-error {
    border-color: var(--error);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-primary);
    font-weight: 500;
}

.toast-success .toast-content {
    color: var(--success);
}

.toast-error .toast-content {
    color: var(--error);
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--blue));
    animation: progress 3s linear forwards;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
@endpush
