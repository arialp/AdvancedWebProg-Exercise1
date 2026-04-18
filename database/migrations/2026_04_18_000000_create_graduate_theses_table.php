<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduate_theses', function (Blueprint $table) {
            $table->id();
            $table->string('work_name')->nullable();
            $table->text('work_text')->nullable();
            $table->string('work_link')->nullable();
            $table->string('identification_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduate_theses');
    }
};
