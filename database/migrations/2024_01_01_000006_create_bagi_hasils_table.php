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
        Schema::create('bagi_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained()->onDelete('cascade');
            $table->integer('bagi_hasil_pemilik');
            $table->integer('bagi_hasil_admin');
            $table->date('tanggal');
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
            
            $table->index(['penyewaan_id']);
            $table->index(['tanggal']);
            $table->index(['settled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagi_hasils');
    }
};