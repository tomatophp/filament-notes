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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();

            $table->string('group')->nullable()->index();
            $table->string('status')->default('pending')->nullable()->index();

            //Morph Link
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_type')->nullable();

            //User
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();

            $table->string('title')->nullable()->index();
            $table->text('body')->nullable();

            //Style
            $table->string('background')->default('#F4F39E')->nullable();
            $table->string('border')->default('#DEE184')->nullable();
            $table->string('color')->default('#47576B')->nullable();

            //Checklist
            $table->json('checklist')->nullable();

            $table->string('icon')->nullable();

            //Font
            $table->string('font_size')->default("1em")->nullable();
            $table->string('font')->nullable();

            //Set Date/Time
            $table->date('date')->nullable();
            $table->time('time')->nullable();

            //Sorting
            $table->boolean('is_public')->default(false)->nullable();
            $table->boolean('is_pined')->default(false)->nullable();
            $table->integer('order')->default(0)->nullable();
            $table->string('place_in')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
