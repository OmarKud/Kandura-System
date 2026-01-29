<?php

use App\Enum\RoleEnumUser;
use App\Enum\StatusEnumUser;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string("phone")->require()->unique();
            $table->enum("status",[StatusEnumUser::Active,StatusEnumUser::INActive])->default(StatusEnumUser::Active);
$table->foreignId("role_id")->constrained("roles");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
