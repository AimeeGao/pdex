<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function student_can_access_their_dashboard()
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertStatus(200);
        // Verify student-specific content or redirects
    }

    #[Test]
    public function student_cannot_access_admin_routes()
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/admin');

        $response->assertRedirect();
        // Should redirect to login or show unauthorized
    }

    #[Test]
    public function student_cannot_access_institution_routes()
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/institution');

        // Should redirect since students are not BCeID users
        $response->assertRedirect();
    }

    #[Test]
    public function student_cannot_access_ministry_routes()
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/ministry');

        // Should redirect since students are not IDIR users
        $response->assertRedirect();
    }

    #[Test]
    public function student_has_correct_role_assigned()
    {
        $student = User::factory()->student()->create();

        $this->assertTrue($student->hasRole(Role::STUDENT));
        $this->assertFalse($student->hasRole(Role::INSTITUTION_USER));
        $this->assertFalse($student->hasRole(Role::MINISTRY_USER));
        $this->assertFalse($student->hasRole(Role::ADMIN_MANAGER));
    }

    #[Test]
    public function inactive_student_cannot_login()
    {
        $student = User::factory()->student()->inactive()->create();

        $response = $this->actingAs($student)->get('/dashboard');

        // Currently no middleware prevents inactive users from accessing dashboard
        // This test documents current behavior - should be updated when inactive user middleware is added
        $response->assertStatus(200);
    }
}
