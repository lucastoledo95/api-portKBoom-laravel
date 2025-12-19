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
    /**
     * ESTOQUE - controle
     */
    Schema::create('localizacoes_estoque', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // Ex: "Depósito Central", "Loja 2"
        $table->text('descricao')->nullable(); // Detalhes adicionais
        $table->boolean('ativo')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('estoques', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produto_id')->constrained()->onDelete('cascade');
        $table->foreignId('localizacao_id')->constrained('localizacoes_estoque')->onDelete('cascade');
        $table->decimal('quantidade', 10, 2)->default(0);       // Quantidade atual
        $table->decimal('reservado', 10, 2)->default(0);        // Reservado de pedidos (lembrar de configurar o disponível)
        $table->decimal('estoque_minimo', 10, 2)->nullable(); 
        $table->decimal('estoque_maximo', 10, 2)->nullable();
        $table->timestamps();
        $table->softDeletes();
        // Um mesmo produto não pode ter estoque duplicado na mesma localização
        $table->unique(['produto_id', 'localizacao_id']);
    });

    Schema::create('movimentacoes_estoque', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estoque_id')->nullable()->constrained('estoques')->nullOnDelete();
        $table->foreignId('localizacao_id')->nullable()->constrained('localizacoes_estoque')->nullOnDelete();
        $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        $table->enum('tipo', ['ENTRADA', 'SAIDA', 'AJUSTE', 'RESERVA', 'DEVOLUCAO']);
        $table->decimal('quantidade', 10, 2);
        $table->text('observacao')->nullable();
        $table->timestamps();
    });
    /**
     * ESTOQUE - controle
     */

    Schema::create('fretes', function (Blueprint $table) {  // Em futuras versões, integrar com API externa de frete, por enquanto é portifolio talvez no futuro :)
        $table->id();
        $table->foreignId('produto_id')->constrained()->onDelete('cascade');
        $table->string('tipo')->default('normal'); // Ex: normal, expresso
        $table->decimal('custo', 10, 2)->default(0.00);
        $table->string('prazo_entrega')->nullable(); // Ex: 5-7 dias úteis
        $table->timestamps();
    });

    /** 
     * PROMOÇÕES
     */
    Schema::create('promocoes', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->decimal('desconto_percentual', 5, 2)->nullable(); // Ex: 10.00 (%)
        $table->decimal('desconto_valor', 10, 2)->nullable(); // Ex: R$ 20,00
        $table->dateTime('inicio');
        $table->dateTime('fim');
        $table->boolean('ativo')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('promocao_produto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('promocao_id')->constrained('promocoes')->onDelete('cascade');
        $table->foreignId('produto_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('promocao_categoria', function (Blueprint $table) {
        $table->id();
        $table->foreignId('promocao_id')->constrained('promocoes')->onDelete('cascade');
        $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('promocao_marca', function (Blueprint $table) {
        $table->id();
        $table->foreignId('promocao_id')->constrained('promocoes')->onDelete('cascade');
        $table->foreignId('marca_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });
    /**
     * PROMOÇÕES
     */


    /**
     * CUPONS
     */
    Schema::create('cupons', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->unique();
        $table->decimal('valor_percentual', 5, 2)->nullable(); // Ex: 10.00 (%)
        $table->decimal('valor_fixo', 10, 2)->nullable(); // Ex: R$ 20,00
        $table->integer('uso_maximo')->nullable();
        $table->integer('usos')->default(0);
        $table->dateTime('inicio')->nullable();
        $table->dateTime('fim')->nullable();
        $table->boolean('ativo')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('cupom_produto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cupom_id')->constrained('cupons')->onDelete('cascade');
        $table->foreignId('produto_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('cupom_categoria', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cupom_id')->constrained('cupons')->onDelete('cascade');
        $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('cupom_marca', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cupom_id')->constrained('cupons')->onDelete('cascade');
        $table->foreignId('marca_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });
    /**
     * /CUPONS
     */

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localizacoes_estoque');
        Schema::dropIfExists('estoques');
        Schema::dropIfExists('movimentacoes_estoque');

        Schema::dropIfExists('fretes');

        Schema::dropIfExists('promocoes');
        Schema::dropIfExists('promocao_produto');
        Schema::dropIfExists('promocao_categoria');
        Schema::dropIfExists('promocao_marca');

        Schema::dropIfExists('cupons');
        Schema::dropIfExists('cupom_produto');
        Schema::dropIfExists('cupom_categoria');
        Schema::dropIfExists('cupom_marca');
    }
};

