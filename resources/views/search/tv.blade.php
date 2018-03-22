@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">
					Search movies: {{$query}} ({{ $response['total_results'] }} results)
				</div>
			</div>

			<ul class="list-group">
				@foreach($results as $result)
					<li class="list-group-item">
						<div class="row">
							<div class="col-sm-4 col-lg-3 text-center">
								<a href="{{ route('movies.show', $result['id']) }}" 
									title="{{ $result['original_name'] }}">
									<img class="w154" src="{!! $image->getUrl($result['poster_path'], 'w154') !!}">
								</a>
							</div>
							<div class="col-sm-8 col-lg-9">
								<h5>
									{{ $loop->index + 1 }}. {{ $result['original_name'] }} 
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

			
			<div class="row d-flex justify-content-center">
				<nav aria-label="Search pagination">
					<ul class="pagination">
						@if ($response['page'] != 1)
							<li class="page-item">
								<a class="page-link" href="#" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $response['total_pages']; $i++)
							@if ($i == $response['page'])
								<li class="page-item active">
									<a class="page-link" href="{{route('search.movie', ['q' => $query, 'page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@else
								<li class="page-item">
									<a class="page-link" href="{{route('search.movie', ['q' => $query, 'page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@endif
						@endfor
						@if ($response['page'] != $response['total_pages'])
							<li class="page-item">
								<a class="page-link" href="#">Next</a>
							</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
		<div class="col-md-3">
			<div class="list-group">
				<a href="{{ route('search.movie', ['page' => $response['page'], 'q' => $query]) }}"
					class="list-group-item list-group-item-action">Movies ({{$movieResponse['total_results']}})</a>
				<a href="{{ route('search.tv', ['page' => $response['page'], 'q' => $query]) }}"
					class="list-group-item list-group-item-action active">Tv ({{$response['total_results']}})</a>
			</div>
		</div>
	</div>
	
@endsection