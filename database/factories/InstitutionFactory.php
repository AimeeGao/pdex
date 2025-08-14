<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institution>
 */
class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $institutionNames = [
            'University of British Columbia',
            'Simon Fraser University',
            'University of Victoria',
            'British Columbia Institute of Technology',
            'Vancouver Community College',
            'Douglas College',
            'Langara College',
            'Northern Lights College',
            'Okanagan College',
            'Thompson Rivers University',
        ];

        $cities = [
            'Vancouver', 'Victoria', 'Burnaby', 'Richmond', 'Surrey', 'Langley',
            'Kelowna', 'Kamloops', 'Prince George', 'Nanaimo', 'Abbotsford'
        ];

        return [
            'guid' => str_replace('-', '', fake()->uuid()),
            'bceid_business_guid' => fake()->uuid(),
            'legal_operating_name' => fake()->randomElement($institutionNames) . ' (' . fake()->city() . ')',
            'institution_type' => fake()->randomElement(Institution::getInstitutionTypes()),
            'dli' => fake()->optional(0.8)->regexify('O[0-9]{8,12}'), // 80% chance of having a DLI number
            'active_status' => fake()->boolean(85), // 85% chance of being active
        ];
    }

    /**
     * Indicate that the institution is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active_status' => true,
        ]);
    }

    /**
     * Indicate that the institution is a university.
     */
    public function university(): static
    {
        return $this->state(fn (array $attributes) => [
            'institution_type' => Institution::TYPE_UNIVERSITY,
        ]);
    }

    /**
     * Indicate that the institution is a college.
     */
    public function college(): static
    {
        return $this->state(fn (array $attributes) => [
            'institution_type' => Institution::TYPE_COLLEGE,
        ]);
    }

    /**
     * Create a specific BCIT institution for testing.
     */
    public function bcit(): static
    {
        return $this->state(fn (array $attributes) => [
            'legal_operating_name' => 'British Columbia Institute of Technology',
            'bceid_business_guid' => 'BCIT-GUID-004',
            'institution_type' => Institution::TYPE_INSTITUTE ?? 'Institute',
            'active_status' => true,
            'dli' => 'O19395134131',
        ]);
    }

    /**
     * Create a specific UBC institution for testing.
     */
    public function ubc(): static
    {
        return $this->state(fn (array $attributes) => [
            'legal_operating_name' => 'University of British Columbia',
            'bceid_business_guid' => 'UBC-GUID-005',
            'institution_type' => Institution::TYPE_UNIVERSITY,
            'active_status' => true,
            'dli' => 'O19334818892',
        ]);
    }

    /**
     * Create an institution with a specific business GUID for testing.
     */
    public function withBusinessGuid(string $businessGuid): static
    {
        return $this->state(fn (array $attributes) => [
            'bceid_business_guid' => $businessGuid,
        ]);
    }
}
