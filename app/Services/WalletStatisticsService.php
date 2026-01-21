<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\ViewOnlyWallet;
use App\Models\WalletStatistic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WalletStatisticsService
{
    /**
     * Mettre à jour les statistiques d'un wallet (classique ou view-only)
     */
    public function updateStatistics($wallet): ?WalletStatistic
    {
        try {
            $address = $wallet->address;
            $network = $wallet->network;
            
            // Récupérer les données depuis l'API blockchain
            $data = $this->fetchWalletData($address, $network);
            
            if (!$data) {
                return null;
            }

            // Créer ou mettre à jour les statistiques
            $statistics = $wallet->statistics()->firstOrNew();
            
            $statistics->fill([
                'total_transactions' => $data['total_transactions'],
                'sent_transactions' => $data['sent_transactions'],
                'received_transactions' => $data['received_transactions'],
                'first_transaction_at' => $data['first_transaction_at'],
                'last_transaction_at' => $data['last_transaction_at'],
                'smart_contract_interactions' => $data['smart_contract_interactions'],
                'unique_contracts_interacted' => $data['unique_contracts_interacted'],
                'top_contracts' => $data['top_contracts'],
                'total_value_sent' => $data['total_value_sent'],
                'total_value_received' => $data['total_value_received'],
                'total_gas_spent' => $data['total_gas_spent'],
                'erc20_transfers' => $data['erc20_transfers'],
                'erc721_transfers' => $data['erc721_transfers'],
                'erc1155_transfers' => $data['erc1155_transfers'],
                'last_updated_at' => now(),
                'update_source' => 'api',
            ]);

            $statistics->save();

            return $statistics;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des statistiques', [
                'wallet_id' => $wallet->id,
                'wallet_type' => get_class($wallet),
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Récupérer les données du wallet depuis l'API blockchain
     */
    private function fetchWalletData(string $address, string $network): ?array
    {
        try {
            // Utiliser Etherscan API V2 pour tous les réseaux
            // (Base a migré de Routescan vers Etherscan API V2)
            return $this->fetchFromEtherscan($address, $network);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des données wallet', [
                'address' => $address,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Récupérer les données depuis Routescan
     */
    private function fetchFromRoutescan(string $address, int $chainId): ?array
    {
        try {
            $baseUrl = "https://api.routescan.io/v2/network/mainnet/evm/{$chainId}/etherscan/api";
            
            // Récupérer la liste des transactions
            // Désactiver SSL verification en environnement local (Windows)
            $http = Http::timeout(30);
            if (config('app.env') === 'local') {
                $http = $http->withOptions(['verify' => false]);
            }
            
            $txListResponse = $http->get($baseUrl, [
                'module' => 'account',
                'action' => 'txlist',
                'address' => $address,
                'startblock' => 0,
                'endblock' => 99999999,
                'sort' => 'asc',
            ]);

            if (!$txListResponse->successful()) {
                return null;
            }

            $txData = $txListResponse->json();
            $transactions = $txData['result'] ?? [];

            if (!is_array($transactions)) {
                $transactions = [];
            }

            // Log pour débugger
            Log::info('Routescan API Response', [
                'address' => $address,
                'chainId' => $chainId,
                'status' => $txData['status'] ?? 'unknown',
                'message' => $txData['message'] ?? 'no message',
                'transaction_count' => count($transactions),
                'first_tx' => $transactions[0] ?? null,
                'last_tx' => $transactions[count($transactions) - 1] ?? null,
            ]);

            return $this->parseTransactionData($transactions, $address);
        } catch (\Exception $e) {
            Log::error('Erreur Routescan', [
                'address' => $address,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Récupérer les données depuis Etherscan API V2
     * Etherscan API V2 unifie tous les réseaux avec chainid parameter
     */
    private function fetchFromEtherscan(string $address, string $network): ?array
    {
        try {
            // Etherscan API V2 - endpoint unifié avec chainid
            $baseUrl = 'https://api.etherscan.io/v2/api';
            
            // Mapping des chainIds
            $chainIds = [
                'base' => 8453,
                'base-sepolia' => 84532,
                'ethereum' => 1,
                'sepolia' => 11155111,
            ];

            if (!isset($chainIds[$network])) {
                Log::error('Réseau non supporté', ['network' => $network]);
                return null;
            }

            $chainId = $chainIds[$network];
            $apiKey = config('services.etherscan.api_key', '');

            // Désactiver SSL verification en environnement local (Windows)
            $http = Http::timeout(30);
            if (config('app.env') === 'local') {
                $http = $http->withOptions(['verify' => false]);
            }

            $txListResponse = $http->get($baseUrl, [
                'chainid' => $chainId,
                'module' => 'account',
                'action' => 'txlist',
                'address' => $address,
                'startblock' => 0,
                'endblock' => 99999999,
                'page' => 1,
                'offset' => 10000,
                'sort' => 'asc',
                'apikey' => $apiKey,
            ]);

            if (!$txListResponse->successful()) {
                Log::error('Erreur HTTP Etherscan', [
                    'status' => $txListResponse->status(),
                    'network' => $network,
                    'address' => $address,
                ]);
                return null;
            }

            $txData = $txListResponse->json();
            
            // Log pour débugger
            Log::info('Etherscan API Response', [
                'address' => $address,
                'network' => $network,
                'status' => $txData['status'] ?? 'unknown',
                'message' => $txData['message'] ?? 'no message',
                'transaction_count' => is_array($txData['result'] ?? null) ? count($txData['result']) : 0,
            ]);

            if (!isset($txData['status']) || $txData['status'] !== '1') {
                Log::warning('Erreur API Etherscan', [
                    'address' => $address,
                    'network' => $network,
                    'message' => $txData['message'] ?? 'Unknown error'
                ]);
                return null;
            }

            $transactions = $txData['result'] ?? [];

            if (!is_array($transactions)) {
                return null;
            }

            return $this->parseTransactionData($transactions, $address);
        } catch (\Exception $e) {
            Log::error('Erreur Etherscan API', [
                'address' => $address,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);
            return null;
            $transactions = $txData['result'] ?? [];

            if (!is_array($transactions)) {
                $transactions = [];
            }

            return $this->parseTransactionData($transactions, $address);
        } catch (\Exception $e) {
            Log::error('Erreur Etherscan', [
                'address' => $address,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Parser les données de transactions
     */
    private function parseTransactionData(array $transactions, string $address): array
    {
        $address = strtolower($address);
        
        $stats = [
            'total_transactions' => count($transactions),
            'sent_transactions' => 0,
            'received_transactions' => 0,
            'first_transaction_at' => null,
            'last_transaction_at' => null,
            'smart_contract_interactions' => 0,
            'unique_contracts_interacted' => 0,
            'top_contracts' => [],
            'total_value_sent' => 0,
            'total_value_received' => 0,
            'total_gas_spent' => 0,
            'erc20_transfers' => 0,
            'erc721_transfers' => 0,
            'erc1155_transfers' => 0,
        ];

        if (empty($transactions)) {
            return $stats;
        }

        $contractInteractions = [];

        foreach ($transactions as $tx) {
            $timestamp = isset($tx['timeStamp']) ? (int)$tx['timeStamp'] : null;
            $from = strtolower($tx['from'] ?? '');
            $to = strtolower($tx['to'] ?? '');
            $value = isset($tx['value']) ? $tx['value'] / 1e18 : 0;
            $gasUsed = isset($tx['gasUsed']) ? (int)$tx['gasUsed'] : 0;
            $gasPrice = isset($tx['gasPrice']) ? $tx['gasPrice'] / 1e18 : 0;

            // Première et dernière transaction
            if ($timestamp) {
                $date = date('Y-m-d H:i:s', $timestamp);
                if (!$stats['first_transaction_at']) {
                    $stats['first_transaction_at'] = $date;
                }
                $stats['last_transaction_at'] = $date;
            }

            // Transactions envoyées vs reçues
            if ($from === $address) {
                $stats['sent_transactions']++;
                $stats['total_value_sent'] += $value;
                $stats['total_gas_spent'] += ($gasUsed * $gasPrice);
            }
            
            if ($to === $address) {
                $stats['received_transactions']++;
                $stats['total_value_received'] += $value;
            }

            // Interactions avec smart contracts
            if (!empty($tx['input']) && $tx['input'] !== '0x' && $to) {
                $stats['smart_contract_interactions']++;
                
                if (!isset($contractInteractions[$to])) {
                    $contractInteractions[$to] = 0;
                }
                $contractInteractions[$to]++;
            }
        }

        // Calculer les contrats uniques et le top
        $stats['unique_contracts_interacted'] = count($contractInteractions);
        
        if (!empty($contractInteractions)) {
            arsort($contractInteractions);
            $stats['top_contracts'] = array_map(function ($address, $count) {
                return ['address' => $address, 'count' => $count];
            }, array_keys($contractInteractions), $contractInteractions);
        }

        return $stats;
    }

    /**
     * Obtenir le chainId depuis le nom du réseau
     */
    private function getChainId(string $network): int
    {
        $chainIds = [
            'base' => 8453,
            'base-sepolia' => 84532,
            'ethereum' => 1,
            'sepolia' => 11155111,
        ];

        return $chainIds[$network] ?? 8453;
    }

    /**
     * Créer des statistiques vides pour un nouveau wallet
     */
    public function createEmptyStatistics($wallet): WalletStatistic
    {
        return $wallet->statistics()->create([
            'total_transactions' => 0,
            'sent_transactions' => 0,
            'received_transactions' => 0,
            'smart_contract_interactions' => 0,
            'unique_contracts_interacted' => 0,
            'total_value_sent' => 0,
            'total_value_received' => 0,
            'total_gas_spent' => 0,
            'erc20_transfers' => 0,
            'erc721_transfers' => 0,
            'erc1155_transfers' => 0,
            'last_updated_at' => now(),
            'update_source' => 'manual',
        ]);
    }
}
