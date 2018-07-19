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
				<li class="list-group-item list-group-header"> Upcoming episodes</li>
				@if (empty($followingTvs))
					<li class="list-group-item">
						You are not following any TV shows. Follow TV shows to see upcoming episodes here.
					</li>
				@endif
				@foreach ($followingTvs as $followingTv)
					@php ($nextEpisodeToAir = $followingTv["next_episode_to_air"])
					@if ($nextEpisodeToAir)
						<li class="list-group-item">
							<a class="" href="{{ route('tvs.show', $followingTv['id']) }}">
								{{ $followingTv['original_name'] }}
							</a>
							</br>
							Season {{ $nextEpisodeToAir['season_number'] }}, episode {{ $nextEpisodeToAir['episode_number'] }}
							</br>
							Air date: {{ $nextEpisodeToAir["air_date"] }}
						</li>
					@endif
				@endforeach
			</ul>

			<ul class="list-group" style="margin-top: 5px;">
				<li class="list-group-item list-group-header"> Previous episodes</li>
				@if (empty($followingTvs))
					<li class="list-group-item">
						You are not following any TV shows. Follow TV shows to see recent episodes here.
					</li>
				@endif
				@foreach ($followingTvs as $followingTv)
					@php ($lastEpisodeToAir = $followingTv["last_episode_to_air"])
					<li class="list-group-item">
						<a class="" href="{{ route('tvs.show', $followingTv['id']) }}">
							{{ $followingTv['original_name'] }}
						</a>
						</br>
						Season {{ $lastEpisodeToAir['season_number'] }}, episode {{ $lastEpisodeToAir['episode_number'] }}
						</br>
						Air date: {{ $lastEpisodeToAir["air_date"] }}
					</li>
				@endforeach
			</ul>

			

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
