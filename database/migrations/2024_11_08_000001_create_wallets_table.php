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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations du wallet
            $table->string('name')->nullable(); // Nom personnalisé du wallet
            $table->string('address', 42)->unique(); // Adresse Ethereum (0x...)
            $table->text('private_key_encrypted'); // Clé privée chiffrée
            $table->string('network')->default('base'); // base, base-sepolia, etc.
            
            // Balance et tracking
            $table->decimal('balance', 30, 18)->default(0); // Balance en ETH (18 décimales)
            $table->decimal('balance_usd', 20, 2)->nullable(); // Balance en USD
            $table->timestamp('last_balance_update')->nullable();
            
            // Métadonnées
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Pour stocker des données supplémentaires
            
            $table->timestamps();
            $table->softDeletes(); // Pour une suppression douce
            
            // Index
            $table->index('user_id');
            $table->index('network');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
