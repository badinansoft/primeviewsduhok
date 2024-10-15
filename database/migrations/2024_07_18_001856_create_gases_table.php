<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('gases', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');

            $table->boolean('is_rent')
                ->default(false);

            $table->foreignId('rent_customer_id')
                ->nullable()
                ->constrained('customers')
                ->onDelete('set null');

            $table->float('last_unit', 8);
            $table->float('current_unit', 8);
            $table->float('consumption', 8);
            $table->float('unit_price', 8);
            $table->float('total_price', 8);
            $table->string('attachment')->nullable();
            $table->date('date');

            $table->timestamp('paid_at')
                ->default(null)
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->bigInteger('paid_by')->nullable();
            $table->bigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gases');
    }
};
