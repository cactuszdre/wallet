<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'walletable_type',
        'walletable_id',
        'total_transactions',
        'sent_transactions',
        'received_transactions',
        'first_transaction_at',
        'last_transaction_at',
        'smart_contract_interactions',
        'unique_contracts_interacted',
        'top_contracts',
        'total_value_sent',
        'total_value_received',
        'total_gas_spent',
        'erc20_transfers',
        'erc721_transfers',
        'erc1155_transfers',
        'last_updated_at',
        'update_source',
        'metadata',
    ];

    protected $casts = [
        'total_transactions' => 'integer',
        'sent_transactions' => 'integer',
        'received_transactions' => 'integer',
        'first_transaction_at' => 'datetime',
        'last_transaction_at' => 'datetime',
        'smart_contract_interactions' => 'integer',
        'unique_contracts_interacted' => 'integer',
        'top_contracts' => 'array',
        'total_value_sent' => 'decimal:18',
        'total_value_received' => 'decimal:18',
        'total_gas_spent' => 'decimal:18',
        'erc20_transfers' => 'integer',
        'erc721_transfers' => 'integer',
        'erc1155_transfers' => 'integer',
        'last_updated_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relation polymorphique vers le wallet (Wallet ou ViewOnlyWallet)
     */
    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Calculer le taux d'activité (transactions par jour depuis la première transaction)
     */
    public function getActivityRateAttribute(): ?float
    {
        if (!$this->first_transaction_at || $this->total_transactions === 0) {
            return null;
        }

        $days = now()->diffInDays($this->first_transaction_at);
        if ($days === 0) {
            return (float) $this->total_transactions;
        }

        return round($this->total_transactions / $days, 2);
    }

    /**
     * Calculer le volume net (reçu - envoyé)
     */
    public function getNetVolumeAttribute(): string
    {
        $net = $this->total_value_received - $this->total_value_sent;
        return number_format($net, 6);
    }

    /**
     * Vérifier si le wallet est actif récemment
     */
    public function isRecentlyActive(int $days = 30): bool
    {
        if (!$this->last_transaction_at) {
            return false;
        }

        return $this->last_transaction_at->isAfter(now()->subDays($days));
    }

    /**
     * Obtenir les 5 contrats les plus utilisés
     */
    public function getTopContractsListAttribute(): array
    {
        if (!$this->top_contracts) {
            return [];
        }

        return array_slice($this->top_contracts, 0, 5);
    }

    /**
     * Scope pour les statistiques récemment mises à jour
     */
    public function scopeRecentlyUpdated($query, int $hours = 24)
    {
        return $query->where('last_updated_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope pour les statistiques qui nécessitent une mise à jour
     */
    public function scopeNeedsUpdate($query, int $hours = 24)
    {
        return $query->where(function ($q) use ($hours) {
            $q->whereNull('last_updated_at')
              ->orWhere('last_updated_at', '<', now()->subHours($hours));
        });
    }
}
