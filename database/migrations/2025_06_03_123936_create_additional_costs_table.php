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
        Schema::create('additional_costs', function (Blueprint $table) {
            $table->id();
            $table->decimal('management_cost', 16, 2)->default(0.00);
            $table->decimal('management_cost_percentage', 16, 2)->default(0.00);
            $table->decimal('moderator_cost', 16, 2)->default(0.00);
            $table->decimal('moderator_cost_percentage', 16, 2)->default(0.00);
            $table->decimal('team_leader_cost', 16, 2)->default(0.00);
            $table->decimal('team_leader_percentage', 16, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_costs');
    }
};
