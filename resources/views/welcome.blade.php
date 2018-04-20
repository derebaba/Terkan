@extends('layouts.app')

@section('head')

@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<nav class="nav nav-pills nav-fill" style="padding: 3px;">
		<a class="nav-item nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="movieGenres" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-film"></i> Movies
			</a>
			<div class="dropdown-menu" aria-labelledby="movieGenres">
				@foreach ($movieGenres as $genre)
					<a href="{{ route('browseByGenre', ['genre' => $genre['id'], 'page' => 1]) }}" 
					class="dropdown-item">
						<i class=""></i> {{ $genre['name'] }}
					</a>
				@endforeach
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="tvGenres" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-tv"></i> TV shows
			</a>
			<div class="dropdown-menu" aria-labelledby="tvGenres">
				@foreach ($tvGenres as $genre)
					<a href="{{ route('browseTvByGenre', ['genre' => $genre['id'], 'page' => 1]) }}" 
					class="dropdown-item">
						<i class=""></i> {{ $genre['name'] }}
					</a>
				@endforeach
			</div>
		</li>
	</nav>

	<div class="row">
		<div class="col-md-9">
			<div class="jumbotron" id="welcome-jumbotron">
				<div class="container">
					{{--<p class="pull-right d-block d-md-none">
						<button type="button" class="btn btn-primary btn-sm" data-target="#genres" data-toggle="collapse">Browse by genres</button>
					</p>--}}
					<h2>Terkan</h2>
					<ul>
						<li>Rate movies</li>
						<li>Share your reviews</li>
						<li>Follow your friends</li>
					</ul>
				</div>
			</div>
			<div class="card widget">
				<div class="card-header text-center">
					<h4 class="card-title" aria-hidden="true"><i class="far fa-comments"></i> Latest popular reviews</h4>
					<span class="badge badge-info" data-toggle="tooltip" data-placement="auto bottom" title="Review count" 
						id="reviewTooltip">{{ $reviews->count() }}</span>
				</div>
				<div class="card-body">
					<ul class="list-group">
						@foreach($reviewables as $reviewable)
							<li class="list-group-item">
								<div class="row">
									<div class="col-sm-4 col-md-3 text-center">
										<a href="{{ route($reviewable->type . 's.show', [$reviewable->id]) }}" 
											title="{{ $reviewable->name }}">
											<img class="w92" src="{!! $image->getUrl($reviewable->poster, 'w92') !!}">
										</a>
										<div class="row text-xs-center center-block">
											<a class="text-xs-center" href="{{ route($reviewable->type . 's.show', [$reviewable->id]) }}">
												{{ $reviewable->name }}
											</a>
										</div>
									</div>
									<div class="col-sm-8 col-md-9">
										@php ($review = $reviews[$loop->index])
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
		<div class="col-md-3 sidebar-offcanvas">
			<div class="card">
				<div class="card-header text-center">
					<i class="fas fa-film" aria-hidden="true"></i> Now popular
				</div>
				@foreach ($recommendations as $recommendation)
					<figure class="figure center-block">
						<a href="{{ route($recommendation->type . 's.show', [$recommendation->id]) }}" 
							title="{{ $recommendation->name }}" style="">
							<img class="img-fluid w154 center-block" src="{!! $image->getUrl($recommendation->poster, 'w154') !!}">
						</a>
						<figcaption class="figure-caption text-center center-block">
							<a href="{{ route($recommendation->type . 's.show', [$recommendation->id]) }}">
								{{ $recommendation->name }}
							</a>
							<select id="popular-rating-{{$loop->index}}" name="popular-rating" autocomplete="off">
								<option value=""></option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</figcaption>
					</figure>
				@endforeach
				
			</div>
		</div>
	</div>
@endsection

@section('script')
	<script>
		for (i = 0; i < 20; i++) {
			$('#star-rating-' + i).barrating({
				theme: 'fontawesome-stars-o',
				showSelectedRating: true,
				initialRating: window.stars[i],
				readonly: true
			});
		}
		for (i = 0; i < 20; i++) {
			$('#popular-rating-' + i).barrating({
				theme: 'fontawesome-stars-o',
				showSelectedRating: true,
				initialRating: window.popularStars[i],
				readonly: true
			});
		}
	</script>
@endsection