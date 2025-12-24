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
        Schema::create('supplier_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('product_name');
            $table->integer('quantity_pieces');
            $table->enum('payment_status', ['lunas', 'hutang'])->default('lunas');
            $table->date('due_date')->nullable();
            $table->decimal('cost_price', 15, 2)->comment('Modal dari supplier');
            $table->decimal('additional_costs', 15, 2)->default(0)->comment('Biaya lain-lain');
            $table->decimal('hpp', 15, 2)->storedAs('cost_price + additional_costs')->comment('HPP = Modal + Biaya Lain');
            $table->date('received_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_shipments');
    }
};
