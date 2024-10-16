<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('apartments', static function (Blueprint $table) {
            $table->integer('area')->nullable();
            $table->integer('balance_usd')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('apartments', static function (Blueprint $table) {
            $table->dropColumn('area');
            $table->dropColumn('balance_usd');
        });
    }
};
