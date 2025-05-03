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
      $table->string('dpi')->unique()->nullable();
      $table->date('death_date');
      $table->string('death_location')->nullable();
      $table->text('death_cause')->nullable();
      $table->text('observations')->nullable();
      $table->foreignId('gender_id')->constrained('genders')->onDelete('cascade');
      $table->foreignId('current_niche_id')->constrained('niches')->onDelete('cascade');
    });

    Schema::create('contract_states', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
    });

    Schema::create('contracts', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('price', 10, 2);
      $table->foreignId('occupant_id')->constrained('occupants')->onDelete('cascade');
      $table->foreignId('niche_id')->constrained('niches')->onDelete('cascade');
      $table->foreignId('representative_user_id')->nullable()->constrained('users')->onDelete('cascade');
      $table->foreignId('state_id')->constrained('contract_states')->onDelete('cascade');
    });
    Schema::create('representative_occupant', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->foreignId('occupant_id')->constrained('occupants')->onDelete('cascade');
      $table->foreignId('representative_user_id')->constrained('users')->onDelete('cascade');
      $table->string('relationship')->nullable();
    });

    // boletas de pago
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
      $table->text('observations')->nullable();
      $table->string('evidence_url')->nullable();
      $table->date('generation_date')->default(now());
      $table->boolean('paid')->default(false);
      $table->date('payment_date')->nullable();
      $table->decimal('amount', 10, 2);
      $table->string('correlative')->unique();
    });

    // alter contracts table to add foreign key to the current payment
    Schema::table('contracts', function (Blueprint $table) {
      $table->foreignId('current_payment_id')->nullable()->constrained('payments')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('occupants');
    Schema::dropIfExists('contract_states');
    Schema::dropIfExists('contracts');
    Schema::dropIfExists('representative_occupant');
    Schema::dropIfExists('payments');
  }
};
