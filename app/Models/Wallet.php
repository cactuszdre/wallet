<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'private_key', // Virtuel - sera converti en private_key_encrypted via mutateur
        'private_key_encrypted',
        'network',
        'balance',
        'balance_usd',
        'last_balance_update',
        'description',
        'is_active',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:18',
        'balance_usd' => 'decimal:2',
        'last_balance_update' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'private_key_encrypted',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get balance history for the wallet.
     */
    public function balanceHistory(): HasMany
    {
        return $this->hasMany(WalletBalanceHistory::class);
    }

    /**
     * Get the decrypted private key.
     * 
     * @return string
     */
    public function getPrivateKeyAttribute(): string
    {
        return Crypt::decryptString($this->private_key_encrypted);
    }

    /**
     * Set the encrypted private key.
     * 
     * @param string $value
     * @return void
     */
    public function setPrivateKeyAttribute(string $value): void
    {
        $this->attributes['private_key_encrypted'] = Crypt::encryptString($value);
    }

    /**
     * Get balance formatted with ETH symbol.
     * 
     * @return string
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 6) . ' ETH';
    }

    /**
     * Get balance in USD formatted.
     * 
     * @return string|null
     */
    public function getFormattedBalanceUsdAttribute(): ?string
    {
        return $this->balance_usd ? '$' . number_format($this->balance_usd, 2) : null;
    }

    /**
     * Scope a query to only include active wallets.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by network.
     */
    public function scopeNetwork($query, string $network)
    {
        return $query->where('network', $network);
    }

    /**
     * Scope a query to get wallets for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Update wallet balance.
     * 
     * @param float $balance
     * @param float|null $balanceUsd
     * @return bool
     */
    public function updateBalance(float $balance, ?float $balanceUsd = null): bool
    {
        return $this->update([
            'balance' => $balance,
            'balance_usd' => $balanceUsd,
            'last_balance_update' => now(),
        ]);
    }
}
