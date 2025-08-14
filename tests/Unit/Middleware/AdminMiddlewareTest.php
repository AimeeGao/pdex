<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected AdminMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->middleware = new AdminMiddleware();
    }

    #[Test]
    public function unauthenticated_user_is_redirected_to_admin_login()
    {
        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContains('/admin/login', $response->headers->get('Location'));
    }

    #[Test]
    public function admin_intended_url_is_stored_for_unauthenticated_access()
    {
        $request = Request::create('/admin/dashboard', 'GET');
        
        $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals('/admin/dashboard', session('admin_intended_url'));
    }

    #[Test]
    public function inactive_user_is_logged_out_and_redirected()
    {
        $inactiveUser = User::factory()->adminManager()->inactive()->create();
        Auth::login($inactiveUser);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function admin_manager_can_pass_through_middleware()
    {
        $adminManager = User::factory()->adminManager()->create();
        Auth::login($adminManager);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertEquals('Success', $response->getContent());
    }

    #[Test]
    public function super_admin_can_pass_through_middleware()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        Auth::login($superAdmin);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertEquals('Success', $response->getContent());
    }

    #[Test]
    public function ministry_user_is_blocked_and_logged_out()
    {
        $ministryUser = User::factory()->ministryUser()->create();
        Auth::login($ministryUser);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function institution_user_is_blocked_and_logged_out()
    {
        $institutionUser = User::factory()->institutionUser()->create();
        Auth::login($institutionUser);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function student_is_blocked_and_logged_out()
    {
        $student = User::factory()->student()->create();
        Auth::login($student);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Should not reach here');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(Auth::check());
    }

    #[Test]
    public function all_admin_roles_can_access_admin_routes()
    {
        $adminRoles = [
            Role::SUPER_ADMIN,
            Role::ADMIN_MANAGER,
            Role::APPLICATION_MANAGER,
            Role::SECURITY_OFFICER,
            Role::PRIVACY_OFFICER,
            Role::ADMIN_GUEST,
        ];

        foreach ($adminRoles as $roleName) {
            $user = User::factory()->create();
            $role = Role::where('name', $roleName)->first();
            $user->roles()->attach($role);
            
            Auth::login($user);

            $request = Request::create('/admin/dashboard', 'GET');
            
            $response = $this->middleware->handle($request, function () {
                return response('Success');
            });

            $this->assertEquals('Success', $response->getContent(), "Role {$roleName} should have access");
            
            Auth::logout();
        }
    }

    #[Test]
    public function user_with_multiple_roles_including_admin_can_access()
    {
        $user = User::factory()->create();
        
        // Give user both ministry and admin roles
        $ministryRole = Role::where('name', Role::MINISTRY_USER)->first();
        $adminRole = Role::where('name', Role::ADMIN_MANAGER)->first();
        
        $user->roles()->attach([$ministryRole->id, $adminRole->id]);
        
        Auth::login($user);

        $request = Request::create('/admin/dashboard', 'GET');
        
        $response = $this->middleware->handle($request, function () {
            return response('Success');
        });

        $this->assertEquals('Success', $response->getContent());
    }
}
