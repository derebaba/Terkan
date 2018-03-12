@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="jumbotron">
		
		<div class="row">
			<div class="col-sm-7 col-lg-9">
				<h1>{{ $tv['original_name'] }}</h1>
				<p>{{ $tv['overview'] }}</p>
			</div>
			<div class="col-sm-5 col-lg-3">
				<div class="float-right">
					<div class="row center-block">
					{{-- "w92", "w154", "w185" mobil için en iyi, "w342", "w500", "w780", or "original" --}}
						{!! $image->getHtml($tv['poster_path'], 'w185') !!}
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
								<textarea rows="3" class="form-control autosize-target" placeholder="Enter review (can be empty)" name="body"></textarea>
							</div>

							<div class="form-group row">
								<label for="star-rating" class="col-1 offset-1">Stars: </label>
								<select id="star-rating" class="col-2" name="stars" autocomplete="off">
									<option value=""></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
								<button type="submit" class="btn btn-primary offset-1 col-1 align-self-end">Submit</button>
							</div>

							<input type="hidden" name="reviewable_id" value="{{ $tv['id'] }}">
							<input type="hidden" name="reviewable_type" value="tv">
							
						</form>
					</div>
				</div>
			@endif
		@else
			<div class="container text-center">
				<i class=""><a href="{{ route('login') }}" class="link-primary">Login</a> or 
				<a href="{{ route('register') }}" class="">register</a> to write a review</i>
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
						@foreach($reviews as $review)
							<li class="list-group-item">
								<div class="row">
									<div class="col-2">
										<div class="row text-center center-block">
											@if (is_file('profilepics' . DIRECTORY_SEPARATOR . $review->user->pic))
												<img src="/profilepics/{{ $review->user->pic }}" class="img-thumbnail rounded-circle" alt="profil-resmi" style="width: 80px; height: 80px;">
											@else
												<img src="http://via.placeholder.com/80" class="img-thumbnail rounded-circle" alt="profil-resmi" style="width: 80px; height: 80px;">
											@endif
										</div>
										<div class="row text-center center-block">
											<a href="{{ route('users.show', [$review->user->id]) }}">{{ $review->user->name }}</a> 
										</div>
									</div>
									<div class="col-10">
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