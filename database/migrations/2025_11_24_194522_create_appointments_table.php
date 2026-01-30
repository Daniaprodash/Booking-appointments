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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
                // علاقات أساسية
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');     // المريض
                $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');   // الطبيب
                $table->foreignId('service_id')->constrained('services')->onDelete('cascade');  // نوع الخدمة
    
                // تفاصيل الموعد
                $table->date('appointment_date');     // تاريخ الموعد
                $table->time('appointment_time');     // وقت الموعد
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending'); // حالة الموعد
                $table->text('notes')->nullable();    // ملاحظات إضافية
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
