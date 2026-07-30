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
        Schema::create('delivery_agent_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_agent_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('base_rate', 16, 2)->default(0.00);
            $table->decimal('base_weight', 16, 2)->default(0.00);
            $table->decimal('additional_rate', 16, 2)->default(0.00);
            $table->boolean('inside_dhaka')->default(false);
            $table->boolean('subarea_dhaka')->default(false);
            $table->boolean('inside_chittagong')->default(false);
            $table->boolean('subarea_chittagong')->default(false);
            $table->boolean('district_level')->default(false);
            $table->string('return_charge_type')->default('Fixed Charge');
            $table->decimal('return_charge', 16, 2)->default(0.00);
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_agent_packages');
    }
};
