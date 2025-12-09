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
        Schema::create('verification_processes', function (Blueprint $table) {
            $table->id('verificationprocess_id');
            $table->unsignedBigInteger('inquiry_id');
            $table->unsignedBigInteger('MCMC_ID');
            $table->unsignedBigInteger('staff_agency_id');
            $table->dateTime('assigned_date')->nullable();
            $table->text('note')->nullable();
            $table->string('status_update', 50);
            $table->text('explanation_text')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('inquiry_id')->references('inquiry_id')->on('inquiries')->onDelete('cascade');
            $table->foreign('MCMC_ID')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('staff_agency_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_processes');
    }
};
