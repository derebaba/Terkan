@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">Discover {{ $route }}</div>
			</div>
			@yield('results')

			<div class="row d-flex justify-content-center">
				<nav aria-label="Search pagination">
					<ul class="pagination">
						@if ($response['page'] != 1)
							<li class="page-item">
								<a class="page-link" href="{{ route('discover.' . $route, array_merge($request->all(), ['page' => $response['page'] - 1])) }}" tabindex="-1">Previous</a>
							</li>
						@endif
						@for ($i = 1; $i <= $max_pages; $i++)
							@if ($i == $response['page'])
								<li class="page-item active">
									<a class="page-link" href="{{route('discover.' . $route, array_merge($request->all(), ['page' => $i])) }}">
										{{ $i }}
									</a>
								</li>
							@else
								<li class="page-item">
									<a class="page-link" href="{{route('discover.' . $route, array_merge($request->all(), ['page' => $i])) }}">
										{{ $i }}
									</a>
								</li>
							@endif
						@endfor
						@if ($response['page'] != $max_pages)
							<li class="page-item">
								<a class="page-link" href=
									"{{ route('discover.' . $route, array_merge($request->all(), ['page' => $response['page'] + 1])) }}">
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
					<form class="form-horizontal" method="GET" action="{{ route("discover." . $route) }}">
						<div class="form-group">
							<label for="language" class="control-label">Language</label>
							<lang-autocomplete old-language-code="{{ $request->languageCode }}"></lang-autocomplete>
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
		$(document).ready(function() {
			$(window).keydown(function(event){
				//	if lang-autocomplete list is present, do not submit form. In this case, enter selects item from list
				if(event.keyCode == 13 && $(".v-autocomplete-list").length) {
					event.preventDefault();
					return false;
				}
			});
		});

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