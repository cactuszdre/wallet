@extends('layouts.app')

@section('title', 'Importer un Wallet')

@push('styles')
    @vite('resources/css/wallets-import.css')
@endpush

@section('content')
<div class="import-page container">
    <div class="import-header">
        <a href="{{ route('wallets.index') }}" class="back-link">← Retour aux wallets</a>
        <h1 class="import-title">Importer un wallet</h1>
        <p class="import-subtitle">Deux méthodes disponibles : importer via clé privée ou ajouter un wallet en lecture seule.</p>
    </div>

    <section class="import-grid">
        <!-- Card: Private Key Import -->
        <div class="card card--primary">
            <div class="card__head">
                <h3>Importer (Private Key)</h3>
                <p class="card__sub">Importer un wallet en fournissant la clé privée. La clé sera chiffrée.</p>
            </div>

            <div class="card__body">
                <form id="importForm" class="form" method="POST" action="{{ route('wallets.store-import') }}">
                    @csrf

                    <label class="form__label">Nom</label>
                    <input name="name" type="text" class="form__input" placeholder="Ex : Mon wallet" required value="{{ old('name') }}">

                    <label class="form__label">Réseau</label>
                    <select name="network" class="form__select" required>
                        <option value="base">Base</option>
                        <option value="base-sepolia">Base Sepolia (testnet)</option>
                    </select>

                    <label class="form__label">Clé privée</label>
                    <div class="input-with-action">
                        <input id="private_key" name="private_key" type="password" class="form__input form__input--mono" placeholder="0x..." required>
                        <button type="button" class="action-btn" onclick="togglePrivateKeyVisibility()" aria-label="Afficher/masquer la clé">👁️</button>
                    </div>

                    <label class="form__label">Description (optionnel)</label>
                    <input name="description" type="text" class="form__input" placeholder="Notes..." value="{{ old('description') }}">

                    <div class="form__actions">
                        <button type="submit" class="btn btn--accent">Importer</button>
                        <a href="{{ route('wallets.index') }}" class="btn btn--ghost">Annuler</a>
                    </div>
                </form>
            </div>

            <div class="card__foot muted">La clé privée n'est jamais affichée en clair et est chiffrée côté serveur.</div>
        </div>

        <!-- Card: View-only -->
        <div class="card card--ghost">
            <div class="card__head">
                <h3>Ajout en lecture seule (View‑only)</h3>
                <p class="card__sub">Suivez le solde et les transactions d'une adresse sans importer la clé privée.</p>
            </div>

            <div class="card__body">
                <form id="viewOnlyForm" class="form" method="POST" action="#">
                    @csrf

                    <label class="form__label">Nom affiché</label>
                    <input name="name" type="text" class="form__input" placeholder="Ex : Wallet suivi" required>

                    <label class="form__label">Réseau</label>
                    <select name="network" class="form__select" required>
                        <option value="base">Base</option>
                        <option value="base-sepolia">Base Sepolia (testnet)</option>
                    </select>

                    <label class="form__label">Adresse</label>
                    <input name="address" type="text" class="form__input form__input--mono" placeholder="0x..." required>

                    <label class="form__label">Description (optionnel)</label>
                    <input name="description" type="text" class="form__input" placeholder="Notes...">

                    <div class="form__actions">
                        <button type="submit" class="btn btn--primary">Ajouter</button>
                        <button type="reset" class="btn btn--ghost">Annuler</button>
                    </div>
                </form>
            </div>

            <div class="card__foot muted">Lecture seule : aucune possibilité de signer ou d'envoyer des transactions.</div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
function togglePrivateKeyVisibility() {
    const input = document.getElementById('private_key');
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
