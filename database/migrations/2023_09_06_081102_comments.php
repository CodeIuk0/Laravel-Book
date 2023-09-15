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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment')->default("");
            $table->integer('note')->max(10);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('book_id')->references('id')->on('books')->onDelete("cascade");
            $table->timestamp("comment_updated_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
