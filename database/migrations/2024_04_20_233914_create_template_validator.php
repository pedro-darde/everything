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
        Schema::create('template_validator', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('template_validator_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validator_id')->constrained('template_validator');
            $table->string('name');
            $table->string('label');
            $table->string('type');
            $table->integer('position');
            $table->string('error_message');
            $table->boolean('required')->default(false);
            $table->string('default_value')->nullable();
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->string('pattern')->nullable();
            $table->boolean('unique')->default(false);
            $table->timestamps();
        });

        Schema::table('template_validator_fields', function (Blueprint $table) {
            $table->foreignId('refers_to')->nullable()->constrained('template_validator_fields');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_validator');
    }
};
