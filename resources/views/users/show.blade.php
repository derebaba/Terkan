@extends('layouts.app')

@section('head')

@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		<div class="col-md-3">
			<div class="row text-center center-block">
				@if (is_file($user->pic))
					<img src="{{ $user->pic }}" class="img-thumbnail rounded-circle" alt="profil-resmi" style="width: 200px; height: 200px;">
				@else
					<img src="http://via.placeholder.com/200x200" class="img-thumbnail rounded-circle" alt="profil-resmi" style="width: 200px; height: 200px;">
				@endif
			</div>
			<div class="row center-block">
				<h3 class="text-center">{{ $user->name }}</h3>
			</div>
			<div class="row center-block">
				<div class="list-group center-block text-center">
				@if (Auth::check() && !$self)
					<form method="GET" action="{{ route('users.follow', $user) }}" class="">
						{{ csrf_field() }}
						<input type="hidden" name="follower_id" value="{{ Auth()->user()->id }}">
						@if (!Auth::user()->isFollowing($user))
							<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i> Follow</button>
						@else
							<button type="submit" class="btn btn-light"><i class="fas fa-eye-slash"></i> Unfollow</button>
							<div class="font-italic small">You are following {{$user->name}}</div>
						@endif
					</form>
				@endif
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card widget">
				<div class="card-header text-center">
					<h4 class="card-title" aria-hidden="true"><i class="fas fa-film"></i> Latest reviews</h4>
					<span class="badge badge-info" data-toggle="tooltip" data-placement="auto bottom" title="Review count" id="reviewTooltip">{{ $user->reviews->count() }}</span>
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
			<div class="list-group">
				<a href="{{$user->id}}/followers" class="list-group-item list-group-item-action "><i class="fas fa-users fa-fw"></i> Followers</a>
				<a href="#" class="list-group-item list-group-item-action "><i class="fas fa-film fa-fw"></i> Tüm izlenenler</a>
				@if (Auth::check() && $self)
					<a class="list-group-item list-group-item-action" href="{{ route('users.edit',[$user->id]) }}">
						<i class="fas fa-pencil-alt fa-fw"></i> Edit profile
					</a>
				@endif
			</div>
		</div>
	</div>

@endsection