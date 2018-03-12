@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">Search results on '{{$query}}'</div>
			</div>
			<div class="row">
				
				<div class="container-fluid text-center">
					@foreach($reviewables as $reviewable)
						<a href="{{ route($reviewable->type . 's.show', [$reviewable->id]) }}" 
							title="{{ $reviewable->name }}"
							class="poster" style="background: url('{!! $image->getUrl($reviewable->poster, "w154") !!}');">
							<div class="overlay">
								<p class="reviewable-name text-center">{{ $reviewable->name }}
									<select id="star-rating-{{$loop->index}}" name="stars" autocomplete="off">
										<option value=""></option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</p>
							</div>
						</a>

						{{--<span class="font-italic">({{ date("Y", strtotime($result['release_date'])) }})</span>--}}

					@endforeach
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<nav aria-label="Search pagination">
					<ul class="pagination">
						@if ($response['page'] != 1)
							<li class="page-item">
								<a class="page-link" href="#" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $max_pages; $i++)
							@if ($i == $response['page'])
								<li class="page-item active">
									<a class="page-link" href="{{ route('browseByGenre', ['genre' => $genre_id, 'page' => $i]) }}">
										{{ $i }}
									</a>
								</li>
							@else
								<li class="page-item">
									<a class="page-link" href="{{ route('browseByGenre', ['genre' => $genre_id, 'page' => $i]) }}">
										{{ $i }}
									</a>
								</li>
							@endif
						@endfor
						@if ($response['page'] != $max_pages)
							<li class="page-item">
								<a class="page-link" href="#">Next</a>
							</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
		<div class="col-md-3 sidebar-offcanvas">
			@include('partials.genresSidebar')
		</div>
	</div>
	
@endsection