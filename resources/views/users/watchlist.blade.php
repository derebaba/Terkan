@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')

	<div class="row">
		@foreach ($movies as $movie)
				
		@endforeach
		<div class="col-md-6 text-center">
			<a href="{{ route($reviewable->type . 's.show', [$reviewable->id]) }}" 
				title="{{ $reviewable->name }}">
				<img class="w154" src="{!! $image->getUrl($reviewable->poster, 'w154') !!}">
			</a>
			<div class="row text-xs-center center-block">
				<a class="text-xs-center" href="{{ route($reviewable->type . 's.show', [$reviewable->id]) }}">
					{{ $reviewable->name }}
				</a>
			</div>
		</div>
	</div>
@endsection