<?php

namespace App\Repositories;

use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository
{
    /**
     * Create a new transaction.
     * 
     * @param array $data
     * @return WalletTransaction
     */
    public function create(array $data): WalletTransaction
    {
        return WalletTransaction::create($data);
    }

    /**
     * Find a transaction by ID.
     * 
     * @param int $id
     * @return WalletTransaction|null
     */
    public function find(int $id): ?WalletTransaction
    {
        return WalletTransaction::find($id);
    }

    /**
     * Find a transaction by hash.
     * 
     * @param string $hash
     * @return WalletTransaction|null
     */
    public function findByHash(string $hash): ?WalletTransaction
    {
        return WalletTransaction::where('transaction_hash', $hash)->first();
    }

    /**
     * Get all transactions for a wallet.
     * 
     * @param int $walletId
     * @return Collection
     */
    public function getWalletTransactions(int $walletId): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->orderBy('block_timestamp', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get paginated transactions for a wallet.
     * 
     * @param int $walletId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWalletTransactionsPaginated(int $walletId, int $perPage = 20): LengthAwarePaginator
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->orderBy('block_timestamp', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get recent transactions for a wallet.
     * 
     * @param int $walletId
     * @param int $limit
     * @return Collection
     */
    public function getRecentTransactions(int $walletId, int $limit = 10): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->recent($limit)
            ->get();
    }

    /**
     * Get pending transactions for a wallet.
     * 
     * @param int $walletId
     * @return Collection
     */
    public function getPendingTransactions(int $walletId): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get confirmed transactions for a wallet.
     * 
     * @param int $walletId
     * @return Collection
     */
    public function getConfirmedTransactions(int $walletId): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->confirmed()
            ->orderBy('block_timestamp', 'desc')
            ->get();
    }

    /**
     * Get transactions by type.
     * 
     * @param int $walletId
     * @param string $type
     * @return Collection
     */
    public function getTransactionsByType(int $walletId, string $type): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->ofType($type)
            ->orderBy('block_timestamp', 'desc')
            ->get();
    }

    /**
     * Update a transaction.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $transaction = $this->find($id);
        
        if (!$transaction) {
            return false;
        }

        return $transaction->update($data);
    }

    /**
     * Update transaction by hash.
     * 
     * @param string $hash
     * @param array $data
     * @return bool
     */
    public function updateByHash(string $hash, array $data): bool
    {
        $transaction = $this->findByHash($hash);
        
        if (!$transaction) {
            return false;
        }

        return $transaction->update($data);
    }

    /**
     * Update transaction status.
     * 
     * @param int $id
     * @param string $status
     * @param int|null $confirmations
     * @return bool
     */
    public function updateStatus(int $id, string $status, ?int $confirmations = null): bool
    {
        $data = ['status' => $status];
        
        if ($confirmations !== null) {
            $data['confirmations'] = $confirmations;
        }

        return $this->update($id, $data);
    }

    /**
     * Get transaction statistics for a wallet.
     * 
     * @param int $walletId
     * @return array
     */
    public function getWalletStats(int $walletId): array
    {
        $transactions = $this->getWalletTransactions($walletId);

        return [
            'total_transactions' => $transactions->count(),
            'confirmed_transactions' => $transactions->where('status', 'confirmed')->count(),
            'pending_transactions' => $transactions->where('status', 'pending')->count(),
            'failed_transactions' => $transactions->where('status', 'failed')->count(),
            'sent_transactions' => $transactions->where('type', 'sent')->count(),
            'received_transactions' => $transactions->where('type', 'received')->count(),
            'total_sent' => $transactions->where('type', 'sent')->sum('amount'),
            'total_received' => $transactions->where('type', 'received')->sum('amount'),
            'total_fees' => $transactions->sum('transaction_fee'),
        ];
    }

    /**
     * Get transactions in date range.
     * 
     * @param int $walletId
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getTransactionsByDateRange(int $walletId, string $startDate, string $endDate): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->whereBetween('block_timestamp', [$startDate, $endDate])
            ->orderBy('block_timestamp', 'desc')
            ->get();
    }

    /**
     * Search transactions.
     * 
     * @param int $walletId
     * @param string $search
     * @return Collection
     */
    public function search(int $walletId, string $search): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->where(function ($query) use ($search) {
                $query->where('transaction_hash', 'like', "%{$search}%")
                      ->orWhere('from_address', 'like', "%{$search}%")
                      ->orWhere('to_address', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
            })
            ->orderBy('block_timestamp', 'desc')
            ->get();
    }

    /**
     * Create or update transaction by hash.
     * 
     * @param string $hash
     * @param array $data
     * @return WalletTransaction
     */
    public function createOrUpdate(string $hash, array $data): WalletTransaction
    {
        return WalletTransaction::updateOrCreate(
            ['transaction_hash' => $hash],
            $data
        );
    }

    /**
     * Delete a transaction.
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $transaction = $this->find($id);
        
        if (!$transaction) {
            return false;
        }

        return $transaction->delete();
    }

    /**
     * Get total volume for a wallet.
     * 
     * @param int $walletId
     * @return array
     */
    public function getTotalVolume(int $walletId): array
    {
        $sent = WalletTransaction::where('wallet_id', $walletId)
            ->sent()
            ->confirmed()
            ->sum('amount');

        $received = WalletTransaction::where('wallet_id', $walletId)
            ->received()
            ->confirmed()
            ->sum('amount');

        return [
            'sent' => $sent,
            'received' => $received,
            'net' => $received - $sent,
        ];
    }

    /**
     * Get transactions with high fees.
     * 
     * @param int $walletId
     * @param float $minFee
     * @return Collection
     */
    public function getHighFeeTransactions(int $walletId, float $minFee = 0.001): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->where('transaction_fee', '>=', $minFee)
            ->orderBy('transaction_fee', 'desc')
            ->get();
    }
}
