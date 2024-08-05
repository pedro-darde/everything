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
        Schema::create('movie', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('overview');
            $table->string('poster_path');
            $table->string('backdrop_path');
            $table->date('release_date');
            $table->integer('external_id');
            $table->float('popularity');
            $table->float('vote_count');
            $table->float('vote_average');
            $table->timestamps();
        });

        Schema::create('movie_gender', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movie');
            $table->foreignId('gender_id')->constrained('gender');
        });

        Schema::create('trending', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movie');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_gender');
        Schema::dropIfExists('trending');
        Schema::dropIfExists('movie');
    }
};
