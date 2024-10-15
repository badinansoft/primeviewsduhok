<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gases', static function (Blueprint $table) {
            $table->float('total_before_discount')->default(0)->after('unit_price');
            $table->float('discount')->default(0)->after('unit_price');
        });
    }


    public function down(): void
    {
        Schema::table('gases', static function (Blueprint $table) {
            $table->dropColumn('total_before_discount');
            $table->dropColumn('discount');
        });
    }
};
