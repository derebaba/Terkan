<?php

namespace Tests\Unit;

use App\Models\User;
use App\Traits\Utils;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
	use Utils;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFindOrCreateUser()
    {
		$user = new User([
			'name'     => 'e',
			'email'    => 'asdf'
		]);

		$user->avatar_original = 'no pic';
		$authUser = $this->findOrCreateUser($user, 'fb');

		$this->assertEquals($authUser->verified, 1);
		
		$authUser->delete();
    }
}
