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
        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');
            $table->integer('year');
            $table->string('month');
            $table->date('date');
            $table->decimal('sales_amount', 16, 2);
            $table->decimal('purchase_amount', 16, 2);
            $table->decimal('monthly_cost', 16, 2);
            $table->decimal('management_cost', 16, 2);
            $table->decimal('delivery_cost', 16, 2);
            $table->decimal('investor_profit', 16, 2);
            $table->decimal('sales_commission', 16, 2);
            $table->decimal('net_profit', 16, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_distributions');
    }
};
