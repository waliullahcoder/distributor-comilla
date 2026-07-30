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
        Schema::create('moderator_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coa_setup_id')->nullable()->constrained()->nullOnDelete();
            $table->string('serial_no');
            $table->integer('year');
            $table->string('month');
            $table->date('date');
            // Member Order
            $table->decimal('member_order_qty', 16, 0);
            $table->decimal('member_order_amout', 16, 0);
            $table->decimal('member_qty_commission', 16, 0);
            $table->decimal('member_amount_commission', 16, 0);
            // Leader Order
            $table->decimal('leader_order_qty', 16, 0)->default(0);
            $table->decimal('leader_order_amout', 16, 0)->default(0);
            $table->decimal('leader_qty_commission', 16, 0)->default(0);
            $table->decimal('leader_amount_commission', 16, 0)->default(0);
            // Total Commission
            $table->decimal('total_commission', 16, 0)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderator_payments');
    }
};
