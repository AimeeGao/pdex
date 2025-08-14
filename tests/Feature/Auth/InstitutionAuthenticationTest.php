<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstitutionAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function institution_user_can_access_institution_dashboard()
    {
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($user)->get('/institution');

        $response->assertStatus(200);
    }

    #[Test]
    public function institution_user_cannot_access_admin_routes()
    {
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect();
    }

    #[Test]
    public function institution_user_cannot_access_ministry_routes()
    {
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($user)->get('/ministry');

        $response->assertRedirect();
    }

    #[Test]
    public function institution_user_cannot_access_institution_settings()
    {
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($user)->get('/institution/settings');

        // Institution users should not be able to access settings
        $response->assertStatus(403);
    }

    #[Test]
    public function institution_admin_can_access_institution_dashboard()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)->get('/institution');

        $response->assertStatus(200);
    }

    #[Test]
    public function institution_admin_can_access_institution_settings()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)->get('/institution/settings');

        $response->assertStatus(200);
    }

    #[Test]
    public function institution_admin_cannot_access_admin_routes()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertRedirect();
    }

    #[Test]
    public function institution_admin_cannot_access_ministry_routes()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)->get('/ministry');

        $response->assertRedirect();
    }

    #[Test]
    public function institution_users_have_correct_roles()
    {
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $this->assertTrue($user->hasRole(Role::INSTITUTION_USER));
        $this->assertFalse($user->hasRole(Role::INSTITUTION_ADMIN));
        
        $this->assertTrue($admin->hasRole(Role::INSTITUTION_ADMIN));
        $this->assertFalse($admin->hasRole(Role::ADMIN_MANAGER));
    }

    #[Test]
    public function institution_admin_can_manage_institution_users()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();
        
        // Create some users for the same institution
        $user1 = User::factory()->institutionUser($institution->bceid_business_guid)->create();
        $user2 = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)->get('/institution/settings');

        $response->assertStatus(200);
        // Should see institution users in the response
        $response->assertInertia(fn ($page) => 
            $page->has('institutionUsers')
                ->where('institutionUsers', fn ($users) => count($users) >= 2)
        );
    }

    #[Test]
    public function institution_admin_can_toggle_user_roles()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)
            ->post("/admin/institutions/{$institution->id}/users/{$user->id}/toggle-role");

        $response->assertStatus(200);
        
        // Verify the user's role was changed
        $user->refresh();
        $this->assertTrue($user->hasRole(Role::INSTITUTION_ADMIN));
    }

    #[Test]
    public function institution_admin_can_toggle_user_status()
    {
        $institution = Institution::factory()->bcit()->create();
        $admin = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($admin)
            ->post("/admin/institutions/{$institution->id}/users/{$user->id}/toggle-status");

        $response->assertStatus(200);
        
        // Verify the user's status was changed
        $user->refresh();
        $this->assertFalse($user->is_active);
    }
}
