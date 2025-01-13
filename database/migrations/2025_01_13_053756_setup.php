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
        // Contractors
        Schema::create('ca_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->integer('yoe');
            $table->timestamps();
            $table->softDeletes();
        });

        // Customers
        Schema::create('ca_customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->timestamps();
            $table->softDeletes();
        });

        // Skills
        Schema::create('ca_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Contractor Skills
        Schema::create('ca_contractors_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contractor_id');
            $table->unsignedBigInteger('skill_id');

            $table->foreign('contractor_id')->references('id')->on('ca_contractors');
            $table->foreign('skill_id')->references('id')->on('ca_skills');
        });

        // Locations
        Schema::create('ca_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('state');
            $table->integer('zipcode');
            $table->timestamps();
            $table->softDeletes();
        });

        // Jobs
        Schema::create('ca_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('ca_locations');
            $table->foreign('customer_id')->references('id')->on('ca_customers');
        });

        // Jobs Skills
        Schema::create('ca_jobs_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('skill_id')->references('id')->on('ca_skills');
            $table->foreign('job_id')->references('id')->on('ca_jobs');
        });

        // Contracts
        Schema::create('ca_contracts', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('contractor_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_id')->references('id')->on('ca_jobs');
            $table->foreign('contractor_id')->references('id')->on('ca_contractors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('ca_contractors');
        Schema::dropIfExists('ca_customers');
        Schema::dropIfExists('ca_skills');
        Schema::dropIfExists('ca_contractors_skills');
        Schema::dropIfExists('ca_locations');
        Schema::dropIfExists('ca_jobs');
        Schema::dropIfExists('ca_jobs_skills');
        Schema::dropIfExists('ca_contracts');
    }
};
