<?php

namespace App\Providers;

use App\Models\Review;
use App\Models\User;

use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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

		Passport::routes();

		Passport::tokensExpireIn(now()->addDays(15));

		Passport::refreshTokensExpireIn(now()->addYears(1));
	
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
