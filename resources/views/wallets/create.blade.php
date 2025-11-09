@extends('layouts.app')

@section('title', 'Créer un Wallet')

@push('styles')
    @vite('resources/css/wallets-create.css')
@endpush

@section('content')
<!-- Header -->
<div class="page-header">
    <div class="page-header-content">
        <a href="{{ route('wallets.index') }}" class="back-link">← Retour</a>
        <h1 class="page-title">Créer un nouveau Wallet</h1>
        <p class="page-subtitle">Un nouveau wallet sera généré automatiquement avec une clé privée sécurisée</p>
    </div>
</div>

<!-- Warning Banner -->
<div class="warning-banner">
    <div class="warning-icon">⚠️</div>
    <div class="warning-content">
        <h3 class="warning-title">Important !</h3>
        <p>Après la création, assurez-vous de bien sauvegarder votre clé privée. Elle ne pourra pas être récupérée si vous la perdez.</p>
    </div>
</div>

<!-- Create Form -->
<div class="form-container">
    <form action="{{ route('wallets.store') }}" method="POST" class="wallet-form">
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
                placeholder="Mon Wallet Principal"
                required
                autofocus
            >
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <span class="form-help">Donnez un nom descriptif à votre wallet</span>
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
            <span class="form-help">Choisissez le réseau blockchain pour votre wallet</span>
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
            >{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <span class="form-help">Ajoutez des notes sur l'utilisation de ce wallet</span>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="info-icon">ℹ️</div>
            <div class="info-content">
                <h4>Ce qui sera généré :</h4>
                <ul>
                    <li>Une nouvelle adresse Ethereum</li>
                    <li>Une clé privée unique et sécurisée</li>
                    <li>La balance sera automatiquement récupérée</li>
                </ul>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('wallets.index') }}" class="btn btn-cancel">
                Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="btn-icon">✨</span>
                Créer le Wallet
            </button>
        </div>
    </form>

    <!-- Alternative Action -->
    <div class="alternative-action">
        <p>Vous avez déjà un wallet ?</p>
        <a href="{{ route('wallets.import') }}" class="link-primary">
            Importer un wallet existant →
        </a>
    </div>
</div>

@endsection
