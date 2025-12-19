<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categorias')->onDelete('restrict');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
       
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();  
            $table->string('slug')->unique();
            $table->string('logo')->nullable(); 
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        
        /**
         * FORNECEDORES
         */
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj_cpf')->nullable();
            $table->string('site')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->boolean('ativo')->default(true);            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fornecedor_contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('restrict');
            $table->string('nome')->nullable(); 
            $table->string('tipo')->nullable(); // Ex: setores, financeiro, comercial, cobranças, gerencia
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cargo')->nullable();
            $table->timestamps();
        });
        /**
         * FORNECEDORES
         */

        /**
         * PRODUTOS
         */
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Código interno do produto (Stock Keeping Unit)
            $table->string('codigo_barras')->nullable(); // EAN/GTIN
            $table->string('nome', '200');
            $table->longText('descricao');
            $table->longText('informacao_tecnica');
            $table->decimal('preco', 10, 2);
            $table->string('slug')->unique();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('restrict');
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('restrict');
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('largura', 8, 2)->nullable();
            $table->decimal('altura', 8, 2)->nullable();
            $table->decimal('comprimento', 8, 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('slug')->unique();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('produto_tag', function (Blueprint $table) {
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('restrict');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('restrict');
            $table->timestamps();
            $table->unique(['produto_id', 'tag_id']);
        });

        Schema::create('produto_historicos', function (Blueprint $table) {
            $table->comment('Todo histórico do produto, categoria, tags, qualquer campo que houve alteração em json');
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('restrict');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->longText('alteracoes'); // JSON (informações que atualizaram)
            $table->timestamps();
            $table->softDeletes();
        });
        /**
         * PRODUTOS
         */

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');

        Schema::dropIfExists('marcas');

        Schema::dropIfExists('fornecedor_contatos');
        Schema::dropIfExists('fornecedores');

        Schema::dropIfExists('produtos');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('produto_tag');
        Schema::dropIfExists('produto_historicos');
    }
};
