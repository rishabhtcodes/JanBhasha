<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glossaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->string('source_term');
            $table->string('target_term');
            $table->boolean('case_sensitive')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['organisation_id', 'source_term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glossaries');
    }
};
