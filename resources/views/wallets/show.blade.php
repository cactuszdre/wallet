@extends('layouts.app')

@section('title', $wallet->name)

@push('styles')
    @vite('resources/css/wallets-show.css')
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('wallets.index') }}" class="back-link">← Retour aux wallets</a>
        <div class="wallet-header-info">
            <h1 class="page-title">{{ $wallet->name }}</h1>
            <div class="wallet-badges">
                @if($wallet->is_active)
                    <span class="status-badge status-active">Actif</span>
                @else
                    <span class="status-badge status-inactive">Inactif</span>
                @endif
                <span class="network-badge">{{ strtoupper($wallet->network) }}</span>
            </div>
        </div>
    </div>
    <div class="page-header-actions">
        <form action="{{ route('wallets.refresh-balance', $wallet) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <span class="btn-icon">🔄</span>
                Actualiser
            </button>
        </form>
        <a href="{{ route('wallets.edit', $wallet) }}" class="btn btn-primary">
            <span class="btn-icon">✏️</span>
            Modifier
        </a>
    </div>
</div>

<!-- New Wallet Alert -->
@if(session('new_wallet') || session('imported_wallet'))
<div class="success-banner">
    <div class="success-icon">🎉</div>
    <div class="success-content">
        <h3>{{ session('new_wallet') ? 'Wallet créé avec succès !' : 'Wallet importé avec succès !' }}</h3>
        <p>Votre wallet est maintenant prêt à être utilisé. N'oubliez pas de sauvegarder votre clé privée en lieu sûr.</p>
    </div>
</div>
@endif

<!-- Main Content Grid -->
<div class="wallet-details-grid">
    <!-- Left Column - Info Card -->
    <div class="wallet-info-card">
        <h2 class="card-title">Informations du Wallet</h2>

        <!-- Address -->
        <div class="info-group">
            <label class="info-label">Adresse</label>
            <div class="address-display">
                <code class="address-code">{{ $wallet->address }}</code>
                <button class="btn-icon-action" onclick="copyToClipboard('{{ $wallet->address }}')" title="Copier">
                    📋
                </button>
            </div>
        </div>

        <!-- Balance -->
        <div class="info-group">
            <label class="info-label">Balance</label>
            <div class="balance-display">
                <div class="balance-main">
                    <span class="balance-amount">{{ number_format($wallet->balance, 6) }}</span>
                    <span class="balance-currency">ETH</span>
                </div>
                @if($wallet->balance_usd)
                <div class="balance-usd">
                    ≈ ${{ number_format($wallet->balance_usd, 2) }} USD
                </div>
                @endif
            </div>
        </div>

        <!-- Network -->
        <div class="info-group">
            <label class="info-label">Réseau</label>
            <div class="info-value">
                {{ $wallet->network === 'base' ? 'Base (Mainnet)' : 'Base Sepolia (Testnet)' }}
            </div>
        </div>

        <!-- Description -->
        @if($wallet->description)
        <div class="info-group">
            <label class="info-label">Description</label>
            <div class="info-value description">
                {{ $wallet->description }}
            </div>
        </div>
        @endif

        <!-- Dates -->
        <div class="info-group">
            <label class="info-label">Créé le</label>
            <div class="info-value">
                {{ $wallet->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        @if($wallet->last_synced_at)
        <div class="info-group">
            <label class="info-label">Dernière synchronisation</label>
            <div class="info-value">
                {{ $wallet->last_synced_at->diffForHumans() }}
            </div>
        </div>
        @endif

        <!-- Export Private Key -->
        <div class="danger-zone">
            <h3 class="danger-title">Zone Dangereuse</h3>
            <button onclick="exportPrivateKey()" class="btn btn-danger">
                <span class="btn-icon">🔑</span>
                Exporter la Clé Privée
            </button>
            <p class="danger-warning">Ne partagez jamais votre clé privée !</p>
        </div>
    </div>

    <!-- Right Column - Transactions -->
    <div class="wallet-transactions-card">
        <div class="card-header">
            <h2 class="card-title">Transactions Récentes</h2>
            <span class="transaction-count">{{ $wallet->transactions->count() }} transaction(s)</span>
        </div>

        @if($wallet->transactions->count() > 0)
        <div class="transactions-list">
            @foreach($wallet->transactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-icon">
                    @if($transaction->type === 'receive')
                        <span class="icon-receive">⬇️</span>
                    @else
                        <span class="icon-send">⬆️</span>
                    @endif
                </div>
                <div class="transaction-details">
                    <div class="transaction-type">
                        {{ $transaction->type === 'receive' ? 'Réception' : 'Envoi' }}
                    </div>
                    <div class="transaction-hash">
                        {{ Str::limit($transaction->hash, 20) }}
                    </div>
                    @if($transaction->created_at)
                    <div class="transaction-date">
                        {{ $transaction->created_at->diffForHumans() }}
                    </div>
                    @endif
                </div>
                <div class="transaction-amount {{ $transaction->type === 'receive' ? 'positive' : 'negative' }}">
                    {{ $transaction->type === 'receive' ? '+' : '-' }}{{ number_format($transaction->amount, 6) }} ETH
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-transactions">
            <div class="empty-icon">📭</div>
            <p class="empty-text">Aucune transaction pour le moment</p>
        </div>
        @endif
    </div>
</div>

<!-- Actions Section -->
<div class="wallet-actions-section">
    <h2 class="section-title">Actions</h2>
    <div class="actions-grid">
        <button class="action-button" onclick="alert('Fonctionnalité à venir')">
            <span class="action-icon">📤</span>
            <span class="action-label">Envoyer</span>
        </button>
        <button class="action-button" onclick="alert('Fonctionnalité à venir')">
            <span class="action-icon">📥</span>
            <span class="action-label">Recevoir</span>
        </button>
        <button class="action-button" onclick="alert('Fonctionnalité à venir')">
            <span class="action-icon">📊</span>
            <span class="action-label">Historique</span>
        </button>
        <button class="action-button action-danger" onclick="confirmDelete()">
            <span class="action-icon">🗑️</span>
            <span class="action-label">Supprimer</span>
        </button>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('wallets.destroy', $wallet) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Private Key Modal -->
<div id="privateKeyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Clé Privée</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-box">
                <p>⚠️ Ne partagez jamais cette clé avec qui que ce soit !</p>
            </div>
            <div class="private-key-display">
                <code id="privateKeyValue">Chargement...</code>
                <button class="btn-copy-key" onclick="copyPrivateKey()">📋 Copier</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Adresse copiée!', 'success');
    }).catch(err => {
        showToast('Erreur lors de la copie', 'error');
    });
}

function exportPrivateKey() {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir afficher votre clé privée ?\n\nNe la partagez avec personne et assurez-vous que personne ne regarde votre écran.')) {
        return;
    }

    fetch('{{ route('wallets.export-private-key', $wallet) }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('privateKeyValue').textContent = data.private_key;
            document.getElementById('privateKeyModal').style.display = 'flex';
        })
        .catch(error => {
            showToast('Erreur lors de l\'export', 'error');
        });
}

function closeModal() {
    document.getElementById('privateKeyModal').style.display = 'none';
    document.getElementById('privateKeyValue').textContent = 'Chargement...';
}

function copyPrivateKey() {
    const privateKey = document.getElementById('privateKeyValue').textContent;
    copyToClipboard(privateKey);
}

function confirmDelete() {
    if (confirm('⚠️ Êtes-vous absolument sûr de vouloir supprimer ce wallet ?\n\nCette action est irréversible. Assurez-vous d\'avoir sauvegardé votre clé privée si vous souhaitez réimporter ce wallet plus tard.')) {
        document.getElementById('deleteForm').submit();
    }
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('privateKeyModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(5px);
}

.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-xl);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow: auto;
    box-shadow: var(--shadow-xl);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-primary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 0;
    width: 2rem;
    height: 2rem;
}

.modal-close:hover {
    color: var(--text-primary);
}

.modal-body {
    padding: 1.5rem;
}

.private-key-display {
    background: var(--bg-secondary);
    border: 1px solid var(--border-primary);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin-top: 1rem;
}

.private-key-display code {
    display: block;
    word-break: break-all;
    color: var(--gold);
    font-size: 0.875rem;
    margin-bottom: 1rem;
    font-family: 'Courier New', monospace;
}

.btn-copy-key {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    border: none;
    border-radius: var(--radius-lg);
    color: var(--bg-primary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-copy-key:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
}

/* Toast */
.toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    background: var(--bg-card);
    border: 1px solid var(--border-primary);
    box-shadow: var(--shadow-xl);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 99999;
}

.toast.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-success {
    border-color: rgba(34, 197, 94, 0.3);
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.toast-error {
    border-color: rgba(239, 68, 68, 0.3);
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}
</style>
@endpush
