<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('customers', static function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('customers', static function (Blueprint $table) {
            $table->string('phone', 100)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('customers', static function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('customers', static function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('name');
        });
    }
};