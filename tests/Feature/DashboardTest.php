<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_without_etec_can_render_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => Role::Professor,
            'first_access_status' => null,
        ]);

        $response = $this->actingAs($teacher)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_coordinator_without_etec_can_render_dashboard(): void
    {
        $coordinator = User::factory()->create([
            'role' => Role::Coordenador,
            'first_access_status' => null,
        ]);

        $response = $this->actingAs($coordinator)->get(route('dashboard'));

        $response->assertStatus(200);
    }
}
