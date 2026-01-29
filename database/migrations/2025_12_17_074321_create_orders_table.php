<?php

use App\Enum\PaymentEnumOrder;
use App\Enum\StatusEnumOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("address_id")->constrained("addresses");
            $table->decimal("price",10,2);
            $table->enum("payment_method", [PaymentEnumOrder::STRIPE, PaymentEnumOrder::WALLET, PaymentEnumOrder::DELIVERY]);
            $table->enum("status", [StatusEnumOrder::COMPLETED, StatusEnumOrder::CANCELLED, StatusEnumOrder::PENDING,StatusEnumOrder::PROCESSING])->default(StatusEnumOrder::PENDING);
            $table->string("notes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
