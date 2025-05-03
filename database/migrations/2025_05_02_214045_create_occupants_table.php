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

    Schema::create('occupants', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('first_name');
      $table->string('last_name');
      $table->date('date_of_birth');
      $table->string('birth_location')->nullable();
      $table->string('dpi')->unique();
      $table->date('death_date');
      $table->string('death_location')->nullable();
      $table->text('death_cause')->nullable();
      $table->text('observations')->nullable();
      $table->foreignId('gender_id')->constrained('genders')->onDelete('cascade');
      $table->foreignId('current_niche_id')->constrained('niches')->onDelete('cascade');
    });

    Schema::create('contracts', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('price', 10, 2);
      $table->foreignId('occupant_id')->constrained('occupants')->onDelete('cascade');
      $table->foreignId('niche_id')->constrained('niches')->onDelete('cascade');
      $table->foreignId('representative_id')->constrained('representatives')->onDelete('cascade');
      $table->boolean('active')->default(true);
    });
    Schema::create('representative_occupant', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->foreignId('occupant_id')->constrained('occupants')->onDelete('cascade');
      $table->foreignId('representative_id')->constrained('representatives')->onDelete('cascade');
      $table->string('relationship')->nullable();
    });

    // boletas de pago
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
      $table->text('observations')->nullable();
      $table->string('evidence_url')->nullable();
      $table->boolean('paid')->default(false);
      $table->date('payment_date');
      $table->decimal('amount', 10, 2);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('occupants');
  }
};
