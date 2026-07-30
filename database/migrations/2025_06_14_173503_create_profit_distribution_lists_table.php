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
        Schema::create('profit_distribution_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profit_distribution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investor_id')->constrained()->cascadeOnDelete();
            $table->string('month');
            $table->string('year');
            $table->integer('invest_qty');
            $table->decimal('invest_amount', 16, 0);
            $table->decimal('profit_amount', 16, 0);
            $table->boolean('paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_distribution_lists');
    }
};
