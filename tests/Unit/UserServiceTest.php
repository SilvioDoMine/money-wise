<?php

namespace Tests\Unit;

use App\Models\DocumentType;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(UserService::class);
    }

    public function testLoginMethodWithWrongEmailShouldNotSucceed(): void
    {
        $user = User::factory()
            ->create();

        $wrongEmail = $this->service->login([
            'email' => "incorrect{$user->email}",
            'password' => 'password',
        ]);

        $this->assertEquals([
            'success' => false,
            'message' => "E-mail or password are incorrect.",
        ], $wrongEmail);
    }

    public function testLoginMethodWithWrongPasswordShouldNotSucceed(): void
    {
        $user = User::factory()
            ->create();

        $wrongPassword = $this->service->login([
            'email' => $user->email,
            'password' => 'incorrect',
        ]);

        $this->assertEquals([
            'success' => false,
            'message' => "E-mail or password are incorrect.",
        ], $wrongPassword);
    }

    public function testLoginMethodWithAllCredentialsWrongShouldNotSucceed(): void
    {
        $user = User::factory()
            ->create();

        $response = $this->service->login([
            'email' => "incorrect{$user->email}",
            'password' => 'incorrect',
        ]);

        $this->assertEquals([
            'success' => false,
            'message' => "E-mail or password are incorrect.",
        ], $response);
    }

    public function testLoginWithRightCredentialsShouldGenerateValidToken(): void
    {
        $user = User::factory()
            ->create();

        $response = $this->service->login([
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertTrue(auth()->check());
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('token', $response['message']);
    }

    public function testGetRoleShouldReturnCustomerWhenDocumentIsCpf(): void
    {
        $user = User::factory()
            ->withCpf()
            ->create();

        $response = $this->service->getRole($user);

        $this->assertEquals(User::CPF_NAME, $response);
        $this->assertEquals('customer', $response);
    }

    public function testGetRoleShouldReturnStoreWhenDocumentIsCnpj(): void
    {
        $user = User::factory()
            ->withCnpj()
            ->create();

        $response = $this->service->getRole($user);

        $this->assertEquals(User::CNPJ_NAME, $response);
        $this->assertEquals('store', $response);
    }

    public function testGetRoleShouldThrowExceptionWhenDocumentIsUnknown(): void
    {
        DocumentType::factory()
            ->count(3)
            ->create();

        $user = User::factory()
            ->create([
                'document_type_id' => 3,
            ]);

        $this->expectExceptionMessage("There is no role assigned to the document type id 3.");
        $this->service->getRole($user);
    }
}
