@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-12 col-md-9">
			<div class="row">
				<div class="card" style="width: 100%;">
					<div class="card-header">
						Search movies: {{$query}} ({{ $movieResponse['total_results'] }} results)
					</div>
				</div>
			</div>
			<div class="row d-block d-md-none">
				<nav class="navbar navbar-expand-sm" id="search-navbar">
					<ul class="navbar-nav" id="navbar-list">
						<li>
							Jump to:
						</li>
						<li>
							<a href="{{ route('search.movie', ['page' => 1, 'q' => $query]) }}"
								class="list-group-item list-group-item-action bar-button active">
								Movies <span class="badge badge-light">{{$movieResponse['total_results']}}</span>
							</a>
						</li>
						<li>
							<a href="{{ route('search.tv', ['page' => 1, 'q' => $query]) }}"
								class="list-group-item list-group-item-action bar-button">
								TV shows <span class="badge badge-primary">{{$tvResponse['total_results']}}</span>
							</a>
						</li>
						<li>
							<a href="{{ route('search.people', ['page' => 1, 'q' => $query]) }}"
								class="list-group-item list-group-item-action bar-button">
								People <span class="badge badge-primary">{{$peopleResults->count()}}</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>

			<div class="row">
				<ul class="list-group">
					@if (empty($results))
						<li class="list-group-item">
							No movies found.
						</li>
					@endif
					@foreach($results as $result)
						<li class="list-group-item">
							<div class="row">
								<div class="col-sm-4 col-lg-3 text-center">
									<a href="{{ route('movies.show', $result['id']) }}" 
										title="{{ $result['original_title'] }}">
										<img class="w154" src="{!! $image->getUrl($result['poster_path'], 'w154') !!}">
									</a>
								</div>
								<div class="col-sm-8 col-lg-9">
									<h5>
										{{ $loop->index + 1 }}. 
										<a href="{{ route('movies.show', $result['id']) }}" >
											{{ $result['original_title'] }}
										</a>
										<small>({{$result['release_date']}})</small>
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

				
				
			</div>
			<div class="row d-flex justify-content-center">
				<nav aria-label="Search pagination">
					<ul class="pagination">
						@if ($movieResponse['page'] != 1)
							<li class="page-item">
								<a class="page-link" href="#" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $movieResponse['total_pages']; $i++)
							@if ($i == $movieResponse['page'])
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
						@if ($movieResponse['page'] != $movieResponse['total_pages'])
							<li class="page-item">
								<a class="page-link" href="#">Next</a>
							</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
		<div class="col-md-3 d-none d-md-block">
			<div class="list-group d-none d-md-block">
				<div class="list-group-item bg-secondary text-white"> Jump to:</div>
				<a href="{{ route('search.movie', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action active">
					Movies <span class="badge badge-light">{{$movieResponse['total_results']}}</span>
				</a>
				<a href="{{ route('search.tv', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action">
					TV shows <span class="badge badge-primary">{{$tvResponse['total_results']}}</span>
				</a>
				<a href="{{ route('search.people', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action">
					People <span class="badge badge-primary">{{$peopleResults->count()}}</span>
				</a>
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
	</script>
@endsection