<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $wallet->name }} - Détails</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('wallets.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $wallet->name }}</h1>
                            <p class="text-sm text-gray-500 mt-1">Créé le {{ $wallet->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('wallets.refresh-balance', $wallet->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualiser Balance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- New Wallet Alert -->
            @if(session('new_wallet'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <strong>Wallet créé avec succès!</strong> Veillez à sauvegarder votre clé privée en lieu sûr. Elle ne pourra pas être récupérée si vous la perdez.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Balance Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Balance Totale</p>
                                <p class="text-4xl font-bold mt-2">{{ number_format($wallet->balance, 6) }} ETH</p>
                                @if($wallet->balance_usd)
                                    <p class="text-lg mt-1 opacity-90">≈ ${{ number_format($wallet->balance_usd, 2) }} USD</p>
                                @endif
                                @if($wallet->last_balance_update)
                                    <p class="text-xs mt-2 opacity-75">Dernière mise à jour: {{ $wallet->last_balance_update->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div>
                                <div class="h-20 w-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Details Card -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du Wallet</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <div class="flex items-center space-x-2">
                                    <code class="flex-1 bg-gray-100 px-3 py-2 rounded font-mono text-sm break-all">{{ $wallet->address }}</code>
                                    <button onclick="copyToClipboard('{{ $wallet->address }}')" class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Private Key -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Clé Privée</label>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 relative">
                                        <input 
                                            type="password" 
                                            id="privateKey" 
                                            value="••••••••••••••••••••••••••••••••" 
                                            readonly
                                            class="w-full bg-gray-100 px-3 py-2 rounded font-mono text-sm">
                                    </div>
                                    <button 
                                        onclick="togglePrivateKey()" 
                                        class="flex-shrink-0 px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm font-medium">
                                        Afficher
                                    </button>
                                    <button 
                                        onclick="copyPrivateKey()" 
                                        class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-red-600">⚠️ Ne partagez jamais votre clé privée avec quiconque!</p>
                            </div>

                            <!-- Network -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Réseau</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($wallet->network) }}
                                </span>
                            </div>

                            <!-- Description -->
                            @if($wallet->description)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <p class="text-sm text-gray-600">{{ $wallet->description }}</p>
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                @if($wallet->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Inactif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    @if($wallet->transactions->isNotEmpty())
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Transactions Récentes</h3>
                            </div>
                            <ul class="divide-y divide-gray-200">
                                @foreach($wallet->transactions as $transaction)
                                    <li class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center">
                                                    @if($transaction->type === 'received')
                                                        <span class="flex-shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-600">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span class="flex-shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-600">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                            </svg>
                                                        </span>
                                                    @endif
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ ucfirst($transaction->type) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 font-mono">{{ Str::limit($transaction->transaction_hash, 20) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 text-right">
                                                <p class="text-sm font-medium {{ $transaction->type === 'received' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $transaction->formatted_amount }}
                                                </p>
                                                @if($transaction->block_timestamp)
                                                    <p class="text-xs text-gray-500">{{ $transaction->block_timestamp->diffForHumans() }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Quick Actions -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Actions Rapides</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6 space-y-3">
                            <a href="https://{{ $wallet->network === 'base-sepolia' ? 'sepolia.' : '' }}basescan.org/address/{{ $wallet->address }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Voir sur BaseScan
                            </a>
                            
                            <a href="{{ route('wallets.edit', $wallet->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>

                            <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce wallet?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Security Tips -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Conseils de Sécurité</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Sauvegardez votre clé privée</li>
                                        <li>Ne la partagez jamais</li>
                                        <li>Utilisez un wallet hardware pour les gros montants</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let privateKeyVisible = false;
        let actualPrivateKey = '';

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copié dans le presse-papier!');
            });
        }

        async function togglePrivateKey() {
            const input = document.getElementById('privateKey');
            const btn = event.target;

            if (!privateKeyVisible) {
                // Fetch private key from server
                if (!actualPrivateKey) {
                    try {
                        const response = await fetch('{{ route('wallets.export-private-key', $wallet->id) }}');
                        const data = await response.json();
                        actualPrivateKey = data.private_key;
                    } catch (error) {
                        alert('Erreur lors de la récupération de la clé privée');
                        return;
                    }
                }
                input.type = 'text';
                input.value = actualPrivateKey;
                btn.textContent = 'Masquer';
                privateKeyVisible = true;
            } else {
                input.type = 'password';
                input.value = '••••••••••••••••••••••••••••••••';
                btn.textContent = 'Afficher';
                privateKeyVisible = false;
            }
        }

        async function copyPrivateKey() {
            if (!actualPrivateKey) {
                try {
                    const response = await fetch('{{ route('wallets.export-private-key', $wallet->id) }}');
                    const data = await response.json();
                    actualPrivateKey = data.private_key;
                } catch (error) {
                    alert('Erreur lors de la récupération de la clé privée');
                    return;
                }
            }
            copyToClipboard(actualPrivateKey);
        }
    </script>
</body>
</html>
