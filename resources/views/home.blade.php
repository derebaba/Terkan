@extends('layouts.app')

@section('head')

@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		<div class="col-md-3 col-lg-2 d-none d-md-block">
			<div class="card">
				<div class="card-header text-center">
					<i class="fas fa-film" aria-hidden="true"></i> Recommended for you
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
							<select id="recommendation-rating-{{$loop->index}}" name="recommendation-rating" autocomplete="off">
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
		
		<div class="col-md-6 col-lg-7">
			@if (Auth::user()->followings()->get()->isEmpty())
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					You are not following anyone. Search their name in order to follow someone.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			@endif
			@if (Auth::user()->getWatchlist()->isEmpty())
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					Your <a href="{{ route('users.watchlist', Auth::user()) }}">watchlist</a> is empty. Save movies in your watchlist to watch them later.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			@endif
			<div class="card">
				<div class="card-header text-center">
					<h5 class="card-title" aria-hidden="true"><i class="fas fa-users"></i> News feed</h5>
				</div>
					<ul class="list-group">
						@foreach($reviewables as $reviewable)
							<li class="list-group-item">
								<div class="row">
									<div class="col-sm-4 col-lg-3 text-center">
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
									<div class="col-sm-8 col-lg-9">
										@php ($review = $reviews[$loop->index])
										@include('partials.review')
									</div>
								</div>
							</li>
						@endforeach	
					</ul>
			</div>
		</div>
		<div class="col-md-3 sidebar-offcanvas">
			<ul class="list-group">
				<li class="list-group-item list-group-header"> Episodes aired last week</li>
				@if (empty($newEpisodes))
					<li class="list-group-item">
						You are not following any TV shows. Follow TV shows to see recent episodes here.
					</li>
				@endif
				@foreach ($newEpisodes as $episode)
					<li class="list-group-item">
						<a class="" href="{{ route('tvs.show', $newTvs[$loop->index]['id']) }}">
							{{ $newTvs[$loop->index]['original_name'] }}
						</a>
						</br>
						Season {{ $newTvs[$loop->index]['number_of_seasons'] }}, episode {{ $episode['episode_number'] }}
						</br>
						@if ($episode['days_ago'] != 1)
							{{ $episode['days_ago'] }} days ago
						@else 
							{{ $episode['days_ago'] }} day ago
						@endif
					</li>
				@endforeach
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
	</script>
@endsection
