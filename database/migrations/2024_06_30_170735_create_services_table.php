<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('services',static function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('customer_id')
                ->constrained()
                ->onDelete('cascade');

            $table->boolean('is_rent')
                ->default(false);

            $table->foreignId('rent_customer_id')
                ->nullable()
                ->constrained('customers')
                ->onDelete('set null');

            $table->float('amount')
                ->default(0);

            $table->timestamp('paid_at')
                ->default(null)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->date('start_date');

            $table->date('end_date');

            $table->bigInteger('created_by');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
