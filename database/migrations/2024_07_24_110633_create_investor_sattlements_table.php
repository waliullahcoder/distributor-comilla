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
        Schema::create('investor_sattlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->string('serial_no');
            $table->date('date')->nullable();
            $table->decimal('amount', 16, 2);
            $table->tinyInteger('approved')->default(0);
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
        Schema::dropIfExists('investor_sattlements');
    }
};
