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
        Schema::create('design_orders_sellection', function (Blueprint $table) {
            $table->id();
            $table->foreignId("design_orders_id")->constrained("design_orders");
            $table->foreignId("design_option_id")->constrained("design_options");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_orders_sellection');
    }
};
