<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Etec;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorEtecScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_only_sees_students_from_their_active_etec(): void
    {
        $etec1 = Etec::create(['code' => 101, 'name' => 'Etec Zona Leste']);
        $etec2 = Etec::create(['code' => 102, 'name' => 'Etec Zona Sul']);

        $coordinator = User::factory()->create([
            'name' => 'COORDENADOR.TESTE',
            'role' => Role::Coordenador,
            'email' => 'coord@cps.sp.gov.br',
            'first_access_status' => 'concluido',
        ]);
        $coordinator->etecs()->attach($etec1->id, ['role' => Role::Coordenador->value]);

        $student1 = User::factory()->create([
            'name' => 'ALUNO.ETEC1',
            'role' => Role::Aluno,
            'email' => 'aluno1@aluno.cps.sp.gov.br',
            'first_access_status' => 'concluido',
        ]);
        $student1->etecs()->attach($etec1->id, ['role' => Role::Aluno->value, 'rm' => 1111111]);

        $student2 = User::factory()->create([
            'name' => 'ALUNO.ETEC2',
            'role' => Role::Aluno,
            'email' => 'aluno2@aluno.cps.sp.gov.br',
            'first_access_status' => 'concluido',
        ]);
        $student2->etecs()->attach($etec2->id, ['role' => Role::Aluno->value, 'rm' => 2222222]);

        $response = $this->actingAs($coordinator)
            ->withSession(['etec_ativa' => $etec1->id])
            ->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('ALUNO.ETEC1');
        $response->assertDontSee('ALUNO.ETEC2');

        $filterResponse = $this->actingAs($coordinator)
            ->withSession(['etec_ativa' => $etec1->id])
            ->get(route('users.filtrar'));

        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('ALUNO.ETEC1');
        $filterResponse->assertDontSee('ALUNO.ETEC2');
    }
}
