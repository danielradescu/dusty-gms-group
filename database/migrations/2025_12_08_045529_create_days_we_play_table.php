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
        Schema::create('days_we_play', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week');
            $table->boolean('playable')->default(false);
            $table->string('changed_by')->nullable(); // track who last changed it
            $table->timestamps();
        });

        // Seed all days; only Saturday and Sunday are playable
        DB::table('days_we_play')->insert([
            ['day_of_week' => 'monday',    'playable' => false, 'changed_by' => null],
            ['day_of_week' => 'tuesday',   'playable' => false, 'changed_by' => null],
            ['day_of_week' => 'wednesday', 'playable' => false, 'changed_by' => null],
            ['day_of_week' => 'thursday',  'playable' => false, 'changed_by' => null],
            ['day_of_week' => 'friday',    'playable' => false, 'changed_by' => null],
            ['day_of_week' => 'saturday',  'playable' => true,  'changed_by' => '-system-'],
            ['day_of_week' => 'sunday',    'playable' => true,  'changed_by' => '-system-'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days_we_play');
    }
};
