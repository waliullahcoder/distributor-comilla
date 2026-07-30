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
        Schema::create('investor_profit_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_profit_id')->constrained()->onDelete('cascade');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id');
            $table->decimal('total_profit', 16, 2);
            $table->decimal('profit_percentage', 16, 2);
            $table->decimal('investor_part', 16, 2);
            $table->integer('total_share');
            $table->integer('individual_share');
            $table->decimal('amount', 16, 2);
            $table->decimal('deposited_amount', 16, 2)->default(0.00);
            $table->tinyInteger('deposited')->default(0);
            $table->date('deposit_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_profit_lists');
    }
};
