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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->smallInteger('min_players')->default(3);
            $table->smallInteger('max_players')->default(10);
            $table->unsignedTinyInteger('complexity')->nullable();
            $table->unsignedTinyInteger('type')->unsigned()->default(1);
            $table->dateTime('delay_until')->nullable();
            // Foreign key column
            $table->foreignId('organized_by')
                ->constrained('users')
                ->onDelete('restrict');
            $table->longText('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
