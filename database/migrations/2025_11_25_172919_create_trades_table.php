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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pair'); // BTC/USDT, ETH/USDT, etc.
            $table->enum('type', ['buy', 'sell']);
            $table->enum('order_type', ['market', 'limit', 'stop_limit']);
            $table->decimal('amount', 20, 8); // Amount of base currency
            $table->decimal('price', 20, 8); // Price in quote currency
            $table->decimal('total', 20, 8); // Total in quote currency
            $table->decimal('fee', 20, 8)->default(0);
            $table->decimal('filled_amount', 20, 8)->default(0);
            $table->enum('status', ['pending', 'partial', 'filled', 'cancelled'])->default('pending');
            $table->timestamp('filled_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('pair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
