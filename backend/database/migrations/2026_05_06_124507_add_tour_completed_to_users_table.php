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
                $table->boolean('tour_completed')->default(false);
            } else {
                $table->boolean('tour_completed')->default(false)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tour_completed');
        });
    }
};
