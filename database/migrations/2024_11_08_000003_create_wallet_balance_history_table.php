<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_balance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            
            // Données de balance
            $table->decimal('balance', 30, 18); // Balance en ETH
            $table->decimal('balance_usd', 20, 2)->nullable(); // Balance en USD
            $table->decimal('eth_price_usd', 20, 2)->nullable(); // Prix ETH au moment du snapshot
            
            // Changements
            $table->decimal('change_amount', 30, 18)->nullable(); // Changement depuis le dernier snapshot
            $table->decimal('change_percentage', 10, 4)->nullable(); // Pourcentage de changement
            
            // Informations du snapshot
            $table->string('snapshot_type')->default('manual'); // manual, scheduled, transaction
            $table->timestamp('snapshot_at')->index();
            
            $table->timestamps();
            
            // Index
            $table->index(['wallet_id', 'snapshot_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_balance_history');
    }
};
