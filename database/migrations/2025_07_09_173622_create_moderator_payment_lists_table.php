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
        Schema::create('moderator_payment_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderator_payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('month');
            $table->string('year');
            // Own Order
            $table->decimal('order_qty', 16, 0);
            $table->decimal('order_amount', 16, 0);
            $table->decimal('qty_commission', 16, 0);
            $table->decimal('amount_commission', 16, 0);

            // Team Members Order
            $table->decimal('leader_qty', 16, 0)->default(0);
            $table->decimal('leader_amount', 16, 0)->default(0);
            $table->decimal('leader_qty_commission', 16, 0)->default(0);
            $table->decimal('leader_amount_commission', 16, 0)->default(0);
            $table->decimal('total_commission', 16, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderator_payment_lists');
    }
};
