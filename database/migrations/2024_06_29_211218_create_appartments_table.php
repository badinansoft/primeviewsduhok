<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartments', static function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')
                  ->unique();

            $table->tinyInteger('number');

            $table->foreignId('level_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->onDelete('set null');

            $table->foreignId('tower_id')
                    ->constrained()
                    ->onDelete('cascade');

            $table->string('view', 50);

            $table->boolean('is_rent')
                    ->default(false);

            $table->foreignId('rent_customer_id')
                ->nullable()
                ->constrained('customers')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
