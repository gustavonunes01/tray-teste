<?php

namespace Tests\Feature;

use App\Models\Sales\Sales;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ApiTest extends TestCase
{
    use RefreshDatabase, CreatesApplication;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar migrations e seeders
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');

        // Login e obter token
        $response = $this->postJson('/api/login', [
            'email' => 'super@admin.teste',
            'password' => 'superadmin123'
        ]);

        $this->token = $response->json('access_token');
    }

    /** @test */
    public function it_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'super@admin.teste',
            'password' => 'superadmin123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    /** @test */
    public function it_can_get_user_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'profile'
            ]);
    }

    /** @test */
    public function it_can_send_email_notification()
    {
        $seller = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/v1/sellers/email/notify/{$seller->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /** @test */
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/v1/sales');

        $response->assertStatus(401);
    }
}
