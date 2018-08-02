@extends('layouts.discover')

@section('results')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<ul class="list-group">
		@foreach($results as $result)
			<li class="list-group-item">
				<div class="row">
					<div class="col-sm-4 col-lg-3 text-center">
						<a href="{{ route('tvs.show', $result['id']) }}" 
							title="{{ $result['original_name'] }}">
							<img class="w154" src="{!! $image->getUrl($result['poster_path'], 'w154') !!}">
						</a>
					</div>
					<div class="col-sm-8 col-lg-9">
						<h5>
							{{ $loop->index + 1 }}. 
							<a href="{{ route('tvs.show', $result['id']) }}" >
								{{ $result['original_name'] }}
							</a>
							<small>(First aired on: {{$result['first_air_date']}})</small>
						</h5>
						<select id="star-rating-{{$loop->index}}" name="stars" autocomplete="off">
							<option value=""></option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
						<small>
							{{ sprintf ("%.2f", $result['vote_average']) }} / 5 ({{ $result['vote_count'] }} votes)
						</small>
						<p>{{ $result['overview'] }}</p>
					</div>
				</div>
			</li>
		@endforeach	
	</ul>

@endsection

@section("filters")
	<genre-filter v-bind:old-genres="{{ isset($request->genres) ? json_encode($request->genres) : '[-1]'}}" route="tv"></genre-filter>
@endsection