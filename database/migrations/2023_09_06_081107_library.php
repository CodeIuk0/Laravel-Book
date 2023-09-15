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
        Schema::create('library', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('comment_id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');

            $table->foreign('user_id')->references('id')->on("users")->onDelete("cascade");
            $table->foreign('book_id')->references('id')->on('books')->onDelete("cascade");

            $table->integer('advancement')->default(0);

            $table->timestamp("user_take_book_when");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library');
    }
};
