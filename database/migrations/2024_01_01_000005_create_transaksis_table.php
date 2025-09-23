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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('metode_pembayaran');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('external_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->text('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['penyewaan_id']);
            $table->index(['status']);
            $table->index(['external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};