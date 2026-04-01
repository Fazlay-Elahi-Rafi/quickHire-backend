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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('tag');
            $table->string('company');
            $table->string('location');
            $table->string('job_type');      // Full-time, Part-time, etc.
            $table->string('workplace');     // Hybrid, Remote, On-site

            // Text Content
            $table->text('description');
            $table->text('about_role');

            $table->text('key_responsibilities');
            $table->text('required_technical_skills');
            $table->text('database_knowledge');
            $table->text('development_tools');
            $table->text('bonus_skills');
            $table->text('benefits');
            $table->text('categories');

            // Nested Object
            $table->text('qualifications');

            // Other Fields
            $table->string('experience_required')->nullable();
            $table->string('salary_range')->nullable();
            $table->integer('min_passing_score')->default(0);
            $table->integer('available')->default(0);

            $table->tinyInteger('status')->default(0)->comment('0=inactive, 1=active');

            $table->string('icon')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
