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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdraw', 'trade', 'transfer', 'fee', 'reward']);
            $table->string('currency');
            $table->decimal('amount', 20, 8);
            $table->decimal('fee', 20, 8)->default(0);
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('tx_hash')->nullable(); // Blockchain transaction hash
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('tx_hash');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
