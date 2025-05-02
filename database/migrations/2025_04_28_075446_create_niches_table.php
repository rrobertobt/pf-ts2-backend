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
        Schema::create('niches_states', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
        });

        Schema::create('niches_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
        });

        Schema::create('niches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('state_id')->constrained('niches_states')->onDelete('cascade');
            $table->string('avenue_location');
            $table->string('street_location');
            $table->foreignId('type_id')->constrained('niches_types')->onDelete('cascade');
            $table->boolean('is_historical')->default(false);
            $table->string('code')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niches');
    }
};
