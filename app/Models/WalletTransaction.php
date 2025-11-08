<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'transaction_hash',
        'from_address',
        'to_address',
        'amount',
        'amount_usd',
        'gas_used',
        'gas_price',
        'transaction_fee',
        'type',
        'status',
        'block_number',
        'block_timestamp',
        'confirmations',
        'input_data',
        'contract_address',
        'logs',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:18',
        'amount_usd' => 'decimal:2',
        'gas_used' => 'decimal:0',
        'gas_price' => 'decimal:0',
        'transaction_fee' => 'decimal:18',
        'block_number' => 'integer',
        'block_timestamp' => 'datetime',
        'confirmations' => 'integer',
        'logs' => 'array',
    ];

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Check if transaction is incoming (received).
     * 
     * @return bool
     */
    public function isIncoming(): bool
    {
        return $this->type === 'received';
    }

    /**
     * Check if transaction is outgoing (sent).
     * 
     * @return bool
     */
    public function isOutgoing(): bool
    {
        return $this->type === 'sent';
    }

    /**
     * Check if transaction is confirmed.
     * 
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if transaction is pending.
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get formatted amount with sign.
     * 
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->isIncoming() ? '+' : '-';
        return $sign . number_format($this->amount, 6) . ' ETH';
    }

    /**
     * Get transaction fee formatted.
     * 
     * @return string|null
     */
    public function getFormattedFeeAttribute(): ?string
    {
        return $this->transaction_fee ? number_format($this->transaction_fee, 6) . ' ETH' : null;
    }

    /**
     * Get explorer URL for the transaction.
     * 
     * @return string
     */
    public function getExplorerUrlAttribute(): string
    {
        $network = $this->wallet->network ?? 'base';
        
        $explorers = [
            'base' => 'https://basescan.org/tx/',
            'base-sepolia' => 'https://sepolia.basescan.org/tx/',
        ];

        return ($explorers[$network] ?? $explorers['base']) . $this->transaction_hash;
    }

    /**
     * Scope a query to only include confirmed transactions.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include received transactions.
     */
    public function scopeReceived($query)
    {
        return $query->where('type', 'received');
    }

    /**
     * Scope a query to only include sent transactions.
     */
    public function scopeSent($query)
    {
        return $query->where('type', 'sent');
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to get recent transactions.
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('block_timestamp', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }
}
