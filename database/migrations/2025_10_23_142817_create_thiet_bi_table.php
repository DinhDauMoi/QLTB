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
        Schema::create('thiet_bi', function (Blueprint $table) {
            $table->id(); // cột id tự tăng
            $table->integer('kho_id')->nullable(); // tên kho
            $table->date('ngay')->nullable(); // ngày nhận / ngày giao
            $table->string('imei')->unique(); // imei thiết bị, không trùng
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thiet_bi');
    }
};
