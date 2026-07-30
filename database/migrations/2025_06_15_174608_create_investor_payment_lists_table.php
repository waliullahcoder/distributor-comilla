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
        Schema::create('investor_payment_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profit_distribution_list_id')->constrained()->cascadeOnDelete();
            $table->string('month');
            $table->string('year');
            $table->integer('invest_qty');
            $table->decimal('invest_amount', 16, 0);
            $table->decimal('profit_amount', 16, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_payment_lists');
    }
};
