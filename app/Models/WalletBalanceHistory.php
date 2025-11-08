<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletBalanceHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'balance',
        'balance_usd',
        'eth_price_usd',
        'change_amount',
        'change_percentage',
        'snapshot_type',
        'snapshot_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:18',
        'balance_usd' => 'decimal:2',
        'eth_price_usd' => 'decimal:2',
        'change_amount' => 'decimal:18',
        'change_percentage' => 'decimal:4',
        'snapshot_at' => 'datetime',
    ];

    /**
     * Get the wallet that owns the balance history.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Check if balance increased.
     * 
     * @return bool
     */
    public function isIncrease(): bool
    {
        return $this->change_amount > 0;
    }

    /**
     * Check if balance decreased.
     * 
     * @return bool
     */
    public function isDecrease(): bool
    {
        return $this->change_amount < 0;
    }

    /**
     * Get formatted balance.
     * 
     * @return string
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 6) . ' ETH';
    }

    /**
     * Get formatted balance USD.
     * 
     * @return string|null
     */
    public function getFormattedBalanceUsdAttribute(): ?string
    {
        return $this->balance_usd ? '$' . number_format($this->balance_usd, 2) : null;
    }

    /**
     * Get formatted change amount with sign.
     * 
     * @return string|null
     */
    public function getFormattedChangeAttribute(): ?string
    {
        if ($this->change_amount === null) {
            return null;
        }

        $sign = $this->change_amount > 0 ? '+' : '';
        return $sign . number_format($this->change_amount, 6) . ' ETH';
    }

    /**
     * Get formatted change percentage with sign.
     * 
     * @return string|null
     */
    public function getFormattedChangePercentageAttribute(): ?string
    {
        if ($this->change_percentage === null) {
            return null;
        }

        $sign = $this->change_percentage > 0 ? '+' : '';
        return $sign . number_format($this->change_percentage, 2) . '%';
    }

    /**
     * Scope a query to filter by snapshot type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('snapshot_type', $type);
    }

    /**
     * Scope a query to get recent snapshots.
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('snapshot_at', 'desc')->limit($limit);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('snapshot_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get today's snapshots.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('snapshot_at', today());
    }
}
