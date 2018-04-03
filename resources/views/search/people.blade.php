@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-12 col-md-9">
			<div class="row">
				<div class="card" style="width: 100%;">
					<div class="card-header">
						Search people: {{$query}} ({{ $peopleResults->count() }} results)
					</div>
				</div>
			</div>
			<div class="row d-block d-md-none">
				<nav class="navbar navbar-expand-sm bg-light" id="search-navbar">
					<ul class="navbar-nav" id="navbar-list">
						<li>
							Jump to:
						</li>
						<li>
							<a href="{{ route('search.movie', ['page' => 1, 'q' => $query]) }}"
								class="list-group-item list-group-item-action bar-button">
								Movies <span class="badge badge-primary">{{$movieResponse['total_results']}}</span>
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
								class="list-group-item list-group-item-action bar-button active">
								People <span class="badge badge-light">{{$peopleResults->count()}}</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>

			<div class="row">
				<ul class="list-group">
					@if ($peopleResults->isEmpty())
						<li class="list-group-item">
							Nobody found.
						</li>
					@endif
					@foreach($peopleResults as $result)
						<li class="list-group-item">
							<div class="row">
								<div class="col-sm-4 col-lg-3 text-center">
									<a href="{{ route('users.show', $result->id) }}" 
										title="{{ $result->name }}">
										@if ($result->pic != null)
											<img src={{ Cloudder::secureShow($result->pic) }} class="img-fluid img-thumbnail rounded-circle" alt="profil-resmi" 
											height="160px" width="160px">
										@else
											<img src="/profilepics/generic_profile_pic.png" class="img-thumbnail rounded-circle" 
											alt="profil-resmi" height="160px" width="160px">
										@endif
									</a>
								</div>
								<div class="col-sm-8 col-lg-9">
									<h5>
										<a href="{{ route('users.show', $result->id) }}">{{ $loop->index + 1 }}. {{ $result->name }}</a>
									</h5>
								</div>
							</div>
						</li>
					@endforeach	
				</ul>
			</div>

			{{--pagination için $results->links() --}}
		</div>
		<div class="col-md-3 d-none d-md-block">
			<div class="list-group">
				<div class="list-group-item bg-secondary text-white"> Jump to:</div>
				<a href="{{ route('search.movie', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action">
					Movies <span class="badge badge-primary">{{$movieResponse['total_results']}}</span>
				</a>
				<a href="{{ route('search.tv', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action">
					TV shows <span class="badge badge-primary">{{$tvResponse['total_results']}}</span>
				</a>
				<a href="{{ route('search.people', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action active">
					People <span class="badge badge-light">{{$peopleResults->count()}}</span>
				</a>
			</div>
		</div>
	</div>
	
@endsection