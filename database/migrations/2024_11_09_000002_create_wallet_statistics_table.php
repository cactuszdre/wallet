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
        Schema::create('wallet_statistics', function (Blueprint $table) {
            $table->id();
            
            // Relation polymorphique : peut être lié à un Wallet ou ViewOnlyWallet
            $table->morphs('walletable'); // Crée walletable_id et walletable_type
            
            // Statistiques de transactions
            $table->unsignedBigInteger('total_transactions')->default(0); // Nombre total de transactions
            $table->unsignedBigInteger('sent_transactions')->default(0); // Transactions envoyées
            $table->unsignedBigInteger('received_transactions')->default(0); // Transactions reçues
            $table->timestamp('first_transaction_at')->nullable(); // Date première transaction
            $table->timestamp('last_transaction_at')->nullable(); // Date dernière transaction
            
            // Statistiques d'interactions avec smart contracts
            $table->unsignedBigInteger('smart_contract_interactions')->default(0); // Nb d'interactions
            $table->unsignedBigInteger('unique_contracts_interacted')->default(0); // Nb de contrats uniques
            $table->json('top_contracts')->nullable(); // Liste des contrats les plus utilisés [{address, count}]
            
            // Statistiques de volume
            $table->decimal('total_value_sent', 30, 18)->default(0); // Volume total envoyé (ETH)
            $table->decimal('total_value_received', 30, 18)->default(0); // Volume total reçu (ETH)
            $table->decimal('total_gas_spent', 30, 18)->default(0); // Total gas dépensé (ETH)
            
            // Statistiques par type de transaction
            $table->unsignedBigInteger('erc20_transfers')->default(0); // Transferts ERC20
            $table->unsignedBigInteger('erc721_transfers')->default(0); // Transferts NFT
            $table->unsignedBigInteger('erc1155_transfers')->default(0); // Transferts ERC1155
            
            // Métadonnées
            $table->timestamp('last_updated_at')->nullable(); // Dernière mise à jour des stats
            $table->string('update_source')->nullable(); // Source de la MAJ (api, manual, cron)
            $table->json('metadata')->nullable(); // Données supplémentaires
            
            $table->timestamps();
            
            // Index pour performance (morphs() crée déjà l'index walletable_type + walletable_id)
            $table->index('total_transactions');
            $table->index('first_transaction_at');
            $table->index('last_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_statistics');
    }
};
