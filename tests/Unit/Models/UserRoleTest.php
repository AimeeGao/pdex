<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Role;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRoleTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function user_can_have_roles_assigned()
    {
        $user = User::factory()->create();
        $role = Role::where('name', Role::STUDENT)->first();

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole(Role::STUDENT));
    }

    #[Test]
    public function user_can_have_multiple_roles()
    {
        $user = User::factory()->create();
        $studentRole = Role::where('name', Role::STUDENT)->first();
        $ministryRole = Role::where('name', Role::MINISTRY_USER)->first();

        $user->roles()->attach([$studentRole->id, $ministryRole->id]);

        $this->assertTrue($user->hasRole(Role::STUDENT));
        $this->assertTrue($user->hasRole(Role::MINISTRY_USER));
    }

    #[Test]
    public function user_factory_student_creates_user_with_student_role()
    {
        $student = User::factory()->student()->create();

        $this->assertTrue($student->hasRole(Role::STUDENT));
        $this->assertNotNull($student->bcsc_user_guid);
        $this->assertEquals('bcsc', $student->identity_provider);
    }

    #[Test]
    public function user_factory_institution_user_creates_user_with_institution_role()
    {
        $institutionUser = User::factory()->institutionUser('TEST-GUID-123')->create();

        $this->assertTrue($institutionUser->hasRole(Role::INSTITUTION_USER));
        $this->assertEquals('TEST-GUID-123', $institutionUser->bceid_business_guid);
        $this->assertNotNull($institutionUser->bceid_username);
        $this->assertEquals('bceid_business', $institutionUser->identity_provider);
    }

    #[Test]
    public function user_factory_institution_admin_creates_user_with_admin_role()
    {
        $institutionAdmin = User::factory()->institutionAdmin('TEST-GUID-123')->create();

        $this->assertTrue($institutionAdmin->hasRole(Role::INSTITUTION_ADMIN));
        $this->assertEquals('TEST-GUID-123', $institutionAdmin->bceid_business_guid);
    }

    #[Test]
    public function user_factory_ministry_user_creates_user_with_ministry_role()
    {
        $ministryUser = User::factory()->ministryUser()->create();

        $this->assertTrue($ministryUser->hasRole(Role::MINISTRY_USER));
        $this->assertStringContainsString('@IDIR', $ministryUser->idir_username);
        $this->assertNotNull($ministryUser->idir_user_guid);
        $this->assertEquals('idir', $ministryUser->identity_provider);
        $this->assertStringContainsString('gov.bc.ca', $ministryUser->email);
    }

    #[Test]
    public function user_factory_admin_manager_creates_user_with_admin_manager_role()
    {
        $adminManager = User::factory()->adminManager()->create();

        $this->assertTrue($adminManager->hasRole(Role::ADMIN_MANAGER));
        $this->assertStringContainsString('@IDIR', $adminManager->idir_username);
        $this->assertEquals('idir', $adminManager->identity_provider);
    }

    #[Test]
    public function user_factory_super_admin_creates_user_with_super_admin_role()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->assertTrue($superAdmin->hasRole(Role::SUPER_ADMIN));
        $this->assertEquals('idir', $superAdmin->identity_provider);
    }

    #[Test]
    public function user_has_any_role_method_works_correctly()
    {
        $user = User::factory()->create();
        $studentRole = Role::where('name', Role::STUDENT)->first();
        $ministryRole = Role::where('name', Role::MINISTRY_USER)->first();

        $user->roles()->attach($studentRole);

        $this->assertTrue($user->hasAnyRole([Role::STUDENT, Role::MINISTRY_USER]));
        $this->assertTrue($user->hasAnyRole([Role::STUDENT]));
        $this->assertFalse($user->hasAnyRole([Role::MINISTRY_USER, Role::ADMIN_MANAGER]));
    }

    #[Test]
    public function inactive_user_factory_creates_inactive_user()
    {
        $inactiveUser = User::factory()->inactive()->create();

        $this->assertFalse($inactiveUser->is_active);
    }

    #[Test]
    public function role_constants_are_defined_correctly()
    {
        $this->assertEquals('Student', Role::STUDENT);
        $this->assertEquals('Institution User', Role::INSTITUTION_USER);
        $this->assertEquals('Institution Admin', Role::INSTITUTION_ADMIN);
        $this->assertEquals('Ministry User', Role::MINISTRY_USER);
        $this->assertEquals('Ministry Admin', Role::MINISTRY_ADMIN);
        $this->assertEquals('Super Admin', Role::SUPER_ADMIN);
        $this->assertEquals('Admin Manager', Role::ADMIN_MANAGER);
    }

    #[Test]
    public function role_can_manage_institutions_method_works()
    {
        $this->assertTrue(Role::canManageInstitutions(Role::SUPER_ADMIN));
        $this->assertTrue(Role::canManageInstitutions(Role::ADMIN_MANAGER));
        $this->assertFalse(Role::canManageInstitutions(Role::MINISTRY_USER));
        $this->assertFalse(Role::canManageInstitutions(Role::INSTITUTION_USER));
        $this->assertFalse(Role::canManageInstitutions(Role::STUDENT));
    }

    #[Test]
    public function get_admin_roles_returns_correct_roles()
    {
        $adminRoles = Role::getAdminRoles();

        $expectedRoles = [
            Role::SUPER_ADMIN,
            Role::ADMIN_MANAGER,
            Role::APPLICATION_MANAGER,
            Role::SECURITY_OFFICER,
            Role::PRIVACY_OFFICER,
            Role::ADMIN_GUEST,
        ];

        $this->assertEquals($expectedRoles, $adminRoles);
    }
}
