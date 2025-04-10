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
        Schema::disableForeignKeyConstraints();

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->text('answer_text');
            $table->integer('order')->nullable();
            $table->boolean('is_correct');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
