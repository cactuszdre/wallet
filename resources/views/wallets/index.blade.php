@extends('layouts.app')

@section('title', 'Mes Wallets')

@push('styles')
    @vite('resources/css/wallets-index.css')
@endpush

@section('content')
<!-- Hero Header -->
<div class="wallets-hero">
    <div class="hero-background"></div>
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">
                <span class="hero-icon">💼</span>
                Portfolio Crypto
            </h1>
            <p class="hero-subtitle">Gérez et surveillez tous vos wallets en temps réel</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('wallets.create') }}" class="btn-hero btn-hero-primary">
                <svg class="btn-hero-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Créer un Wallet
            </a>
            <a href="{{ route('wallets.import') }}" class="btn-hero btn-hero-secondary">
                <svg class="btn-hero-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Importer
            </a>
        </div>
    </div>
</div>

<!-- Stats Dashboard -->
<div class="stats-dashboard">
    <div class="stat-card stat-card-primary">
        <div class="stat-card-glow"></div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Total Wallets</div>
            <div class="stat-card-value">{{ $stats['total_wallets'] }}</div>
            <div class="stat-card-footer">
                <span class="stat-badge stat-badge-success">
                    <span class="stat-badge-dot"></span>
                    {{ $stats['active_wallets'] }} actifs
                </span>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-gold">
        <div class="stat-card-glow"></div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Balance ETH</div>
            <div class="stat-card-value">{{ number_format($stats['total_balance_eth'], 4) }}</div>
            <div class="stat-card-footer">
                <span class="stat-card-currency">Ethereum</span>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-blue">
        <div class="stat-card-glow"></div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Valeur USD</div>
            <div class="stat-card-value">${{ number_format($stats['total_balance_usd'], 2) }}</div>
            <div class="stat-card-footer">
                <span class="stat-card-change stat-positive">+0.00%</span>
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-purple">
        <div class="stat-card-glow"></div>
        <div class="stat-card-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
            </svg>
        </div>
        <div class="stat-card-content">
            <div class="stat-card-label">Réseaux</div>
            <div class="stat-card-value">{{ $stats['networks']->count() }}</div>
            <div class="stat-card-footer">
                @if($stats['networks']->count() > 0)
                    <div class="network-tags">
                        @foreach($stats['networks'] as $network)
                            <span class="network-tag">{{ strtoupper($network) }}</span>
                        @endforeach
                    </div>
                @else
                    <span class="stat-card-currency">Aucun réseau</span>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Wallets List -->
@if($wallets->count() > 0)
<div class="wallets-section">
    <div class="section-header">
        <h2 class="section-title">Vos Wallets</h2>
        <div class="section-actions">
            <button class="view-toggle active" data-view="grid" onclick="toggleView('grid')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </button>
            <button class="view-toggle" data-view="list" onclick="toggleView('list')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="wallets-grid" id="walletsContainer">
        @foreach($wallets as $wallet)
        <div class="wallet-card-modern {{ !$wallet->is_active ? 'wallet-inactive' : '' }}">
            <!-- Card Background Effect -->
            <div class="wallet-card-bg"></div>
            
            <!-- Status Indicator -->
            <div class="wallet-status-indicator {{ $wallet->is_active ? 'status-active' : 'status-inactive' }}"></div>
            
            <!-- Card Header -->
            <div class="wallet-modern-header">
                <div class="wallet-modern-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="wallet-modern-info">
                    <h3 class="wallet-modern-name">{{ $wallet->name }}</h3>
                    <div class="wallet-modern-meta">
                        <span class="network-badge-modern network-{{ $wallet->network }}">
                            <span class="network-dot"></span>
                            {{ strtoupper($wallet->network) }}
                        </span>
                        @if($wallet->is_active)
                            <span class="active-badge">Actif</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="wallet-modern-address">
                <label class="address-label-modern">Adresse</label>
                <div class="address-display-modern">
                    <code class="address-code-modern">{{ Str::substr($wallet->address, 0, 10) }}...{{ Str::substr($wallet->address, -8) }}</code>
                    <button class="btn-copy-modern" onclick="copyToClipboard('{{ $wallet->address }}')" title="Copier l'adresse">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Balance Section -->
            <div class="wallet-modern-balance">
                <div class="balance-main-modern">
                    <div class="balance-eth">
                        <span class="balance-label-modern">Balance</span>
                        <div class="balance-amount-modern">
                            <span class="balance-number">{{ number_format($wallet->balance, 4) }}</span>
                            <span class="balance-unit">ETH</span>
                        </div>
                    </div>
                    @if($wallet->balance_usd)
                    <div class="balance-usd-modern">
                        <span class="usd-symbol">$</span>{{ number_format($wallet->balance_usd, 2) }}
                    </div>
                    @endif
                </div>
                
                <!-- Balance Chart Placeholder -->
                <div class="balance-chart">
                    <svg viewBox="0 0 100 30" class="chart-line">
                        <polyline
                            fill="none"
                            stroke="url(#gradient)"
                            stroke-width="2"
                            points="0,25 20,20 40,22 60,15 80,18 100,10"
                        />
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#d4af37;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <!-- Description -->
            @if($wallet->description)
            <div class="wallet-modern-description">
                <p>{{ Str::limit($wallet->description, 80) }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="wallet-modern-actions">
                <a href="{{ route('wallets.show', $wallet) }}" class="action-btn action-btn-view">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Détails</span>
                </a>
                <a href="{{ route('wallets.edit', $wallet) }}" class="action-btn action-btn-edit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Modifier</span>
                </a>
                <form action="{{ route('wallets.refresh-balance', $wallet) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn action-btn-refresh" title="Actualiser">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Sync</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<!-- Empty State -->
<div class="empty-state-modern">
    <div class="empty-state-animation">
        <div class="wallet-empty-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div class="empty-state-particles">
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
        </div>
    </div>
    <h2 class="empty-state-title-modern">Aucun wallet trouvé</h2>
    <p class="empty-state-description-modern">
        Commencez votre voyage crypto en créant votre premier wallet ou en important un wallet existant
    </p>
    <div class="empty-state-actions-modern">
        <a href="{{ route('wallets.create') }}" class="btn-empty-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Créer mon premier Wallet</span>
        </a>
        <a href="{{ route('wallets.import') }}" class="btn-empty-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
            </svg>
            <span>Importer un Wallet</span>
        </a>
    </div>
</div>
@endif

<!-- View-Only Wallets Section -->
@if($viewOnlyWallets->isNotEmpty())
<div class="view-only-section">
    <div class="section-header-modern">
        <div class="section-title-wrapper">
            <div class="section-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="section-title-modern">Wallets en Lecture Seule</h2>
                <p class="section-subtitle-modern">Suivez vos wallets favoris sans clé privée</p>
            </div>
        </div>
        <div class="view-toggle-group">
            <span class="wallet-count-badge">{{ $viewOnlyWallets->count() }} wallet{{ $viewOnlyWallets->count() > 1 ? 's' : '' }}</span>
        </div>
    </div>

    <div class="wallets-grid view-only-grid">
        @foreach($viewOnlyWallets as $viewOnlyWallet)
        <div class="wallet-card-modern wallet-card-view-only {{ !$viewOnlyWallet->is_active ? 'wallet-inactive' : '' }}">
            <!-- Card Background Effect -->
            <div class="wallet-card-bg"></div>
            
            <!-- View-Only Badge -->
            <div class="view-only-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>Lecture Seule</span>
            </div>
            
            <!-- Status Indicator -->
            <div class="wallet-status-indicator {{ $viewOnlyWallet->is_active ? 'status-active' : 'status-inactive' }}"></div>
            
            <!-- Card Header -->
            <div class="wallet-modern-header">
                <div class="wallet-modern-icon wallet-icon-view-only">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="wallet-modern-info">
                    <h3 class="wallet-modern-name">{{ $viewOnlyWallet->name }}</h3>
                    <div class="wallet-modern-meta">
                        <span class="network-badge-modern network-{{ $viewOnlyWallet->network }}">
                            <span class="network-dot"></span>
                            {{ strtoupper($viewOnlyWallet->network) }}
                        </span>
                        @if($viewOnlyWallet->is_active)
                            <span class="active-badge">Actif</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="wallet-modern-address">
                <label class="address-label-modern">Adresse</label>
                <div class="address-display-modern">
                    <code class="address-code-modern">{{ Str::substr($viewOnlyWallet->address, 0, 10) }}...{{ Str::substr($viewOnlyWallet->address, -8) }}</code>
                    <button class="btn-copy-modern" onclick="copyToClipboard('{{ $viewOnlyWallet->address }}')" title="Copier l'adresse">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Balance Section -->
            <div class="wallet-modern-balance">
                <div class="balance-main-modern">
                    <div class="balance-eth">
                        <span class="balance-label-modern">Balance</span>
                        <div class="balance-amount-modern">
                            <span class="balance-number">{{ number_format($viewOnlyWallet->balance, 4) }}</span>
                            <span class="balance-unit">ETH</span>
                        </div>
                    </div>
                    @if($viewOnlyWallet->balance_usd)
                    <div class="balance-usd-modern">
                        <span class="usd-symbol">$</span>{{ number_format($viewOnlyWallet->balance_usd, 2) }}
                    </div>
                    @endif
                </div>
                
                <!-- Statistics Preview -->
                @if($viewOnlyWallet->statistics)
                <div class="statistics-preview">
                    <div class="stat-item">
                        <span class="stat-label">Transactions</span>
                        <span class="stat-value">{{ number_format($viewOnlyWallet->statistics->total_transactions) }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Contrats</span>
                        <span class="stat-value">{{ number_format($viewOnlyWallet->statistics->smart_contract_interactions) }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Description -->
            @if($viewOnlyWallet->description)
            <div class="wallet-modern-description">
                <p>{{ Str::limit($viewOnlyWallet->description, 80) }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="wallet-modern-actions">
                <a href="{{ route('view-only-wallets.show', $viewOnlyWallet) }}" class="action-btn action-btn-view">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Statistiques</span>
                </a>
                <form action="{{ route('view-only-wallets.refresh-balance', $viewOnlyWallet) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn action-btn-refresh" title="Actualiser la balance">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Sync</span>
                    </button>
                </form>
                <form action="{{ route('view-only-wallets.refresh-statistics', $viewOnlyWallet) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn action-btn-stats" title="Actualiser les statistiques">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        <span>Stats</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('✓ Adresse copiée dans le presse-papiers!', 'success');
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

function toggleView(view) {
    const container = document.getElementById('walletsContainer');
    const buttons = document.querySelectorAll('.view-toggle');
    
    buttons.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.view === view);
    });
    
    if (view === 'list') {
        container.classList.add('view-list');
    } else {
        container.classList.remove('view-list');
    }
}

// Add subtle animations on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }, index * 100);
        }
    });
}, observerOptions);

document.querySelectorAll('.wallet-card-modern').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
});
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
