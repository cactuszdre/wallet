# 🔗 Intégration WalletConnect - Guide Complet

## 📋 Vue d'ensemble

WalletConnect est maintenant intégré dans votre application Base Wallet Manager. Cette intégration permet aux utilisateurs de :

- ✅ Connecter leur wallet externe (MetaMask, Trust Wallet, etc.)
- ✅ Visualiser leur adresse et balance en temps réel
- ✅ Interagir avec la blockchain Base directement
- ✅ Gérer plusieurs wallets (créés, importés, ou connectés)

## 🚀 Installation Rapide

### 1. Configurer WalletConnect Project ID

**Étape 1:** Créez un compte sur [WalletConnect Cloud](https://cloud.walletconnect.com/)

**Étape 2:** Créez un nouveau projet et copiez votre **Project ID**

**Étape 3:** Ajoutez-le dans votre fichier `.env` :

```bash
VITE_WALLETCONNECT_PROJECT_ID=votre_project_id_ici
```

### 2. Compiler les assets

```bash
npm run dev
```

Pour la production :

```bash
npm run build
```

## 📦 Packages Installés

Les packages suivants ont été ajoutés à votre projet :

```json
{
  "@web3modal/wagmi": "^5.1.11",
  "wagmi": "^2.x",
  "viem": "^2.x",
  "@tanstack/react-query": "^5.x"
}
```

## 🎯 Fonctionnalités

### 1. Bouton WalletConnect dans la Navbar

Le bouton WalletConnect est automatiquement visible dans la barre de navigation. Il affiche :

- **État déconnecté** : "Connecter Wallet" 
- **État connecté** : Adresse raccourcie + Réseau + Bouton déconnecter

### 2. Page WalletConnect Dédiée

Accessible via `/walletconnect`, cette page affiche :

- État de connexion
- Adresse complète du wallet
- Réseau connecté (Base / Base Sepolia)
- Balance en temps réel
- Chain ID
- Liste des wallets supportés

### 3. Trois Méthodes de Gestion de Wallets

Votre application supporte maintenant 3 façons de gérer des wallets :

| Méthode | Description | Clé Privée | Use Case |
|---------|-------------|------------|----------|
| **Créer** | Génère un nouveau wallet | ✅ Stockée chiffrée | Créer un nouveau compte |
| **Importer** | Ajoute un wallet existant | ✅ Stockée chiffrée | Importer depuis MetaMask |
| **Connecter** | WalletConnect | ❌ Jamais exposée | Connexion temporaire sécurisée |

## 🔧 API JavaScript

### Méthodes Globales

```javascript
// Connecter un wallet
window.WalletConnect.connect()

// Déconnecter
await window.WalletConnect.disconnect()

// Obtenir l'adresse connectée
const address = window.WalletConnect.getAddress()
// Retourne: "0x1234...5678" ou null

// Obtenir le Chain ID
const chainId = window.WalletConnect.getChain()
// Retourne: 8453 (Base) ou 84532 (Base Sepolia)
```

### Événements

```javascript
// Écouter les changements de connexion
window.addEventListener('walletconnect:accountChanged', (event) => {
    const { address, chainId, isConnected } = event.detail
    
    console.log('Adresse:', address)
    console.log('Réseau:', chainId)
    console.log('Connecté:', isConnected)
})
```

## 🎨 Composants Blade

### Bouton WalletConnect

```blade
<x-wallet-connect-button />
```

Ce composant affiche automatiquement :
- Le bouton de connexion si déconnecté
- L'adresse et le réseau si connecté
- Le bouton de déconnexion

## 🌐 Réseaux Supportés

| Réseau | Chain ID | RPC URL |
|--------|----------|---------|
| Base Mainnet | 8453 | https://mainnet.base.org |
| Base Sepolia | 84532 | https://sepolia.base.org |

## 💼 Wallets Compatibles

WalletConnect supporte plus de **300 wallets**, incluant :

- 🦊 **MetaMask** - Le plus populaire
- 💼 **Trust Wallet** - Mobile & Desktop
- 🌈 **Rainbow** - Expérience moderne
- 🔵 **Coinbase Wallet** - Intégration Coinbase
- 🔐 **Ledger Live** - Hardware wallet
- 🦄 **Uniswap Wallet** - DeFi focused
- ⚡ **Zerion** - Portfolio tracking
- 🎯 **Argent** - Smart wallet
- Et bien d'autres...

## 🔒 Sécurité

### Points Importants

✅ **Les clés privées ne sont JAMAIS exposées via WalletConnect**
- WalletConnect utilise uniquement des adresses publiques
- Les transactions nécessitent toujours une confirmation dans le wallet
- La connexion est cryptée de bout en bout

✅ **Distinction claire entre wallets stockés et connectés**
- Wallets créés/importés : Clés stockées chiffrées en base de données
- Wallets connectés : Aucune clé stockée, connexion temporaire

✅ **Bonnes pratiques**
- Ne partagez jamais votre Project ID publiquement
- Utilisez HTTPS en production
- Validez toujours les transactions côté client

## 📱 Utilisation sur Mobile

### Scanner QR Code

1. L'utilisateur clique sur "Connecter Wallet"
2. Le modal affiche un QR code
3. Scanner avec l'app wallet mobile (MetaMask, Trust, etc.)
4. Approuver la connexion dans l'app

### Deep Links

Les wallets mobiles supportent les deep links automatiques pour une connexion rapide.

## 🛠️ Développement

### Structure des Fichiers

```
resources/
├── js/
│   ├── app.js                          # Point d'entrée
│   ├── walletconnect.js                # Configuration WalletConnect
│   └── bootstrap.js                     # Axios, Alpine
└── views/
    ├── components/
    │   └── wallet-connect-button.blade.php  # Composant bouton
    ├── walletconnect/
    │   └── index.blade.php                  # Page dédiée
    └── layouts/
        └── app.blade.php                     # Layout avec navbar

app/Http/Controllers/
└── WalletConnectController.php          # Contrôleur

routes/
└── web.php                              # Routes
```

### Personnalisation

#### Changer le Thème

Modifiez dans `resources/js/walletconnect.js` :

```javascript
const modal = createWeb3Modal({
    wagmiConfig,
    projectId,
    chains,
    themeMode: 'dark',  // 'light' ou 'dark'
    themeVariables: {
        '--w3m-accent': '#3b82f6',              // Couleur primaire
        '--w3m-border-radius-master': '12px'    // Border radius
    }
})
```

#### Ajouter d'Autres Réseaux

Dans `resources/js/walletconnect.js` :

```javascript
import { base, baseSepolia, mainnet, sepolia } from 'viem/chains'

const chains = [base, baseSepolia, mainnet, sepolia]
```

## 🐛 Dépannage

### Le bouton ne s'affiche pas

1. Vérifiez que `npm run dev` est lancé
2. Vérifiez le Project ID dans `.env`
3. Videz le cache : `Ctrl+Shift+R`

### Erreur "Project ID is required"

```bash
# Ajoutez dans .env
VITE_WALLETCONNECT_PROJECT_ID=votre_id_ici

# Puis relancez
npm run dev
```

### Le wallet ne se connecte pas

1. Vérifiez votre connexion internet
2. Assurez-vous d'être sur le bon réseau (Base)
3. Essayez avec un autre wallet
4. Vérifiez la console du navigateur pour les erreurs

### Console Logs

Activez les logs pour débugger :

```javascript
// Dans walletconnect.js, ajoutez :
console.log('WalletConnect initialized', {
    projectId,
    chains,
    metadata
})
```

## 📊 Monitoring

### Événements à Tracker

```javascript
// Connexion réussie
window.addEventListener('walletconnect:accountChanged', (e) => {
    if (e.detail.isConnected) {
        // Analytics: Wallet connecté
        console.log('✅ Wallet connected:', e.detail.address)
    }
})

// Déconnexion
window.addEventListener('walletconnect:accountChanged', (e) => {
    if (!e.detail.isConnected) {
        // Analytics: Wallet déconnecté
        console.log('❌ Wallet disconnected')
    }
})
```

## 🚀 Prochaines Étapes

### Améliorations Possibles

1. **Envoyer des Transactions**
   - Ajouter la possibilité d'envoyer des ETH/tokens
   - Intégrer avec viem pour les transactions

2. **Afficher les Tokens**
   - Lister les tokens ERC-20 du wallet
   - Afficher les NFTs

3. **Historique des Transactions**
   - Récupérer l'historique depuis la blockchain
   - Afficher dans l'interface

4. **Multi-Chain**
   - Support d'autres réseaux (Ethereum, Polygon, etc.)
   - Switch de réseau automatique

5. **Sign Messages**
   - Permettre la signature de messages
   - Vérification de propriété

## 📚 Ressources

- [Documentation WalletConnect](https://docs.walletconnect.com/)
- [Reown AppKit Docs](https://docs.reown.com/appkit)
- [Wagmi Documentation](https://wagmi.sh/)
- [Viem Documentation](https://viem.sh/)
- [Base Network Docs](https://docs.base.org/)

## ✅ Checklist Finale

Avant de déployer en production :

- [ ] Project ID WalletConnect configuré
- [ ] Variables d'environnement `.env` à jour
- [ ] `npm run build` exécuté
- [ ] Tests sur différents wallets (MetaMask, Trust, etc.)
- [ ] Tests sur mobile (QR code)
- [ ] Tests de déconnexion/reconnexion
- [ ] Vérification HTTPS en production
- [ ] Monitoring des erreurs configuré

## 🎉 Félicitations !

Votre application Base Wallet Manager dispose maintenant d'une intégration WalletConnect complète ! 

Les utilisateurs peuvent :
- ✅ Créer des wallets
- ✅ Importer des wallets existants
- ✅ Connecter leurs wallets externes
- ✅ Gérer tout depuis une seule interface

---

**Besoin d'aide ?** Consultez la documentation ou ouvrez une issue sur GitHub.
