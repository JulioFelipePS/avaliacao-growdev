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
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->string('cooperativa');
            $table->string('agencia');
            $table->string('conta');
            $table->string('nome');
            $table->string('documento');
            $table->string('codigo');
            $table->string('descricao');
            $table->decimal('debito', 15, 2);
            $table->decimal('credito', 15, 2);
            $table->timestamp('data_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
