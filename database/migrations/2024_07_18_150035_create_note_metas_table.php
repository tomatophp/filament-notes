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
        Schema::create('note_metas', function (Blueprint $table) {
            $table->id();

            //Link To Table
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_type')->nullable();

            $table->foreignId('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->string('key')->index();
            $table->json('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_metas');
    }
};
