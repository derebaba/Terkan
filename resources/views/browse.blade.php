@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">Search results on '{{$query}}'</div>
			</div>

			<ul class="list-group">
				@foreach($reviewables as $reviewable)
					<li class="list-group-item">
						<div class="row">
							<div class="col-sm-4 col-lg-3 text-center">
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
							<div class="col-sm-8 col-lg-9">
								<h5>
									{{ $loop->index + 1 }}. {{ $reviewable->name }}
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
									{{ sprintf ("%.2f", $reviewable->vote_average) }} / 5 ({{ $reviewable->vote_count }} votes)
								</small>
								<p>{{ $response['results'][$loop->index]['overview'] }}</p>
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
								<a class="page-link" href="{{ route('browseByGenre', ['genre' => $genre_id, 'page' => ($response['page'] - 1)]) }}" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $max_pages; $i++)
							@if ($i == $response['page'])
								<li class="page-item active">
									<a class="page-link" href="{{route('browseByGenre', ['genre' => $genre_id, 'page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@else
								<li class="page-item">
									<a class="page-link" href="{{route('browseByGenre', ['genre' => $genre_id, 'page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@endif
						@endfor
						@if ($response['page'] != $max_pages)
							<li class="page-item">
								<a class="page-link" href=
									"{{ route('browseByGenre', ['genre' => $genre_id, 'page' => ($response['page'] + 1)]) }}">
									Next
								</a>
							</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
		<div class="col-md-3 sidebar-offcanvas">
			
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