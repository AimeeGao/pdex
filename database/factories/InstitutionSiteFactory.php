<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\InstitutionSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstitutionSite>
 */
class InstitutionSiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_guid' => Institution::factory(),
            'operating_name' => fake()->optional()->company() . ' Campus',
            
            // Contact Information
            'primary_phone' => fake()->phoneNumber(),
            'primary_email' => fake()->companyEmail(),
            'website' => fake()->optional()->url(),
            
            // Regulation & Compliance
            'regulating_body' => fake()->randomElement(InstitutionSite::getRegulatingBodies()),
            'other_regulating_body' => fake()->optional()->company(),
            'established_date' => fake()->date('Y-m-d', '2020-01-01'),
            'info_sharing_agreement' => fake()->boolean(70), // 70% chance of true
            
            // Primary Contact Person
            'contact_first_name' => fake()->firstName(),
            'contact_last_name' => fake()->lastName(),
            'contact_email' => fake()->email(),
            'contact_phone' => fake()->phoneNumber(),
            
            // Address Information
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'province_state' => 'British Columbia',
            'country' => 'Canada',
            'postal_code' => fake()->regexify('[A-Z]\d[A-Z] \d[A-Z]\d'),
            
            // Status & Classification
            'public' => fake()->boolean(60), // 60% chance of public
            'active_status' => fake()->boolean(80), // 80% chance of active
            'standing_status' => fake()->randomElement(InstitutionSite::getStandingStatuses()),
            'economic_region' => fake()->randomElement(InstitutionSite::getEconomicRegions()),
            
            // Metadata
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
