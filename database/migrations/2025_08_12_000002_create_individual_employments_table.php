<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('individual_employments', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('individual_id')->comment('Reference to individuals table');
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            
            // Current Employment Status
            $table->string('employment_status')->nullable()->comment('Current employment status: employed, unemployed, student, retired');
            $table->boolean('is_looking_for_work')->default(false)->comment('Indicates if actively seeking employment');
            
            // Current Job Information
            $table->string('job_title')->nullable()->comment('Current job title or position');
            $table->string('employer_name')->nullable()->comment('Name of current employer or organization');
            $table->string('employer_industry')->nullable()->comment('Industry sector of current employer');
            $table->date('employment_start_date')->nullable()->comment('Start date of current employment');
            $table->date('employment_end_date')->nullable()->comment('End date of current employment (if applicable)');
            $table->integer('work_hours_per_week')->nullable()->comment('Number of hours worked per week');
            $table->decimal('monthly_income', 10, 2)->nullable()->comment('Monthly income in CAD');
            $table->boolean('is_job_related_to_program')->default(false)->comment('Indicates if current job is related to study program');
            
            // Previous Employment
            $table->string('previous_job_title')->nullable()->comment('Most recent previous job title');
            $table->string('previous_employer_name')->nullable()->comment('Most recent previous employer name');
            $table->date('previous_employment_start_date')->nullable()->comment('Start date of previous employment');
            $table->date('previous_employment_end_date')->nullable()->comment('End date of previous employment');
            $table->string('reason_for_leaving')->nullable()->comment('Reason for leaving previous employment');
            
            // Career Goals and Development
            $table->string('career_interest_area')->nullable()->comment('Primary area of career interest');
            $table->string('desired_job_title')->nullable()->comment('Desired future job title or position');
            $table->string('career_readiness_level')->nullable()->comment('Self-assessed career readiness: beginner, intermediate, advanced');
            $table->boolean('has_career_plan')->default(false)->comment('Indicates if individual has a formal career plan');
            
            // Employment Support and Benefits
            $table->boolean('is_receiving_employment_insurance')->default(false)->comment('Indicates if receiving employment insurance benefits');
            $table->boolean('is_participating_in_work_study_program')->default(false)->comment('Indicates if participating in work-study program');
            
            // Employment Barriers
            $table->text('barriers_to_employment')->nullable()->comment('Description of barriers to employment (disability, lack of experience, etc.)');
            
            // Status
            $table->boolean('is_current')->default(true)->comment('Indicates if this is the current employment record');
            
            // Audit Fields
            $table->timestamps();
            
            // Foreign Key Constraints
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('individual_id');
            $table->index('user_id');
            $table->index(['individual_id', 'is_current']);
            $table->index('employment_status');
            $table->index('is_looking_for_work');
            $table->index(['individual_id', 'employment_status']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('individual_employments');
    }
};
