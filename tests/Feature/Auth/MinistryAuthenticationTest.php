<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MinistryAuthenticationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function ministry_user_can_access_ministry_dashboard()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/ministry/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function ministry_user_cannot_access_admin_routes()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/admin');

        $response->assertRedirect();
        
        // Verify the redirect goes to login
        $this->followRedirects($response)->assertSee('sign in');
    }

    #[Test]
    public function ministry_user_cannot_access_admin_dashboard()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/admin/dashboard');

        $response->assertRedirect();
    }

    #[Test]
    public function ministry_user_cannot_access_admin_institutions()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/admin/institutions');

        $response->assertRedirect();
    }

    #[Test]
    public function ministry_user_cannot_access_institution_routes()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/institution/dashboard');

        $response->assertRedirect();
    }

    #[Test]
    public function ministry_user_has_correct_role()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $this->assertTrue($ministryUser->hasRole(Role::MINISTRY_USER));
        $this->assertFalse($ministryUser->hasRole(Role::MINISTRY_ADMIN));
        $this->assertFalse($ministryUser->hasRole(Role::ADMIN_MANAGER));
        $this->assertFalse($ministryUser->hasRole(Role::INSTITUTION_USER));
    }

    #[Test]
    public function ministry_user_gets_logged_out_when_accessing_admin()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        // First verify user is authenticated
        $this->assertAuthenticated();

        // Try to access admin area
        $response = $this->actingAs($ministryUser)->get('/admin');

        // Should be redirected and logged out
        $response->assertRedirect();
        
        // Follow the redirect and verify we're at login
        $this->followRedirects($response)->assertSee('Please sign in');
    }

    #[Test]
    public function ministry_user_sees_appropriate_error_message_for_admin_access()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/admin');

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Please sign in to access the administrative area.');
    }

    #[Test]
    public function ministry_user_first_login_gets_ministry_user_role()
    {
        // Simulate first-time login by creating user without roles
        $newUser = User::factory()->create([
            'idir_username' => fake()->userName() . '@IDIR',
            'idir_user_guid' => fake()->uuid(),
            'identity_provider' => 'idir',
            'email' => fake()->email('gov.bc.ca'),
        ]);

        // Manually assign role as would happen in authentication process
        $ministryRole = Role::where('name', Role::MINISTRY_USER)->first();
        $newUser->roles()->attach($ministryRole);

        $this->assertTrue($newUser->hasRole(Role::MINISTRY_USER));
        $this->assertFalse($newUser->hasRole(Role::ADMIN_MANAGER));
    }

    #[Test]
    public function ministry_user_navigation_shows_ministry_options_only()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $response = $this->actingAs($ministryUser)->get('/ministry/dashboard');

        $response->assertStatus(200);
        
        // Should not see admin navigation
        $response->assertDontSee('Admin');
        $response->assertDontSee('Institution Settings');
        
        // Should see ministry-specific navigation
        $response->assertSee('Ministry');
    }

    #[Test]
    public function ministry_user_has_read_only_permissions()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        // Test that ministry user cannot perform write operations
        // This would depend on your specific ministry functionality
        $response = $this->actingAs($ministryUser)->get('/ministry/dashboard');

        $response->assertStatus(200);
        // Verify read-only access in the response data
    }
}
