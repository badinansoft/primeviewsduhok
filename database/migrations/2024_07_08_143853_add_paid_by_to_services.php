<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->bigInteger('paid_by')->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->dropColumn('paid_by');
        });
    }
};
