<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Models\Etec;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomaticRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_without_providing_manual_name(): void
    {
        $etec = Etec::create(['code' => 123, 'name' => 'Etec Teste']);

        $response = $this->post(route('register'), [
            'email_prefix' => 'joao.silva',
            'role' => Role::Professor->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'etecs' => [$etec->id],
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'joao.silva@cps.sp.gov.br')->first();

        $this->assertNotNull($user);
        $this->assertSame('JOAO.SILVA', $user->name);
        $this->assertNotNull($user->identifier);
        $this->assertMatchesRegularExpression('/^\d{4}$/', $user->identifier);
        $this->assertSame("#{$user->identifier}", $user->formatted_identifier);
        $this->assertSame("JOAO.SILVA#{$user->identifier}", $user->tag);
    }
}
