@extends('layouts.app')

@section('title', 'Importer un Wallet')

@push('styles')
    @vite('resources/css/wallets-import.css')
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('wallets.index') }}" class="back-link">← Retour</a>
        <h1 class="page-title">Importer un Wallet</h1>
        <p class="page-subtitle">Importez un wallet existant en utilisant votre clé privée</p>
    </div>
</div>

<!-- Security Warning -->
<div class="security-banner">
    <div class="security-icon">🔒</div>
    <div class="security-content">
        <h3 class="security-title">Sécurité</h3>
        <ul>
            <li>Ne partagez jamais votre clé privée avec qui que ce soit</li>
            <li>Assurez-vous d'être sur une connexion sécurisée</li>
            <li>Votre clé privée sera chiffrée dans notre base de données</li>
        </ul>
    </div>
</div>

<!-- Import Form -->
<div class="form-container">
    <form action="{{ route('wallets.store-import') }}" method="POST" class="wallet-form" id="importForm">
        @csrf

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
                value="{{ old('name') }}"
                placeholder="Mon Wallet Importé"
                required
                autofocus
            >
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Network Field -->
        <div class="form-group">
            <label for="network" class="form-label">
                Réseau <span class="required">*</span>
            </label>
            <select 
                id="network" 
                name="network" 
                class="form-select @error('network') error @enderror"
                required
            >
                <option value="">Sélectionner un réseau</option>
                <option value="base" {{ old('network') == 'base' ? 'selected' : '' }}>
                    Base (Mainnet)
                </option>
                <option value="base-sepolia" {{ old('network') == 'base-sepolia' ? 'selected' : '' }}>
                    Base Sepolia (Testnet)
                </option>
            </select>
            @error('network')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Private Key Field -->
        <div class="form-group">
            <label for="private_key" class="form-label">
                Clé Privée <span class="required">*</span>
            </label>
            <div class="private-key-input-wrapper">
                <input 
                    type="password" 
                    id="private_key" 
                    name="private_key" 
                    class="form-input private-key-input @error('private_key') error @enderror" 
                    value="{{ old('private_key') }}"
                    placeholder="0x..."
                    required
                >
                <button type="button" class="btn-toggle-visibility" onclick="togglePrivateKeyVisibility()">
                    <span id="eyeIcon">👁️</span>
                </button>
            </div>
            @error('private_key')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <span class="form-help">Entrez votre clé privée (commence généralement par 0x)</span>
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
                placeholder="Notes sur ce wallet..."
            >{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Warning Box -->
        <div class="warning-box">
            <div class="warning-icon">⚠️</div>
            <div class="warning-content">
                <h4>Vérifiez bien votre clé privée</h4>
                <p>Une clé privée invalide empêchera l'importation du wallet. Assurez-vous qu'elle est correcte avant de soumettre.</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('wallets.index') }}" class="btn btn-cancel">
                Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="btn-icon">📥</span>
                Importer le Wallet
            </button>
        </div>
    </form>

    <!-- Alternative Action -->
    <div class="alternative-action">
        <p>Vous n'avez pas encore de wallet ?</p>
        <a href="{{ route('wallets.create') }}" class="link-primary">
            Créer un nouveau wallet →
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePrivateKeyVisibility() {
    const input = document.getElementById('private_key');
    const icon = document.getElementById('eyeIcon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈';
    } else {
        input.type = 'password';
        icon.textContent = '👁️';
    }
}

// Warning before leaving page with unsaved changes
let formModified = false;
const form = document.getElementById('importForm');
const inputs = form.querySelectorAll('input, textarea, select');

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
