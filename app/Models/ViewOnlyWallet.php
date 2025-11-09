<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ViewOnlyWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'network',
        'balance',
        'balance_usd',
        'last_balance_update',
        'description',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'balance' => 'decimal:18',
        'balance_usd' => 'decimal:2',
        'last_balance_update' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relation vers l'utilisateur propriétaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique vers les statistiques
     */
    public function statistics(): MorphOne
    {
        return $this->morphOne(WalletStatistic::class, 'walletable');
    }

    /**
     * Obtenir l'URL de l'explorateur pour cette adresse
     */
    public function getExplorerUrlAttribute(): string
    {
        $explorers = [
            'base' => 'https://basescan.org/address/',
            'base-sepolia' => 'https://sepolia.basescan.org/address/',
            'ethereum' => 'https://etherscan.io/address/',
            'sepolia' => 'https://sepolia.etherscan.io/address/',
        ];

        $baseUrl = $explorers[$this->network] ?? 'https://basescan.org/address/';
        return $baseUrl . $this->address;
    }

    /**
     * Formater la balance en ETH
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 6) . ' ETH';
    }

    /**
     * Formater la balance en USD
     */
    public function getFormattedBalanceUsdAttribute(): ?string
    {
        if (!$this->balance_usd) {
            return null;
        }
        return '$' . number_format($this->balance_usd, 2);
    }

    /**
     * Scope pour les wallets actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un réseau spécifique
     */
    public function scopeOnNetwork($query, string $network)
    {
        return $query->where('network', $network);
    }
}
