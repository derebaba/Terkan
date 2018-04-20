@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-12 col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">
					Episodes of {{ $tv['original_name'] }} - season {{ $season['season_number'] }}
					<nav class="d-block d-md-none" aria-label="Season navigation">
						<ul class="pagination pagination-sm justify-content-end">
							<li class="page-item font-weight-normal" style="margin-right: 5px;">Choose season: </li>
							@for ($i = 1; $i <= $tv['number_of_seasons']; $i++)
								
								@if ($season['season_number'] == $i)
									<li class="page-item active">
										<a href="{{ route('tvs.season', ['id' => $tv['id'], 'season_number' => $i]) }}" 
											class="page-link">{{$i}}</a>
									</li>
								@else
									<li class="page-item">
										<a href="{{ route('tvs.season', ['id' => $tv['id'], 'season_number' => $i]) }}" 
											class="page-link">{{$i}}</a>
									</li>
								@endif
								
							@endfor
						</ul>
					</nav>
				</div>
			</div>
			<ul class="list-group">
				@foreach($episodes as $episode)
					<li class="list-group-item">
						<div class="row">
							<div class="col-sm-5 col-lg-4 text-center">

								<img class="" src="{!! $image->getUrl($episode['still_path'], 'w185') !!}">

							</div>
							<div class="col-sm-7 col-lg-8">
								<h5>
									{{ $loop->index + 1 }}. {{ $episode['name'] }}
									<small>({{$episode['air_date']}})</small>
								</h5>
								<p>{{ $episode['overview'] }}</p>
							</div>
						</div>
					</li>
				@endforeach	
			</ul>
		</div>
		<div class="col-md-3 d-none d-md-block">
			<div class="list-group">
				<div class="list-group-item list-group-header">Seasons</div>
				@for ($i = 1; $i <= $tv['number_of_seasons']; $i++)
					@if ($season['season_number'] == $i)
						<a href="{{ route('tvs.season', ['id' => $tv['id'], 'season_number' => $i]) }}" 
							class="list-group-item list-group-item-action active">{{$i}}</a>
					@else
						<a href="{{ route('tvs.season', ['id' => $tv['id'], 'season_number' => $i]) }}" 
							class="list-group-item list-group-item-action">{{$i}}</a>
					@endif
				@endfor
			</div>
		</div>
	</div>
	
@endsection