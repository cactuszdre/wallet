<!-- WalletConnect Button Component -->
<div class="flex items-center space-x-3">
    <!-- Connect Button (visible when not connected) -->
    <button 
        id="wallet-connect-btn"
        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
        </svg>
        Connecter Wallet
    </button>

    <!-- Connected State (visible when connected) -->
    <div class="hidden" id="wallet-connected-state">
        <div class="flex items-center space-x-3 bg-white border border-gray-300 rounded-lg px-4 py-2 shadow-sm">
            <!-- Network Badge -->
            <div class="hidden" id="wallet-network-display">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <span class="w-2 h-2 mr-1.5 bg-blue-400 rounded-full animate-pulse"></span>
                    <span id="network-name">Base</span>
                </span>
            </div>

            <!-- Address Display -->
            <div class="hidden flex items-center" id="wallet-address-container">
                <svg class="h-4 w-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-mono font-medium text-gray-700" id="wallet-address-display">0x0000...0000</span>
            </div>

            <!-- Disconnect Button -->
            <button 
                id="wallet-disconnect-btn"
                class="hidden ml-2 inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Déconnecter
            </button>
        </div>
    </div>
</div>

<script>
// Update UI when wallet state changes
window.addEventListener('walletconnect:accountChanged', (event) => {
    const { address, chainId, isConnected } = event.detail
    
    const connectBtn = document.getElementById('wallet-connect-btn')
    const connectedState = document.getElementById('wallet-connected-state')
    const addressContainer = document.getElementById('wallet-address-container')
    const addressDisplay = document.getElementById('wallet-address-display')
    const networkDisplay = document.getElementById('wallet-network-display')
    const networkName = document.getElementById('network-name')
    const disconnectBtn = document.getElementById('wallet-disconnect-btn')
    
    if (isConnected && address) {
        // Show connected state
        connectBtn?.classList.add('hidden')
        connectedState?.classList.remove('hidden')
        addressContainer?.classList.remove('hidden')
        networkDisplay?.classList.remove('hidden')
        disconnectBtn?.classList.remove('hidden')
        
        // Update address
        if (addressDisplay) {
            addressDisplay.textContent = `${address.substring(0, 6)}...${address.substring(address.length - 4)}`
        }
        
        // Update network name
        if (networkName) {
            // Base mainnet chain ID: 8453, Base Sepolia: 84532
            const network = chainId === 8453 ? 'Base' : chainId === 84532 ? 'Base Sepolia' : 'Unknown'
            networkName.textContent = network
        }
    } else {
        // Show connect button
        connectBtn?.classList.remove('hidden')
        connectedState?.classList.add('hidden')
        addressContainer?.classList.add('hidden')
        networkDisplay?.classList.add('hidden')
        disconnectBtn?.classList.add('hidden')
    }
})
</script>
