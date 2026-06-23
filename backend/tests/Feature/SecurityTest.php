<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_enforces_rate_limiting_on_login()
    {
        // Enviar 5 peticiones fallidas al login en el mismo minuto
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => 'nonexistent@example.com',
                'password' => 'wrongpassword'
            ]);
            $response->assertStatus(401);
        }

        // La 6ta petición debería ser bloqueada con 429
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);
        $response->assertStatus(429);
    }

    /** @test */
    public function it_rejects_weak_passwords_during_registration()
    {
        // Contraseña demasiado corta (< 8 caracteres)
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short'
        ]);
        $response->assertStatus(422);

        // Contraseña sin números
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'NoNumbersPassword'
        ]);
        $response->assertStatus(422);

        // Contraseña sin mayúsculas/minúsculas
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'password' => 'onlylowercase123'
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function it_accepts_strong_passwords_during_registration()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'strong@example.com',
            'password' => 'StrongPass123'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'strong@example.com'
        ]);
    }

    /** @test */
    public function it_prevents_sql_injection_on_login()
    {
        // Payload típico de inyección SQL (no pasa la validación de formato de email)
        $response = $this->postJson('/api/auth/login', [
            'email' => "' OR '1'='1",
            'password' => 'anything'
        ]);

        $this->assertNotEquals(500, $response->getStatusCode());
        $response->assertStatus(422);
    }

    /** @test */
    public function it_handles_sql_injection_in_valid_email_format_safely()
    {
        // Payload que sí pasa la validación formal de email pero tiene inyección SQL
        $response = $this->postJson('/api/auth/login', [
            'email' => "admin'or'1'='1@example.com",
            'password' => 'anything'
        ]);

        // Debe ser rechazado como no autorizado (401), sin causar crash en el servidor (500)
        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_refresh_its_token_when_authenticated()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'access_token',
                    'token_type'
                ]
            ]);

        $this->assertNotNull($response['data']['access_token']);
    }

    /** @test */
    public function it_cannot_refresh_token_if_unauthenticated()
    {
        $response = $this->postJson('/api/auth/refresh', []);
        $response->assertStatus(401);
    }

    /** @test */
    public function it_invalidates_old_token_after_refresh()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Refrescar el token
        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200);

        $newToken = $response['data']['access_token'];

        // Forzar desautenticación en el contexto del test para evaluar el token
        auth()->forgetUser();

        // Intentar usar el token viejo para acceder a me (debe fallar con 401)
        $meOldResponse = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer $token"
        ]);
        $meOldResponse->assertStatus(401);

        // Intentar usar el token nuevo para acceder a me (debe funcionar con 200)
        $meNewResponse = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer $newToken"
        ]);
        $meNewResponse->assertStatus(200);
    }
}
