<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminUnauthorizedToastHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_forbidden_admin_route_redirects_back_with_toast_for_html_requests(): void
    {
        Permission::create([
            'name' => 'sales.view',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('home'))
            ->get(route('admin.analytics.index'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('toast_type', 'error');
        $response->assertSessionHas('toast_message', 'You are not authorized to perform this action.');
    }

    public function test_forbidden_admin_route_returns_json_403_for_json_requests(): void
    {
        Permission::create([
            'name' => 'sales.view',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('admin.analytics.index'));

        $response->assertStatus(403);
        $response->assertJsonStructure(['message']);
    }

    public function test_missing_admin_resource_redirects_back_with_not_found_toast(): void
    {
        Permission::create([
            'name' => 'inventory.view',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();
        $user->assignRole('admin');
        $user->givePermissionTo('inventory.view');

        $response = $this->actingAs($user)
            ->from(route('admin.products.index'))
            ->get(route('admin.products.show', 999999));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('toast_type', 'error');
        $response->assertSessionHas('toast_message', 'The requested admin resource was not found.');
    }
}
