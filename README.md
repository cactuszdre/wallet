# 💰 Moula - Gestionnaire de Portefeuilles Crypto

Application SaaS de gestion de portefeuilles crypto et d'interaction avec des smart contracts, construite avec Laravel 12 et des technologies Web3.

## 📋 Description du Projet

Moula est une plateforme de gestion de portefeuilles crypto qui permet aux utilisateurs de :

- **Créer et gérer des portefeuilles Ethereum/Base** - Génération sécurisée de clés privées et adresses
- **Suivre les balances et transactions** - Historique complet des mouvements de fonds
- **Surveiller des portefeuilles en lecture seule** - Ajouter des wallets externes pour le suivi
- **Interagir avec des smart contracts** - Ajouter des contrats et exécuter leurs fonctions
- **Consulter des statistiques** - Tableaux de bord et analytics sur vos actifs

## 🛠️ Technologies Utilisées

### Backend
- **PHP 8.2+**
- **Laravel 12** - Framework PHP
- **Laravel Breeze** - Authentification
- **MariaDB** - Base de données
- **kornrunner/keccak** - Génération d'adresses Ethereum
- **simplito/elliptic-php** - Cryptographie elliptique

### Frontend
- **Vite 7** - Bundler JavaScript
- **TailwindCSS 4** - Framework CSS
- **Alpine.js** - Framework JavaScript léger
- **Wagmi / Viem** - Librairies Web3
- **Web3Modal** - Connexion de wallets

### Infrastructure
- **Docker** - Conteneurisation (MariaDB + phpMyAdmin)
- **Laravel Sail** - Environnement Docker pour Laravel

## 📁 Structure du Projet

```
app/
├── Models/
│   ├── User.php                    # Utilisateur
│   ├── Wallet.php                  # Portefeuille crypto
│   ├── ViewOnlyWallet.php          # Portefeuille en lecture seule
│   ├── WalletTransaction.php       # Transactions
│   ├── WalletBalanceHistory.php    # Historique des balances
│   ├── WalletStatistic.php         # Statistiques
│   ├── SmartContract.php           # Contrats intelligents
│   └── ContractInteraction.php     # Interactions avec contrats
├── Services/
│   ├── WalletService.php           # Génération de wallets
│   ├── WalletStatisticsService.php # Calcul de statistiques
│   └── ContractService.php         # Gestion des contrats
└── Repositories/
    ├── WalletRepository.php        # Accès données wallets
    └── TransactionRepository.php   # Accès données transactions
```

## 🚀 Installation et Lancement en Local

### Prérequis

- **PHP 8.2** ou supérieur
- **Composer** - Gestionnaire de dépendances PHP
- **Node.js 18+** et **npm**
- **Docker** et **Docker Compose** (pour la base de données)

### Étapes d'installation

#### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd moula
```

#### 2. Lancer la base de données avec Docker

```bash
docker-compose up -d
```

Cela démarre :
- **MariaDB** sur le port `3306`
- **phpMyAdmin** sur le port `8080` (accessible via http://localhost:8080)

#### 3. Installer les dépendances et configurer le projet

```bash
composer setup
```

Cette commande exécute automatiquement :
- Installation des dépendances PHP (`composer install`)
- Copie du fichier `.env.example` vers `.env`
- Génération de la clé d'application
- Exécution des migrations
- Installation des dépendances npm
- Build des assets

#### 4. Configurer les variables d'environnement

Éditez le fichier `.env` avec les informations de connexion à la base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moula_db
DB_USERNAME=moula_user
DB_PASSWORD=moula_password
```

#### 5. Lancer le serveur de développement

**Sur Windows**, ouvrez deux terminaux séparés :

```bash
# Terminal 1 - Serveur Laravel
php artisan serve
```

```bash
# Terminal 2 - Vite (Hot Module Replacement)
npm run dev
```

**Sur Linux/Mac**, vous pouvez utiliser :

```bash
composer dev
```

Cette commande lance simultanément le serveur Laravel, Vite, la queue et les logs.

> ⚠️ **Note Windows** : La commande `composer dev` ne fonctionne pas sur Windows car Laravel Pail nécessite l'extension `pcntl` qui n'est disponible que sur Unix/Linux.

### Commandes Utiles

| Commande | Description |
|----------|-------------|
| `composer setup` | Installation complète du projet |
| `php artisan serve` | Lancer le serveur Laravel (http://localhost:8000) |
| `npm run dev` | Lancer Vite en mode développement |
| `composer test` | Exécuter les tests PHPUnit |
| `php artisan migrate` | Exécuter les migrations |
| `php artisan migrate:fresh --seed` | Réinitialiser la base de données |
| `npm run build` | Build de production des assets |

### Accès aux Services

| Service | URL | Identifiants |
|---------|-----|--------------|
| Application | http://localhost:8000 | - |
| phpMyAdmin | http://localhost:8080 | root / root_password_secret |
| Base de données | localhost:3306 | moula_user / moula_password |

### Compte Utilisateur de Test

Après avoir exécuté les seeders (`php artisan migrate:fresh --seed`), un compte de test est disponible :

| Email | Mot de passe |
|-------|--------------|
| test@example.com | password |

## 🧪 Tests

Exécuter les tests unitaires et fonctionnels :

```bash
composer test
# ou
php artisan test
```

## 📄 Licence

Ce projet est sous licence MIT.
