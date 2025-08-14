<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\InstitutionRelationship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstitutionRelationship>
 */
class InstitutionRelationshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_keys(InstitutionRelationship::getRelationshipTypes());
        $type = fake()->randomElement($types);
        
        // Generate appropriate reasons based on type
        $reasons = [
            InstitutionRelationship::TYPE_GEOGRAPHIC => [
                'Same city',
                'Same metropolitan area',
                'Same economic region',
                'Neighboring cities',
            ],
            InstitutionRelationship::TYPE_ACADEMIC => [
                'Research collaboration',
                'Joint degree programs',
                'Faculty exchange',
                'Shared resources',
            ],
            InstitutionRelationship::TYPE_PARTNERSHIP => [
                'Strategic alliance',
                'Resource sharing',
                'Joint initiatives',
                'Collaborative programs',
            ],
            InstitutionRelationship::TYPE_CONSORTIUM => [
                'BC higher education consortium',
                'Research consortium',
                'Technology consortium',
                'Regional education network',
            ],
            InstitutionRelationship::TYPE_TRANSFER_AGREEMENT => [
                'Credit transfer agreement',
                'Pathway programs',
                'Articulation agreement',
                'Bridge programs',
            ],
        ];

        $reason = fake()->randomElement($reasons[$type] ?? ['General relationship']);

        return [
            'institution_a_guid' => Institution::factory(),
            'institution_b_guid' => Institution::factory(),
            'relationship_type' => $type,
            'relationship_reason' => $reason,
            'description' => fake()->optional(0.6)->paragraph(),
            'is_active' => fake()->boolean(90), // 90% chance of being active
            'effective_date' => fake()->optional(0.3)->date('Y-m-d', '2020-01-01'),
            'expiry_date' => fake()->optional(0.2)->date('Y-m-d', '+5 years'),
            'metadata' => fake()->optional(0.4)->randomElement([
                ['contact_person' => fake()->name(), 'department' => 'Academic Affairs'],
                ['priority' => 'high', 'review_date' => fake()->date('Y-m-d', '+1 year')],
                ['budget' => fake()->numberBetween(10000, 500000), 'currency' => 'CAD'],
            ]),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Create a geographic relationship
     */
    public function geographic(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship_type' => InstitutionRelationship::TYPE_GEOGRAPHIC,
            'relationship_reason' => fake()->randomElement([
                'Same city', 'Same metropolitan area', 'Same economic region'
            ]),
        ]);
    }

    /**
     * Create an academic partnership
     */
    public function academic(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship_type' => InstitutionRelationship::TYPE_ACADEMIC,
            'relationship_reason' => fake()->randomElement([
                'Research collaboration', 'Joint degree programs', 'Faculty exchange'
            ]),
        ]);
    }

    /**
     * Create a transfer agreement
     */
    public function transferAgreement(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship_type' => InstitutionRelationship::TYPE_TRANSFER_AGREEMENT,
            'relationship_reason' => fake()->randomElement([
                'Credit transfer agreement', 'Pathway programs', 'Articulation agreement'
            ]),
        ]);
    }
}
