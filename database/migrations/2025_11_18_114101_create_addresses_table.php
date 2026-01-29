<?php

use App\Enum\CityEnum;
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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->enum("city",[CityEnum::ALEPPO,CityEnum::class,CityEnum::HOMS,CityEnum::DAMASCUS,CityEnum::LATAKAI]);
            $table->text("street");
            $table->text("build");
            $table->text("latitude");
            $table->text("longitude");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
