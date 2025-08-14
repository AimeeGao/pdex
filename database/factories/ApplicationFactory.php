<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->email(),
            'contact_phone' => $this->faker->phoneNumber(),
            'status' => 'active',
            'security_approval_status' => 'pending',
            'privacy_approval_status' => 'pending',
            'bcsc_enabled' => $this->faker->boolean(),
            'idir_enabled' => $this->faker->boolean(),
            'bceid_enabled' => $this->faker->boolean(),
            'bcsc_redirect_url' => $this->faker->url(),
            'idir_redirect_url' => $this->faker->url(),
            'bceid_redirect_url' => $this->faker->url(),
            'api_key' => 'pdex_' . Str::random(32),
            'api_secret' => Str::random(64),
            'comments' => $this->faker->paragraph(),
            'stra_provided' => $this->faker->boolean(),
            'pia_provided' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the application is security approved.
     */
    public function securityApproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'security_approval_status' => 'approved',
            'security_approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the application is privacy approved.
     */
    public function privacyApproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'privacy_approval_status' => 'approved',
            'privacy_approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the application is fully approved (both security and privacy).
     */
    public function fullyApproved(): static
    {
        return $this->securityApproved()->privacyApproved();
    }
}
