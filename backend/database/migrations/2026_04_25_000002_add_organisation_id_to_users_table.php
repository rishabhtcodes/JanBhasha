<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() === 'mongodb') {
                $table->string('organisation_id')->nullable();
                $table->string('role')->default('translator');
            } else {
                $table->foreignId('organisation_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('organisations')
                    ->nullOnDelete();
                $table->enum('role', ['super_admin', 'admin', 'translator'])
                    ->default('translator')
                    ->after('organisation_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'mongodb') {
                $table->dropForeign(['organisation_id']);
            }
            $table->dropColumn(['organisation_id', 'role']);
        });
    }
};
