@extends('layouts.app')

@section('head')
<!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
  <meta property="og:url"           content="{{ URL::current() }}" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Terkan" />
  <meta property="og:description"   content="{{ $user->name }}'s watchlist" />
  <meta property="og:image"         content={{ Cloudder::secureShow($user->pic) }} />
@endsection

@section('content')
	@inject('image', 'Tmdb\Helper\ImageHelper')

	<!-- Load Facebook SDK for JavaScript -->
  <div id="fb-root"></div>
  <script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<div class="card" style="width: 100%;">
		<div class="card-header">
			{{ $user->name }}'s watchlist

			<div class="fb-share-button" 
				data-href="{{ URL::current() }}" 
				data-layout="button">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
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
									<img class="img-fluid w154" src="{!! $image->getUrl($movie['poster_path'], 'w154') !!}">
								</a>
							</td>
							<td>
								<a class="text-xs-center" href="{{ route('movies.show', $movie['id']) }}">
									{{ $movie['original_title'] }}
								</a>
							</td>
							<td>
								@foreach($movie['genres'] as $genre)
									{{ $genre['name'] }},
								@endforeach
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="col-md-6">
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
									<img class="img-fluid w154" src="{!! $image->getUrl($tv['poster_path'], 'w154') !!}">
								</a>
							</td>
							<td>
								<a class="text-xs-center" href="{{ route('tvs.show', $tv['id']) }}">
									{{ $tv['original_name'] }}
								</a>
							</td>
							<td>
								@foreach($tv['genres'] as $genre)
									{{ $genre['name'] }},
								@endforeach
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection