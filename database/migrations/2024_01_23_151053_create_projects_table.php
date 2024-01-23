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
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id')->index();
      $table->string('name')->index();
      $table->json('project_tags')->nullable();
      $table->string('project_type')->index();
      $table->string('url')->nullable()->index();
      $table->string('image')->nullable()->index();
      $table->text('description')->nullable();
      $table->enum('status', ['completed', 'on_hold', 'ongoing', 'pending'])->default('completed');
      $table->boolean('is_archived')->default(false);
      $table->boolean('is_deactivated')->default(false);
      $table->dateTime('start_date')->nullable()->index();
      $table->dateTime('end_date')->nullable()->index();
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('projects');
  }
};
