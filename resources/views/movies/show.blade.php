@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="jumbotron">
		
		<div class="row">
			<div class="col-sm-7 col-lg-9">
				<h1>{{ $movie['original_title'] }}</h1>
				<p>{{ $movie['overview'] }}</p>
				@auth
					@if (!Auth::user()->hasWatchlisted($movie['id'], 'movie'))
						<form method="POST" action="/addToWatchlist" class="review-form" style="">
							@method('put')
							{{ csrf_field() }}
							<button type="submit" class="btn btn-dark review-button" title="Watchlist"> 
								<i class="far fa-square"></i> Add to watchlist
							</button>
							<input type="hidden" name="reviewable_id" value="{{ $movie['id'] }}">
							<input type="hidden" name="reviewable_type" value="movie">
							<input type="hidden" name="name" value="{{ $movie['original_title'] }}">
						</form>
					@else
						<form method="POST" action="/removeFromWatchlist" class="review-form" style="">
							@method('put')
							{{ csrf_field() }}
							<button type="submit" class="btn btn-light review-button" title="Unwatchlist"> 
								<i class="far fa-check-square"></i> Remove from watchlist
							</button>
							<input type="hidden" name="reviewable_id" value="{{ $movie['id'] }}">
							<input type="hidden" name="reviewable_type" value="movie">
							<input type="hidden" name="name" value="{{ $movie['original_title'] }}">
						</form>
					@endif
				@endauth
			</div>
			<div class="col-sm-5 col-lg-3">
				<div class="float-right">
					<div class="row center-block">
					{{-- "w92", "w154", "w185" mobil için en iyi, "w342", "w500", "w780", or "original" --}}
						{!! $image->getHtml($movie['poster_path'], 'w185') !!}
					</div>
					<div class="row center-block text-center">
						<strong>Average rating:</strong>
					</div>
					<div class="row center-block text-center">
						<select id="average-rating" name="stars" autocomplete="off">
							<option value=""></option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</div>
					<div class="row center-block text-center">
						<i>{{$reviews->count()}} votes</i>
					</div>
				</div>
			</div>
		</div>
		
	</div>

	<div class="container">
		@if (Auth::check())
			{{-- userin kendi yorumu yoksa review box göster --}}
			@if (!isset($userReview))
				<div class="card">
					<div class="card-header"><h3>Add a review</h3></div>
					<div class="card-body">
						<form method="post" action="{{ route('reviews.store') }}">
							{{ csrf_field() }}

							<div class="form-group">
								<textarea rows="3" class="form-control autosize-target" placeholder="Enter review (optional)" name="body"></textarea>
							</div>

							<div class="form-group row">
								<label for="star-rating" class="col-3 col-lg-1 offset-1">Stars: </label>
								<select id="star-rating" class="col-2" name="stars" autocomplete="off">
									<option value=""></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
								<button type="submit" class="btn btn-primary offset-1 align-self-end">Submit</button>
							</div>

							<input type="hidden" name="reviewable_id" value="{{ $movie['id'] }}">
							<input type="hidden" name="reviewable_type" value="movie">
							
						</form>
					</div>
				</div>
			@endif
		@else
			<div class="container text-center">
				<a href="{{ route('login') }}" class="link-primary">Login</a> or 
				<a href="{{ route('register') }}" class="">register</a> to write a review
			</div>
			<br>
		@endif

		<div class="card widget">
				<div class="card-header">
					<i class="fas fa-comment fa-2x" aria-hidden="true"></i>
					<h3 class="card-title">Reviews</h3>
					<span class="badge badge-info" data-toggle="tooltip" data-placement="auto bottom" title="Review count" id="reviewTooltip">
						{{ $reviews->count() }}
					</span>
				</div>
				<div class="card-body">
					<ul class="list-group">
						@if ($reviews->isEmpty())
							<li class="list-group-item font-italic">
								There are no reviews for this movie. Be the first to write!
							</li>
						@endif
						@foreach($reviews as $review)
							<li class="list-group-item">
								<div class="row">
									<div class="col-4 col-sm-3">
										<div class="row text-center center-block">
											@if (is_file('profilepics' . DIRECTORY_SEPARATOR . $review->user->pic))
												<img src="/profilepics/{{ $review->user->pic }}" class="img-thumbnail rounded-circle" 
													alt="profil-resmi" style="width: 80px; height: 80px;">
											@else
												<img src="/profilepics/generic_profile_pic.png" class="img-thumbnail rounded-circle" 
													alt="profil-resmi" style="width: 80px; height: 80px;">
											@endif
										</div>
										<div class="row text-center center-block">
											<a href="{{ route('users.show', [$review->user->id]) }}">{{ $review->user->name }}</a> 
										</div>
									</div>
									<div class="col-8 col-sm-9">
										@include('partials.review')
									</div>
								</div>
							</li>
						@endforeach	
					</ul>
					{{--<a href="#" class="btn btn-primary btn-sm btn-block" role="button"><span class="fa fa-refresh"></span> More</a> --}}
				</div>
		</div>
	</div>
@endsection