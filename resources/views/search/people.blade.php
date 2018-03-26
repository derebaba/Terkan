@extends('layouts.app')

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')
	<div class="row">
		
		<div class="col-md-9">
			<div class="card">
				<div class="card-header font-weight-bold">
					Search people: {{$query}} ({{ $results->count() }} results)
				</div>
			</div>

			<ul class="list-group">
				@if ($results->isEmpty())
					<li class="list-group-item">
						Nobody found.
					</li>
				@endif
				@foreach($results as $result)
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

			{{-- $results->links() --}}
		</div>
		<div class="col-md-3">
			<div class="list-group">
				<a href="{{ route('search.movie', ['page' => $movieResponse['page'], 'q' => $query]) }}"
					class="list-group-item list-group-item-action">Movies ({{$movieResponse['total_results']}})</a>
				<a href="{{ route('search.tv', ['page' => $tvResponse['page'], 'q' => $query]) }}"
					class="list-group-item list-group-item-action">Tv ({{$tvResponse['total_results']}})</a>
				<a href="{{ route('search.people', ['page' => 1, 'q' => $query]) }}"
					class="list-group-item list-group-item-action active">People ({{$results->count()}})</a>
			</div>
		</div>
	</div>
	
@endsection