<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('inquiry_id');
            $table->unsignedBigInteger('public_user_id');
            $table->string('title', 150);
            $table->string('status', 50);
            $table->text('content')->nullable();
            $table->text('media_attachment')->nullable();
            $table->text('evidence_url')->nullable();
            $table->dateTime('date_submitted');
            $table->string('category', 50)->nullable();
            $table->timestamps();

            $table->foreign('public_user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
