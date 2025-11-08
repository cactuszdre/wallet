# Configuration WalletConnect

## Installation et Configuration

### 1. Obtenir un Project ID WalletConnect

1. Allez sur [WalletConnect Cloud](https://cloud.walletconnect.com/)
2. Créez un compte ou connectez-vous
3. Créez un nouveau projet
4. Copiez votre **Project ID**

### 2. Configurer les variables d'environnement

Ajoutez dans votre fichier `.env` :

```bash
# WalletConnect Configuration
VITE_WALLETCONNECT_PROJECT_ID=votre_project_id_ici

# Base Network RPC URLs (optionnel)
BASE_RPC_URL=https://mainnet.base.org
BASE_SEPOLIA_RPC_URL=https://sepolia.base.org
```

### 3. Installer les dépendances

Les dépendances sont déjà installées via npm. Si nécessaire, réinstallez :

```bash
npm install
```

### 4. Compiler les assets

```bash
npm run dev
# ou pour la production
npm run build
```

## Utilisation

### Dans le Layout

Le bouton WalletConnect est déjà intégré dans la navbar via le composant :

```blade
<x-wallet-connect-button />
```

### Événements JavaScript

Vous pouvez écouter les changements de connexion :

```javascript
window.addEventListener('walletconnect:accountChanged', (event) => {
    const { address, chainId, isConnected } = event.detail
    console.log('Wallet connecté:', address)
    console.log('Réseau:', chainId)
})
```

### API JavaScript Globale

```javascript
// Connecter un wallet
window.WalletConnect.connect()

// Déconnecter
window.WalletConnect.disconnect()

// Obtenir l'adresse connectée
const address = window.WalletConnect.getAddress()

// Obtenir le réseau connecté
const chainId = window.WalletConnect.getChain()
```

## Réseaux Supportés

- **Base Mainnet** (Chain ID: 8453)
- **Base Sepolia Testnet** (Chain ID: 84532)

## Wallets Compatibles

WalletConnect supporte plus de 300 wallets, incluant :

- MetaMask
- Trust Wallet
- Rainbow
- Coinbase Wallet
- Ledger Live
- Et beaucoup d'autres...

## Intégration avec l'Importation de Wallets

Vous pouvez maintenant :

1. **Créer un nouveau wallet** : Génère une nouvelle adresse avec clé privée
2. **Importer un wallet** : Ajoutez un wallet existant via sa clé privée
3. **Connecter via WalletConnect** : Connectez votre wallet externe (MetaMask, etc.)

## Dépannage

### Le bouton ne s'affiche pas

1. Vérifiez que les assets sont compilés : `npm run dev`
2. Vérifiez que le Project ID est configuré dans `.env`
3. Videz le cache du navigateur

### Erreur "Project ID is required"

Vous devez configurer `VITE_WALLETCONNECT_PROJECT_ID` dans votre fichier `.env`

### Le wallet ne se connecte pas

1. Vérifiez votre connexion internet
2. Assurez-vous que votre wallet est sur le réseau Base
3. Essayez de déconnecter/reconnecter

## Sécurité

- Les clés privées ne sont **JAMAIS** exposées via WalletConnect
- Seules les adresses publiques sont accessibles
- Les transactions nécessitent toujours une confirmation dans le wallet
- La connexion est cryptée de bout en bout

## Ressources

- [Documentation WalletConnect](https://docs.walletconnect.com/)
- [Reown AppKit Documentation](https://docs.reown.com/appkit)
- [Base Network Documentation](https://docs.base.org/)
