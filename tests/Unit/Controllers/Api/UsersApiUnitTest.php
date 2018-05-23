<?php

namespace Tests\Unit\Controllers\Api;

use Laravel\Passport\Passport;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersApiUnitTest extends TestCase
{
	public function setUp()
    {
		parent::setUp();
		
		User::create([
			"name" => "erdem",
			"email" => "tk@mail.com",
			"password" => bcrypt('secret'),
		]);
	}
	
    public function testGetSelfProfileUsingToken()
    {
		$user = Passport::actingAs(
			factory(User::class)->create()
		);

		$profileResponse = $this->get('/api/user');
		
		$profileResponse->assertStatus(200)->assertJson($user->toArray());
    }
}
