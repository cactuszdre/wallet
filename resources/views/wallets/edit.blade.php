@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('wallets.show', $wallet) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Modifier le Wallet</h1>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info Card -->
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> Vous pouvez modifier le nom, la description et l'état actif du wallet. 
                        L'adresse et la clé privée ne peuvent pas être modifiées pour des raisons de sécurité.
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Wallet Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du Wallet</h3>
                <p class="mt-1 text-sm text-gray-500">Modifiez les informations de votre wallet</p>
            </div>

            <form action="{{ route('wallets.update', $wallet) }}" method="POST" class="px-4 py-5 sm:p-6">
                @csrf
                @method('PUT')

                <!-- Wallet Address (Read-only) -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse du Wallet</label>
                    <div class="mt-1 flex items-center">
                        <input 
                            type="text" 
                            id="address" 
                            value="{{ $wallet->address }}"
                            readonly
                            class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 text-gray-600 sm:text-sm font-mono">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">L'adresse du wallet ne peut pas être modifiée</p>
                </div>

                <!-- Network (Read-only) -->
                <div class="mb-6">
                    <label for="network" class="block text-sm font-medium text-gray-700">Réseau</label>
                    <input 
                        type="text" 
                        id="network" 
                        value="{{ $wallet->network === 'base' ? 'Base Mainnet' : 'Base Sepolia (Testnet)' }}"
                        readonly
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 text-gray-600 sm:text-sm">
                    <p class="mt-2 text-sm text-gray-500">Le réseau ne peut pas être modifié</p>
                </div>

                <!-- Wallet Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom du Wallet *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        required
                        value="{{ old('name', $wallet->name) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Mon Wallet Principal">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Ajoutez une description pour ce wallet...">{{ old('description', $wallet->metadata['description'] ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active Checkbox -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="is_active" 
                                name="is_active" 
                                type="checkbox" 
                                {{ old('is_active', $wallet->is_active) ? 'checked' : '' }}
                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">Wallet actif</label>
                            <p class="text-gray-500">Désactiver ce wallet le masquera de la liste principale</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-5 border-t border-gray-200">
                    <a href="{{ route('wallets.show', $wallet) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg border-l-4 border-red-500">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Zone de danger</h3>
                <p class="mt-1 text-sm text-gray-500">Actions irréversibles</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Supprimer ce wallet</h4>
                        <p class="mt-1 text-sm text-gray-500">
                            Une fois supprimé, le wallet ne peut pas être récupéré. Assurez-vous d'avoir sauvegardé la clé privée.
                        </p>
                    </div>
                    <form action="{{ route('wallets.destroy', $wallet) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce wallet ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer le wallet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
