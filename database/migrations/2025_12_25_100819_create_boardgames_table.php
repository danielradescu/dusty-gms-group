<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boardgames', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bgg_id')->unique(); // objectid from BGG
            $table->string('name');
            $table->unsignedSmallInteger('year_published')->nullable()->index();
            $table->unsignedTinyInteger('min_players')->nullable();
            $table->unsignedTinyInteger('max_players')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('rank_boardgame')->nullable()->index();
            $table->boolean('is_expansion')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boardgames');
    }
};
