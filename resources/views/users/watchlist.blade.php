@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')

	<div class="card" style="width: 100%;">
		<div class="card-header">
			{{ $user->name }}'s watchlist
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<table class="table table-striped">
				<thead>
					<th colspan="4">Movies</th>
				</thead>
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Poster</th>
						<th scope="col">Title</th>
						<th scope="col">Genres</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($movies as $movie)
						<tr>
							<th scope="row">{{ $loop->iteration }}</th>
							<td>
								<a href="{{ route('movies.show', $movie['id']) }}" 
									title="{{ $movie['original_title'] }}">
									<img class="w154" src="{!! $image->getUrl($movie['poster_path'], 'w154') !!}">
								</a>
							</td>
							<td>
								<a class="text-xs-center" href="{{ route('movies.show', $movie['id']) }}">
									{{ $movie['original_title'] }}
								</a>
							</td>
							<td>
								@foreach($movie['genres'] as $genre)
									{{ $genre['name'] }}
								@endforeach
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="col-6">
			<table class="table table-striped">
				<thead>
					<th colspan="4">TV shows</th>
				</thead>
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Poster</th>
						<th scope="col">Name</th>
						<th scope="col">Genres</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tvs as $tv)
						<tr>
							<th scope="row">{{ $loop->iteration }}</th>
							<td>
								<a href="{{ route('tvs.show', $tv['id']) }}" 
									title="{{ $tv['original_name'] }}">
									<img class="w154" src="{!! $image->getUrl($tv['poster_path'], 'w154') !!}">
								</a>
							</td>
							<td>
								<a class="text-xs-center" href="{{ route('tvs.show', $tv['id']) }}">
									{{ $tv['original_name'] }}
								</a>
							</td>
							<td>
								@foreach($tv['genres'] as $genre)
									{{ $genre['name'] }}
								@endforeach
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection