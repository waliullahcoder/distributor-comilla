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
        Schema::create('invest_renew_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invest_renew_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invest_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('month');
            $table->string('year');
            $table->integer('qty');
            $table->decimal('amount', 16, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invest_renew_lists');
    }
};
