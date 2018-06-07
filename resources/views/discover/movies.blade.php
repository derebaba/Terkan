@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">Discover movies</div>
			</div>

			<ul class="list-group">
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

			<div class="row d-flex justify-content-center">
				<nav aria-label="Search pagination">
					<ul class="pagination">
						@if ($response['page'] != 1)
							<li class="page-item">
								<a class="page-link" href="{{ route('discover.movies', ['page' => ($response['page'] - 1)]) }}" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $max_pages; $i++)
							@if ($i == $response['page'])
								<li class="page-item active">
									<a class="page-link" href="{{route('discover.movies', ['page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@else
								<li class="page-item">
									<a class="page-link" href="{{route('discover.movies', ['page' => $i])}}">
										{{ $i }}
									</a>
								</li>
							@endif
						@endfor
						@if ($response['page'] != $max_pages)
							<li class="page-item">
								<a class="page-link" href=
									"{{ route('discover.movies', ['page' => ($response['page'] + 1)]) }}">
									Next
								</a>
							</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">Filter results</div>
				<div class="card-body">
					<form class="form-horizontal" method="GET" action="{{ route("discover.movies") }}">
						<div class="form-group">
							<label for="language" class="control-label">Language</label>
							<lang-autocomplete></lang-autocomplete>
							<button type="submit" class="btn btn-primary">
								Apply filters
							</button>
						</div>
					</form>
				</div>
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

		$('#search-languages').devbridgeAutocomplete({
			dataType: 'json',
			lookup: this.languages,
			onSelect: function (suggestion) {
				this.value = suggestion;
			},
			paramName: 'language',
			showNoSuggestionNotice: true,
			transformResult: function (response) {
				return {
					suggestions: $.map(response, function (dataItem) {
						return { value: dataItem.english_name , data: dataItem };
					})
				};
			},
		});
	</script>
@endsection