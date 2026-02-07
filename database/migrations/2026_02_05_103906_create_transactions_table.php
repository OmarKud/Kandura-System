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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
             $table->string('reference', 40)->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('type', 20); // deposit | payment

            $table->decimal('amount', 12, 2);

            $table->json('meta')->nullable();


            $table->unique(['order_id', 'type']);
            $table->index(['user_id', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
