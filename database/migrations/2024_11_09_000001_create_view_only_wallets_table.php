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
        Schema::create('view_only_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations du wallet en lecture seule
            $table->string('name')->nullable(); // Nom personnalisé
            $table->string('address', 42); // Adresse Ethereum (0x...)
            $table->string('network')->default('base'); // base, base-sepolia, ethereum, sepolia
            
            // Balance tracking (mis à jour périodiquement)
            $table->decimal('balance', 30, 18)->default(0); // Balance en ETH (18 décimales)
            $table->decimal('balance_usd', 20, 2)->nullable(); // Balance en USD
            $table->timestamp('last_balance_update')->nullable();
            
            // Métadonnées
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Données supplémentaires (tags, notes, etc.)
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index('user_id');
            $table->index(['user_id', 'address']); // Éviter les doublons par utilisateur
            $table->index('network');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_only_wallets');
    }
};
