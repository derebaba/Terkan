<?php

namespace App\Providers;

use App\Review;
use App\User;

use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
		Review::class => ReviewPolicy::class,
		User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

		//Gate::define('reviews.like', 'ReviewsController@like');
		Gate::resource('reviews', 'ReviewPolicy');
		Gate::resource('users', 'UserPolicy');
		
	}
	
	public function registerReviewPolicies() {
		Gate::define('update-review', function ($user, $review) {
			return $user->id == $review->user_id;
		});
	}
}
