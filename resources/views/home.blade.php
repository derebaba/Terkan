@extends('layouts.app')

@section('head')

@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		<div class="col-md-3 d-none d-md-block">
			<div class="card">
				<div class="card-header text-center">
					<h5 class="card-title font-italic" aria-hidden="true">
						<i class="fas fa-film"></i> Recommended for you
					</h5>
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
		{{--<div class="col-md-5">
			<div class="card widget">
				<div class="card-header text-center">
					<h5 class="card-title" aria-hidden="true"><i class="fas fa-film"></i> Latest popular reviews</h5>
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
											{!! $image->getHtml($reviewable->poster, 'w92') !!}
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
				</div>
			</div>
		</div>--}}
		<div class="col-md-6 col-lg-7">
			@if (Auth::user()->followings()->get()->count() == 0)
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					You are not following anyone. Search their name in order to follow someone.
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
		<div class="col-md-3 col-lg-2 sidebar-offcanvas">
			@include('partials.genresSidebar')
		</div>
	</div>
@endsection
