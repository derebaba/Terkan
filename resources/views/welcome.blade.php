@extends('layouts.app')

@section('head')

@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		<div class="col-md-9">
			<div class="jumbotron">
				<div class="container">
					{{--<p class="pull-right d-block d-md-none">
						<button type="button" class="btn btn-primary btn-sm" data-target="#genres" data-toggle="collapse">Browse by genres</button>
					</p>--}}
					<h2>Terkan</h2>
					<ul>
						<li>Rate movies</li>
						<li>Share your reviews</li>
						<li>Suggest movies to your friends <i>(Coming soon!)</i></li>
					</ul>
				</div>
			</div>
			<div class="card widget">
				<div class="card-header text-center">
					<h4 class="card-title" aria-hidden="true"><i class="fas fa-film"></i> Latest popular reviews</h4>
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
					{{--<a href="#" class="btn btn-primary btn-sm btn-block" role="button"><span class="fa fa-refresh"></span> More</a> --}}
				</div>
			</div>
		</div>
		<div class="col-md-3 sidebar-offcanvas">
			@include('partials.genresSidebar')
		</div>
	</div>
@endsection
