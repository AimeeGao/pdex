<?php

namespace Modules\Student\Http\Requests;

use App\Models\Individual;
use App\Rules\ValidSin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIndividualMultiStepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            // General Information Step

            /* General info should collect the following
            $table->string('social_insurance_number')->nullable();
            $table->string('government_issued_id')->nullable();
            // Name & Contact
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('preferred_name')->nullable();
            $table->string('email_address')->unique();
            $table->string('phone_number')->nullable();
            $table->string('alternate_phone_number')->nullable();
            // Demographics (minimal, no address/identity/minority fields)
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('preferred_pronouns')->nullable();
            // Health & Accessibility
            $table->boolean('disability_status')->default(false);
            $table->text('accommodation_needs')->nullable();
            */

            'social_insurance_number' => ['required', 'string', 'max:255', new ValidSin()],
            'government_issued_id' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'email_address' => 'required|email',
            'phone_number' => 'nullable|string|max:255',
            'alternate_phone_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|max:255',
            'preferred_pronouns' => 'nullable|string|max:255',
            'disability_status' => 'boolean',
            'accommodation_needs' => 'nullable|string|required_if:disability_status,true',

            // Address Information Step
            /* address info should collect the following
            
            // Address Information
            $table->string('address_line1')->nullable()->comment('Primary address line (street number and name)');
            $table->string('address_line2')->nullable()->comment('Secondary address line (apartment, unit, etc.)');
            $table->string('city')->nullable()->comment('City or municipality');
            $table->string('province')->nullable()->comment('Province, state, or region');
            $table->string('postal_code')->nullable()->comment('Postal code or ZIP code');
            $table->string('country')->nullable()->comment('Country');
            
            // Status and Metadata
            $table->boolean('is_primary')->default(false)->comment('Indicates if this is the primary address');
            */
            'current_address' => 'required|array',
            'current_address.address_line1' => 'required|string|max:255',
            'current_address.address_line2' => 'nullable|string|max:50',
            'current_address.city' => 'required|string|max:255',
            'current_address.province' => 'required|string|max:255',
            'current_address.postal_code' => 'required|string|max:20',
            'current_address.country' => 'required|string|max:255',
            
            'use_different_mailing_address' => 'boolean',
            'mailing_address' => 'nullable|array|required_if:use_different_mailing_address,true',
            'mailing_address.address_line1' => 'nullable|string|max:255|required_if:use_different_mailing_address,true',
            'mailing_address.address_line2' => 'nullable|string|max:50',
            'mailing_address.city' => 'nullable|string|max:255|required_if:use_different_mailing_address,true',
            'mailing_address.province' => 'nullable|string|max:255|required_if:use_different_mailing_address,true',
            'mailing_address.postal_code' => 'nullable|string|max:20|required_if:use_different_mailing_address,true',
            'mailing_address.country' => 'nullable|string|max:255|required_if:use_different_mailing_address,true',

            // Employment Information Step
            /* Employment info should collect the following
            
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
            */
            'current_employment' => 'nullable|array',
            'current_employment.employment_status' => 'nullable|string|max:255',
            'current_employment.is_looking_for_work' => 'boolean',
            'current_employment.job_title' => 'nullable|string|max:255',
            'current_employment.employer_name' => 'nullable|string|max:255',
            'current_employment.employer_industry' => 'nullable|string|max:255',
            'current_employment.employment_start_date' => 'nullable|date',
            'current_employment.employment_end_date' => 'nullable|date|after:current_employment.employment_start_date',
            'current_employment.work_hours_per_week' => 'nullable|integer|min:0|max:168',
            'current_employment.monthly_income' => 'nullable|numeric|min:0',
            'current_employment.is_job_related_to_program' => 'boolean',
            'current_employment.previous_job_title' => 'nullable|string|max:255',
            'current_employment.previous_employer_name' => 'nullable|string|max:255',
            'current_employment.previous_employment_start_date' => 'nullable|date',
            'current_employment.previous_employment_end_date' => 'nullable|date|after:current_employment.previous_employment_start_date',
            'current_employment.reason_for_leaving' => 'nullable|string|max:255',
            'current_employment.career_interest_area' => 'nullable|string|max:255',
            'current_employment.desired_job_title' => 'nullable|string|max:255',
            'current_employment.career_readiness_level' => 'nullable|string|max:255',
            'current_employment.has_career_plan' => 'boolean',
            'current_employment.is_receiving_employment_insurance' => 'boolean',
            'current_employment.is_participating_in_work_study_program' => 'boolean',
            'current_employment.barriers_to_employment' => 'nullable|string',

            // Identity Information Step
            /* Identity info should collect the following:
            
            // Citizenship and Origin
            $table->string('citizenship_status')->nullable()->comment('Citizenship status: citizen, permanent_resident, temporary_resident, refugee');
            $table->string('country_of_birth')->nullable()->comment('Country where individual was born');
            $table->string('language_spoken_at_home')->nullable()->comment('Primary language spoken at home');
            $table->integer('years_in_country')->nullable()->comment('Number of years individual has lived in the country');
            
            // Immigration Status
            $table->boolean('refugee_status')->default(false)->comment('Indicates if individual has refugee status');
            $table->string('immigration_status')->nullable()->comment('Detailed immigration status: permanent_resident, refugee, temporary_resident, work_permit, study_permit');
            
            // Indigenous Identity
            $table->boolean('indigenous_status')->default(false)->comment('Indicates whether individual identifies as Indigenous (First Nations, Métis, Inuit, etc.)');
            $table->string('indigenous_group')->nullable()->comment('Specific Indigenous group: First Nations, Métis, Inuit, Other');
            $table->string('band_affiliation')->nullable()->comment('Name of the band or Indigenous community affiliated with');
            $table->string('indigenous_status_card_number')->nullable()->comment('Government-issued Indigenous status card number, if applicable');
            $table->boolean('is_registered_with_band')->default(false)->comment('Indicates if officially registered with Indigenous band');
            $table->boolean('on_reserve_resident')->default(false)->comment('Indicates if resides on a recognized Indigenous reserve');
            
            // Racial and Cultural Identity
            $table->string('racial_identity')->nullable()->comment('Self-identified racial group: Black, East Asian, South Asian, Latinx, White, Other');
            $table->boolean('is_visible_minority')->default(false)->comment('Indicates if identifies as member of visible minority group');
            
            // Support Services
            $table->boolean('receives_indigenous_support_services')->default(false)->comment('Indicates if receives support services for Indigenous students');
            $table->boolean('receives_minority_support_services')->default(false)->comment('Indicates if receives support services for minority groups');
            */
            'identity' => 'nullable|array',
            'identity.citizenship_status' => 'nullable|string|max:255',
            'identity.country_of_birth' => 'nullable|string|max:255',
            'identity.language_spoken_at_home' => 'nullable|string|max:255',
            'identity.years_in_country' => 'nullable|integer|min:0',
            'identity.refugee_status' => 'boolean',
            'identity.immigration_status' => 'nullable|string|max:255',
            'identity.indigenous_status' => 'boolean',
            'identity.indigenous_group' => 'nullable|string|max:255|required_if:identity.indigenous_status,true',
            'identity.band_affiliation' => 'nullable|string|max:255',
            'identity.indigenous_status_card_number' => 'nullable|string|max:255',
            'identity.is_registered_with_band' => 'boolean',
            'identity.on_reserve_resident' => 'boolean',
            'identity.racial_identity' => 'nullable|string|max:255',
            'identity.is_visible_minority' => 'boolean',
            'identity.receives_indigenous_support_services' => 'boolean',
            'identity.receives_minority_support_services' => 'boolean',

            'metadata' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'social_insurance_number.required' => 'Social Insurance Number is required.',
            'social_insurance_number.unique' => 'This Social Insurance Number is already registered.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email_address.required' => 'Email address is required.',
            'email_address.unique' => 'This email address is already registered.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            
            // Address validation messages
            'current_address.address_line1.required' => 'Current street address is required.',
            'current_address.city.required' => 'Current city is required.',
            'current_address.province.required' => 'Current province/state is required.',
            'current_address.postal_code.required' => 'Current postal code is required.',
            'current_address.country.required' => 'Current country is required.',
            
            'mailing_address.address_line1.required_if' => 'Mailing street address is required when using a different mailing address.',
            'mailing_address.city.required_if' => 'Mailing city is required when using a different mailing address.',
            'mailing_address.province.required_if' => 'Mailing province/state is required when using a different mailing address.',
            'mailing_address.postal_code.required_if' => 'Mailing postal code is required when using a different mailing address.',
            'mailing_address.country.required_if' => 'Mailing country is required when using a different mailing address.',
            
            // Employment validation messages
            'current_employment.employment_end_date.after' => 'Employment end date must be after the start date.',
            'current_employment.work_hours_per_week.min' => 'Hours per week must be at least 0.',
            'current_employment.work_hours_per_week.max' => 'Hours per week cannot exceed 168.',
            'current_employment.monthly_income.min' => 'Monthly income must be a positive number.',
            'current_employment.previous_employment_end_date.after' => 'Previous employment end date must be after the start date.',
            'current_employment.years_in_country.min' => 'Years in country must be a positive number.',
            
            // Identity validation messages
            'identity.indigenous_group.required_if' => 'Indigenous group is required when Indigenous status is selected.',
            'identity.years_in_country.min' => 'Years in country must be a positive number.',
            
            'accommodation_needs.required_if' => 'Accommodation details are required when requesting accessibility support.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure boolean fields are properly cast
        if (!$this->has('disability_status')) {
            $this->merge(['disability_status' => false]);
        }

        if (!$this->has('use_different_mailing_address')) {
            $this->merge(['use_different_mailing_address' => false]);
        }

        // Set nested boolean fields for identity
        $identity = $this->input('identity', []);
        if (!isset($identity['refugee_status'])) {
            $identity['refugee_status'] = false;
        }
        if (!isset($identity['indigenous_status'])) {
            $identity['indigenous_status'] = false;
        }
        if (!isset($identity['is_registered_with_band'])) {
            $identity['is_registered_with_band'] = false;
        }
        if (!isset($identity['on_reserve_resident'])) {
            $identity['on_reserve_resident'] = false;
        }
        if (!isset($identity['is_visible_minority'])) {
            $identity['is_visible_minority'] = false;
        }
        if (!isset($identity['receives_indigenous_support_services'])) {
            $identity['receives_indigenous_support_services'] = false;
        }
        if (!isset($identity['receives_minority_support_services'])) {
            $identity['receives_minority_support_services'] = false;
        }
        $this->merge(['identity' => $identity]);

        // Set employment boolean fields
        $employment = $this->input('current_employment', []);
        if (!isset($employment['is_looking_for_work'])) {
            $employment['is_looking_for_work'] = false;
        }
        if (!isset($employment['is_job_related_to_program'])) {
            $employment['is_job_related_to_program'] = false;
        }
        if (!isset($employment['has_career_plan'])) {
            $employment['has_career_plan'] = false;
        }
        if (!isset($employment['is_receiving_employment_insurance'])) {
            $employment['is_receiving_employment_insurance'] = false;
        }
        if (!isset($employment['is_participating_in_work_study_program'])) {
            $employment['is_participating_in_work_study_program'] = false;
        }
        $this->merge(['current_employment' => $employment]);
    }
}
