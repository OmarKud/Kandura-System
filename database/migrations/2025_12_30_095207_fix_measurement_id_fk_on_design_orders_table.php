<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) خلّي كل القيم NULL تصير 3
        DB::table('design_orders')
            ->whereNull('measurement_id')
            ->update(['measurement_id' => 3]);

        // 2) إذا في قيم "0" أو قيم مش موجودة بالmeasurements رح تفشل FK
        // (اختياري) تقدر تنظفها وتخليها 3 كمان:
        DB::statement("
            UPDATE design_orders d
            LEFT JOIN measurements m ON m.id = d.measurement_id
            SET d.measurement_id = 3
            WHERE m.id IS NULL
        ");

        // 3) عدّل العمود يخليه default=3 (يحتاج doctrine/dbal غالباً)
        // إذا ما بدك تزيد باكجات، اتركه بدون تعديل default.
        // بس في MySQL ممكن نعملها raw:
        DB::statement("ALTER TABLE design_orders MODIFY measurement_id BIGINT UNSIGNED NOT NULL DEFAULT 3");

        // 4) أضف الـ FK (إذا مو موجود)
        $dbName = DB::getDatabaseName();

        $fkExists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = 'design_orders'
              AND COLUMN_NAME = 'measurement_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ", [$dbName]);

        if (!$fkExists) {
            Schema::table('design_orders', function (Blueprint $table) {
                $table->foreign('measurement_id')
                    ->references('id')
                    ->on('measurements')
                    ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        // حذف الـ FK فقط (وخلي العمود موجود)
        Schema::table('design_orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['measurement_id']);
            } catch (\Throwable $e) {}
        });

        // (اختياري) ترجع default null إذا بدك
        // DB::statement("ALTER TABLE design_orders MODIFY measurement_id BIGINT UNSIGNED NULL");
    }
};
