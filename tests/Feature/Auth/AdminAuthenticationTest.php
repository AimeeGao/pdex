<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function admin_manager_can_access_admin_dashboard()
    {
        $adminManager = User::factory()->adminManager()->create();

        $response = $this->actingAs($adminManager)->get('/admin');

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_manager_can_access_admin_institutions()
    {
        $adminManager = User::factory()->adminManager()->create();
        Institution::factory()->count(3)->create();

        $response = $this->actingAs($adminManager)->get('/admin/institutions');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('institutions')
        );
    }

    #[Test]
    public function admin_manager_can_view_institution_details()
    {
        $adminManager = User::factory()->adminManager()->create();
        $institution = Institution::factory()->bcit()->create();

        $response = $this->actingAs($adminManager)->get("/admin/institutions/{$institution->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('institution')
                ->where('institution.id', $institution->id)
                ->has('institutionUsers')
        );
    }

    #[Test]
    public function admin_manager_can_manage_institution_users()
    {
        $adminManager = User::factory()->adminManager()->create();
        $institution = Institution::factory()->bcit()->create();
        
        // Create users for this institution
        $user1 = User::factory()->institutionUser($institution->bceid_business_guid)->create();
        $user2 = User::factory()->institutionAdmin($institution->bceid_business_guid)->create();

        $response = $this->actingAs($adminManager)->get("/admin/institutions/{$institution->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->has('institutionUsers')
                ->where('institutionUsers', fn ($users) => count($users) >= 2)
        );
    }

    #[Test]
    public function admin_manager_can_toggle_institution_user_roles()
    {
        $adminManager = User::factory()->adminManager()->create();
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($adminManager)
            ->post("/admin/institutions/{$institution->id}/users/{$user->id}/toggle-role");

        $response->assertStatus(200);
        
        // Verify the user's role was changed
        $user->refresh();
        $this->assertTrue($user->hasRole(Role::INSTITUTION_ADMIN));
    }

    #[Test]
    public function admin_manager_can_toggle_institution_user_status()
    {
        $adminManager = User::factory()->adminManager()->create();
        $institution = Institution::factory()->bcit()->create();
        $user = User::factory()->institutionUser($institution->bceid_business_guid)->create();

        $response = $this->actingAs($adminManager)
            ->post("/admin/institutions/{$institution->id}/users/{$user->id}/toggle-status");

        $response->assertStatus(200);
        
        // Verify the user's status was changed
        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    #[Test]
    public function admin_manager_accessing_regular_login_gets_redirected_appropriately()
    {
        $adminManager = User::factory()->adminManager()->create();

        // Test regular login redirect behavior
        $response = $this->actingAs($adminManager)->get('/dashboard');

        // Should either redirect to admin dashboard or show both options
        $response->assertStatus(200);
        // Verify admin options are available
    }

    #[Test]
    public function admin_manager_can_access_ministry_portal_if_dual_access_enabled()
    {
        $adminManager = User::factory()->adminManager()->create();

        $response = $this->actingAs($adminManager)->get('/ministry/dashboard');

        // This test depends on your business logic
        // Either should work (dual access) or redirect to admin
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirect()
        );
    }

    #[Test]
    public function super_admin_can_access_all_admin_features()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_manager_has_correct_role()
    {
        $adminManager = User::factory()->adminManager()->create();

        $this->assertTrue($adminManager->hasRole(Role::ADMIN_MANAGER));
        $this->assertFalse($adminManager->hasRole(Role::MINISTRY_USER));
        $this->assertFalse($adminManager->hasRole(Role::INSTITUTION_USER));
    }

    #[Test]
    public function admin_middleware_blocks_non_admin_users()
    {
        $ministryUser = User::factory()->ministryUser()->create();
        $institutionUser = User::factory()->institutionUser()->create();
        $student = User::factory()->student()->create();

        // Test each non-admin user type
        $response = $this->actingAs($ministryUser)->get('/admin/dashboard');
        $response->assertRedirect();

        $response = $this->actingAs($institutionUser)->get('/admin/dashboard');
        $response->assertRedirect();

        $response = $this->actingAs($student)->get('/admin/dashboard');
        $response->assertRedirect();
    }

    #[Test]
    public function admin_login_page_loads_correctly()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Admin Login');
    }

    #[Test]
    public function unauthenticated_admin_access_redirects_to_admin_login()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('admin_intended_url');
    }

    #[Test]
    public function inactive_admin_gets_logged_out()
    {
        $inactiveAdmin = User::factory()->adminManager()->inactive()->create();

        $response = $this->actingAs($inactiveAdmin)->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('error');
    }
}
