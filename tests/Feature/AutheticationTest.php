<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class AutheticationTest extends TestCase
{
	use RefreshDatabase;

	/**
     * The setup method
     */
    public function setUp()
    {
        parent::setUp();
        //install passport
        //test assumes password auth
        Artisan::call('passport:install');
    }

    /** @test */
    public function can_login_with_email()
    {
        //arrange
        $user = factory(\App\User::class)->create();
		// Passport::actingAs(
		// 	$user,
		// 	['*']
		// );

        //act
        $response = $this->json('POST', '/api/auth/login', [
        	'username' => $user->email,
        	'password' => 'secret'
        ]);

        //assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ]);
    }

    /** @test */
    public function can_login_with_sa_phone_number()
    {
        // $this->withoutExceptionHandling();
        //arrange
        $user = factory(\App\User::class)->create([
            'cell' => '0027740123456'
        ]);

        //act
        $response = $this->json('POST', '/api/auth/login', [
            'username' => $user->cell,
            'password' => 'secret'
        ]);

        //assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ]);
    }
}
