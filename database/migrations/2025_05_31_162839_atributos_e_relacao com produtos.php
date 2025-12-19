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
        Schema::create('unidades', function (Blueprint $table) { // Ex: polegadas, W, GHz
            $table->id();
            $table->string('codigo', 10)->unique(); 
            $table->string('nome'); // ex: milímetro, centímetro, quilograma, decibel
            $table->string('simbolo')->nullable(); // ex: mm, cm, kg, dB
            $table->text('descricao')->nullable();
            $table->string('tipo')->nullable(); // comprimento, peso, volume, etc
            $table->decimal('fator_conversao', 10, 6)->nullable(); // para converter para unidade base
            $table->foreignId('unidade_base_id')->nullable()->constrained('unidades'); // auto-relacionamento
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    /**
     * ATRIBUTOS
     */
        Schema::create('atributos', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex:  Cor, Tamanho
            $table->string('slug')->unique();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('atributo_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atributo_id')->constrained();
            $table->string('valor'); // Ex: "JBL", "Preto"
            $table->string('slug')->unique();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('produto_atributo_valor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained();
            $table->foreignId('atributo_valor_id')->constrained('atributo_valores');
            $table->foreignId('unidade_id')->nullable()->constrained('unidades'); 
            $table->string('detalhe_extra')->nullable();  // Ex: compatível com USB 3.1
            $table->timestamps();
            $table->softDeletes();
        });
    /**
     * ATRIBUTOS
     */

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');

        Schema::dropIfExists('atributos');
        Schema::dropIfExists('atributo_valores');
        Schema::dropIfExists('produto_atributo_valor');
    }
};
