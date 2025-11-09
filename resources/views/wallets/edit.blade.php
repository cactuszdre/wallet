@extends('layouts.app')

@section('title', 'Modifier ' . $wallet->name)

@push('styles')
    @vite('resources/css/wallets-edit.css')
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('wallets.show', $wallet) }}" class="back-link">← Retour au wallet</a>
        <h1 class="page-title">Modifier le Wallet</h1>
        <p class="page-subtitle">{{ $wallet->name }}</p>
    </div>
</div>

<!-- Edit Form -->
<div class="form-container">
    <form action="{{ route('wallets.update', $wallet) }}" method="POST" class="wallet-form">
        @csrf
        @method('PATCH')

        <!-- Name Field -->
        <div class="form-group">
            <label for="name" class="form-label">
                Nom du Wallet <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-input @error('name') error @enderror" 
                value="{{ old('name', $wallet->name) }}"
                required
                autofocus
            >
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description Field -->
        <div class="form-group">
            <label for="description" class="form-label">
                Description <span class="optional">(optionnel)</span>
            </label>
            <textarea 
                id="description" 
                name="description" 
                class="form-textarea @error('description') error @enderror"
                rows="4"
                placeholder="Description de l'utilisation de ce wallet..."
            >{{ old('description', $wallet->description) }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <span class="form-help">Ajoutez des notes sur l'utilisation de ce wallet</span>
        </div>

        <!-- Active Status -->
        <div class="form-group">
            <label class="form-label">Statut</label>
            <div class="checkbox-wrapper">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    class="form-checkbox"
                    {{ old('is_active', $wallet->is_active) ? 'checked' : '' }}
                >
                <label for="is_active" class="checkbox-label">
                    <span class="checkbox-text">Wallet actif</span>
                    <span class="checkbox-help">Un wallet inactif ne sera pas affiché dans les statistiques</span>
                </label>
            </div>
        </div>

        <!-- Read-only Information -->
        <div class="readonly-section">
            <h3 class="section-title">Informations non modifiables</h3>
            
            <div class="info-row">
                <span class="info-label">Adresse:</span>
                <code class="info-value">{{ $wallet->address }}</code>
            </div>
            
            <div class="info-row">
                <span class="info-label">Réseau:</span>
                <span class="info-value">
                    {{ $wallet->network === 'base' ? 'Base (Mainnet)' : 'Base Sepolia (Testnet)' }}
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Balance:</span>
                <span class="info-value">{{ number_format($wallet->balance, 6) }} ETH</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Créé le:</span>
                <span class="info-value">{{ $wallet->created_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-icon">ℹ️</div>
            <div class="info-content">
                <p>L'adresse, le réseau et la clé privée ne peuvent pas être modifiés pour des raisons de sécurité.</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('wallets.show', $wallet) }}" class="btn btn-cancel">
                Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="btn-icon">💾</span>
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="danger-zone-section">
        <h3 class="danger-title">Zone Dangereuse</h3>
        <p class="danger-description">
            La suppression d'un wallet est irréversible. Assurez-vous d'avoir sauvegardé votre clé privée si vous souhaitez réimporter ce wallet plus tard.
        </p>
        <button onclick="confirmDelete()" class="btn btn-danger-full">
            <span class="btn-icon">🗑️</span>
            Supprimer définitivement ce wallet
        </button>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" action="{{ route('wallets.destroy', $wallet) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('⚠️ ATTENTION !\n\nÊtes-vous absolument sûr de vouloir supprimer ce wallet ?\n\n"{{ $wallet->name }}"\n\nCette action est IRRÉVERSIBLE.\n\nAssurez-vous d\'avoir sauvegardé votre clé privée si vous souhaitez réimporter ce wallet plus tard.')) {
        if (confirm('Dernière confirmation !\n\nVoulez-vous vraiment supprimer ce wallet ?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}

// Warning before leaving page with unsaved changes
let formModified = false;
const form = document.querySelector('.wallet-form');
const inputs = form.querySelectorAll('input, textarea');

inputs.forEach(input => {
    input.addEventListener('change', () => {
        formModified = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (formModified) {
        e.preventDefault();
        e.returnValue = '';
    }
});

form.addEventListener('submit', () => {
    formModified = false;
});
</script>
@endpush
