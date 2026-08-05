<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('api_key', 64)->unique();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('department')->nullable()->comment('e.g. Ministry of Finance');
            $table->boolean('is_active')->default(true);
            $table->integer('monthly_char_limit')->default(1_000_000)->comment('Character quota per month');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
