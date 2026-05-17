<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'phone_number')) {
            throw new RuntimeException(
                'Column users.phone_number is missing. Run migrations that add profile fields to users before this migration.'
            );
        }
    
        // نقل أرقام الهاتف من doctors إلى users
        if (Schema::hasColumn('doctors', 'phone')) {
            DB::table('doctors')
                ->select(['id', 'user_id', 'phone'])
                ->whereNotNull('user_id')
                ->whereNotNull('phone')
                ->orderBy('id')
                ->chunkById(100, function ($doctors) {
                    foreach ($doctors as $doctor) {
                        DB::table('users')
                            ->where('id', $doctor->user_id)
                            ->whereNull('phone_number')
                            ->update(['phone_number' => $doctor->phone]);
                    }
                });
        }
    
        // حذف الأعمدة المكررة
        $columnsToDrop = array_values(array_filter(
            ['phone', 'image'],
            fn (string $column) => Schema::hasColumn('doctors', $column)
        ));
    
        if ($columnsToDrop !== []) {
            Schema::table('doctors', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (! Schema::hasColumn('doctors', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (! Schema::hasColumn('doctors', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }
};
