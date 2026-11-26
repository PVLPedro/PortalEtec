<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserIdentifierService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIdentifierServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserIdentifierService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserIdentifierService;
    }

    public function test_generates_standardized_name_from_email(): void
    {
        $this->assertSame(
            'JOAO.SILVA',
            $this->service->generateName('joao.silva@etec.sp.gov.br'),
        );

        $this->assertSame(
            'ANA-CLAUDIA.BENCAO',
            $this->service->generateName('ana-cláudia.bênção@cps.sp.gov.br'),
        );

        $this->assertSame(
            'JOSE.ALVARES',
            $this->service->generateName('josé.álvares@cps.sp.gov.br'),
        );
    }

    public function test_extracts_first_name_correctly(): void
    {
        $this->assertSame('JOAO', $this->service->extractFirstName('JOAO.SILVA'));
        $this->assertSame('MARIA', $this->service->extractFirstName('MARIA_SOUZA'));
        $this->assertSame('ANA', $this->service->extractFirstName('ANA-PAULA.LIMA'));
        $this->assertSame('CARLOS', $this->service->extractFirstName('CARLOS'));
    }

    public function test_generates_four_digit_padded_identifier(): void
    {
        $identifier = $this->service->generateIdentifier('JOAO');

        $this->assertMatchesRegularExpression('/^\d{4}$/', $identifier);
    }

    public function test_identifiers_are_unique_for_the_same_first_name(): void
    {
        User::factory()->create([
            'name' => 'JOAO.SILVA',
            'identifier' => '4821',
        ]);

        $this->assertTrue($this->service->isIdentifierTaken('JOAO', '4821'));
        $this->assertFalse($this->service->isIdentifierTaken('JOAO', '0937'));

        $newIdentifier = $this->service->generateIdentifier('JOAO');
        $this->assertNotSame('4821', $newIdentifier);
    }

    public function test_different_first_names_can_share_the_same_identifier(): void
    {
        User::factory()->create([
            'name' => 'JOAO.SILVA',
            'identifier' => '4821',
        ]);

        $this->assertFalse($this->service->isIdentifierTaken('MARIA', '4821'));
    }
}
