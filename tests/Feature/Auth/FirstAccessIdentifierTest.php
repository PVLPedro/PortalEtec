<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FirstAccessIdentifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_gets_name_and_identifier_generated_upon_email_verification(): void
    {
        $student = User::factory()->create([
            'name' => 'Nome Provisorio',
            'email' => null,
            'role' => Role::Aluno,
            'first_access_status' => 'email_pendente',
            'identifier' => null,
        ]);

        $verification = EmailVerification::create([
            'user_id' => $student->id,
            'email' => 'mariana.silva@aluno.cps.sp.gov.br',
            'code' => Hash::make('123456'),
            'purpose' => 'first_access',
            'sent_at' => now(),
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->actingAs($student)->post(route('first-access.email.verify'), [
            'code' => '123456',
        ]);

        $response->assertRedirect(route('first-access.password'));

        $student->refresh();

        $this->assertSame('MARIANA.SILVA', $student->name);
        $this->assertSame('mariana.silva@aluno.cps.sp.gov.br', $student->email);
        $this->assertNotNull($student->identifier);
        $this->assertMatchesRegularExpression('/^\d{4}$/', $student->identifier);
        $this->assertSame("#{$student->identifier}", $student->formatted_identifier);
        $this->assertSame('senha_pendente', $student->first_access_status);
    }
}
