<?php

declare(strict_types=1);

namespace Tests\Feature\Roles;

use App\Models\User;
use Tests\Support\AuthenticationAdmin;
use Tests\TestBase;

final class ListRolesTest extends TestBase
{
    use AuthenticationAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /** @test */
    public function user_without_permissions_can_not_access_to_users_list()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        $this->assertFalse($user->hasPermissionTo('role list'));

        try {
            $this->actingAs($user);
            $response = $this->get(route('roles.index'));
        } catch (\Throwable $th) {
            $this->assertEquals('User does not have any of the necessary access rights.', $th->getMessage());
        }
    }

    /** @test */
    public function user_can_access_to_see_list_roles()
    {
        $this->authenticated()
            ->get( route('roles.index') )
            ->assertOk();
    }
}
