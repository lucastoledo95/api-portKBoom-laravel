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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->decimal('total', 10, 2);
            // Dados de envio
            $table->string('transportadora')->nullable();
            $table->string('codigo_rastreamento')->nullable();
            $table->timestamp('data_envio')->nullable();
            $table->timestamp('data_entrega')->nullable();
            $table->enum('status_envio', ['PENDENTE', 'ENVIADO', 'ENTREGUE', 'DEVOLVIDO'])->default('PENDENTE');
            // Dados do frete aplicados no pedido - copia do frete atual configurado da tabela fretes
            $table->string('tipo_frete')->nullable();            
            $table->decimal('custo_frete', 10, 2)->default(0.00);
            $table->string('prazo_entrega_frete')->nullable();  
            $table->timestamps();
            $table->softDeletes();
        });
   
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('restrict');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('restrict');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal_unitario', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('pedido_itens');
    }
};
