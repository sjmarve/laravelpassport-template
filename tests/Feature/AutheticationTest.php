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
        Artisan::call('passport:install');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_login_with_email()
    {
        //arrange
        $user = factory(\App\User::class)->create();
		// Passport::actingAs(
		// 	$user,
		// 	['*']
		// );

        //act
        $response = $this->post('/api/login', [
        	'username' => $user->email,
        	'password' => 'secret'
        ]);
        //assert
        dd($response->decodeResponseJson());
    }
}
