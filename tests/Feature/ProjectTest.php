<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_project_endpoints(): void
    {
        $project = Project::factory()->create();

        $this->getJson('/api/projects')->assertUnauthorized();
        $this->postJson('/api/projects', [
            'name' => 'New Project',
        ])->assertUnauthorized();
        $this->getJson("/api/projects/{$project->id}")->assertUnauthorized();
        $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated',
            'status' => 'active',
        ])->assertUnauthorized();
        $this->deleteJson("/api/projects/{$project->id}")->assertUnauthorized();
    }

    public function test_user_can_list_only_their_own_projects(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $ownProject = Project::factory()->for($user)->create(['name' => 'Mine']);
        Project::factory()->for($other)->create(['name' => 'Theirs']);

        $response = $this->actingAs($user)->getJson('/api/projects');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownProject->id)
            ->assertJsonPath('data.0.name', 'Mine')
            ->assertJsonMissing(['name' => 'Theirs']);
    }

    public function test_admin_can_list_all_projects(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Project::factory()->for($admin)->create(['name' => 'Admin Project']);
        Project::factory()->for($user)->create(['name' => 'User Project']);

        $response = $this->actingAs($admin)->getJson('/api/projects');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_create_a_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/projects', [
            'name' => 'Launch App',
            'description' => 'First release',
            'status' => 'active',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Launch App')
            ->assertJsonPath('data.description', 'First release')
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('projects', [
            'name' => 'Launch App',
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function test_project_store_requires_a_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/projects', [
            'description' => 'Missing name',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_view_their_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'name' => 'Owned Project',
        ]);

        $this->actingAs($user)
            ->getJson("/api/projects/{$project->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonPath('data.name', 'Owned Project');
    }

    public function test_user_cannot_view_another_users_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/projects/{$project->id}")
            ->assertForbidden();
    }

    public function test_admin_can_view_another_users_project(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create(['name' => 'User Owned']);

        $this->actingAs($admin)
            ->getJson("/api/projects/{$project->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'User Owned');
    }

    public function test_user_can_update_their_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'name' => 'Old Name',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->putJson("/api/projects/{$project->id}", [
            'name' => 'New Name',
            'description' => 'Updated description',
            'status' => 'archived',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.status', 'archived');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'New Name',
            'status' => 'archived',
        ]);
    }

    public function test_user_cannot_update_another_users_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Original']);

        $this->actingAs($user)
            ->putJson("/api/projects/{$project->id}", [
                'name' => 'Hijacked',
                'status' => 'active',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Original',
        ]);
    }

    public function test_admin_can_update_another_users_project(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create(['name' => 'Original']);

        $this->actingAs($admin)
            ->putJson("/api/projects/{$project->id}", [
                'name' => 'Admin Updated',
                'description' => null,
                'status' => 'archived',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Admin Updated');
    }

    public function test_user_can_delete_their_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->deleteJson("/api/projects/{$project->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user)
            ->deleteJson("/api/projects/{$project->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_admin_can_delete_another_users_project(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/projects/{$project->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_regular_user_cannot_access_admin_area(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_area(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }
}
