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
    Schema::create('user_profiles', function (Blueprint $table) {
      $table->unsignedBigInteger('user_id');
      $table->date('birthdate')->nullable()->index();
      $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
      $table->string('contact_number')->nullable();
      $table->string('street_address')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('postal_code')->nullable();
      $table->string('profile_image')->nullable();
      $table->text('bio')->nullable();
      $table->string('website')->nullable();
      $table->json('additional_info')->nullable();
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_profiles');
  }
};
