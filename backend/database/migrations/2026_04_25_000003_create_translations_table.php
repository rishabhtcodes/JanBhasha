<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->longText('source_text');
            $table->longText('translated_text')->nullable();
            $table->string('source_lang', 10)->default('en');
            $table->string('target_lang', 10)->default('hi');
            $table->string('provider', 30)->default('google');
            $table->unsignedInteger('characters')->default(0)->comment('Source character count');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->string('source_label')->nullable()->comment('Optional label e.g. Notice #42');
            $table->boolean('is_cached')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organisation_id', 'status']);
            $table->index(['organisation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
