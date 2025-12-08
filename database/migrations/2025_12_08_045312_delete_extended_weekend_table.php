<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the table if it exists
        Schema::dropIfExists('extended_weekends');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('extended_weekends', function (Blueprint $table) {
            $table->id();
            $table->date('start_date'); // inclusive (Y-m-d)
            $table->date('end_date');   // inclusive (Y-m-d)
            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('comment')->nullable();
            $table->timestamps();

            $table->unique(['start_date', 'end_date']); // prevent duplicates
            $table->index(['start_date', 'end_date']);
        });
    }
};
