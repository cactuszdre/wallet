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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            
            // Informations de la transaction
            $table->string('transaction_hash', 66)->unique(); // Hash de la transaction (0x...)
            $table->string('from_address', 42)->index();
            $table->string('to_address', 42)->index();
            
            // Montants
            $table->decimal('amount', 30, 18); // Montant en ETH
            $table->decimal('amount_usd', 20, 2)->nullable();
            $table->decimal('gas_used', 30, 0)->nullable();
            $table->decimal('gas_price', 30, 0)->nullable(); // En wei
            $table->decimal('transaction_fee', 30, 18)->nullable(); // Frais en ETH
            
            // Détails de la transaction
            $table->enum('type', ['sent', 'received', 'contract_interaction', 'token_transfer'])->index();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending')->index();
            $table->integer('block_number')->nullable()->index();
            $table->timestamp('block_timestamp')->nullable();
            $table->integer('confirmations')->default(0);
            
            // Données supplémentaires
            $table->text('input_data')->nullable(); // Data de la transaction
            $table->string('contract_address', 42)->nullable(); // Pour les interactions de contrat
            $table->json('logs')->nullable(); // Logs de la transaction
            $table->text('notes')->nullable(); // Notes personnelles
            
            $table->timestamps();
            
            // Index composés
            $table->index(['wallet_id', 'status']);
            $table->index(['wallet_id', 'type']);
            $table->index('block_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
