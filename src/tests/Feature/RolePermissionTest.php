<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_can_be_created()
    {
        $role = Role::create(['name' => 'admin']);

        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    public function test_permissions_can_be_created()
    {
        $permission = Permission::create(['name' => 'edit articles']);

        $this->assertDatabaseHas('permissions', ['name' => 'edit articles']);
    }

    public function test_user_can_be_assigned_a_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_user_can_be_assigned_a_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'edit articles']);

        $user->givePermissionTo('edit articles');

        $this->assertTrue($user->hasPermissionTo('edit articles'));
    }

    public function test_user_with_permission_can_access_protected_route()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'view dashboard']);
        $user->givePermissionTo('view dashboard');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_without_permission_cannot_access_protected_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(403);
    }
}

