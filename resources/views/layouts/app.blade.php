<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ isset($page_title) ? $page_title . ' - ' : '' }}{{ config('app.name', 'Terkan') }}</title>

		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">

		<!-- Styles -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" 
			integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		{{--<link rel="stylesheet" href="/css/fontawesome-stars.css">  unused --}}
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:600" rel="stylesheet">
		<link rel="stylesheet" href="/css/fontawesome-stars-o.css">
		<link rel="stylesheet" href="/css/custom.css?v=1.1"> 

		@yield('head')

	</head>
	<body class="bg-light">
		<div id="app">
			
			<nav class="navbar navbar-dark bg-dark navbar-static-top navbar-expand">
				<div class="container">
					

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggler collapsed" data-toggle="collapse" 
					data-target="#app-navbar-collapse" aria-expanded="false">
						<span class="sr-only">Toggle Navigation</span>
					</button>

					<!-- Branding Image -->
					<a href="/"><img src="/t.png" height="40px" width="40px"></a>

					<div class="collapse navbar-collapse" id="app-navbar-collapse">
						<!-- Left Side Of Navbar -->
						<ul class="navbar-nav mr-auto center-block">
							<li class="nav-item center-block">
								<form id="search-form" class="form-inline" method="get" action="/search/movie">
									<div class="input-group">
										@if (!empty($query))
											<input class="form-control" type="text" name="q" placeholder="Search movies, TV series, friends" 
											size="30" required value="{{$query}}" id="search-bar">
										@else
											<input class="form-control" type="text" name="q" placeholder="Search movies, TV series, friends" 
											size="30" required id="search-bar">
										@endif
										<input type="hidden" name="page" value="1">
										<span class="input-group-btn">
											<button class="btn btn-outline-secondary disabled" type="submit">
												<i class="fas fa-search"></i>
											</button>
										</span>
									</div>
								</form>
							</li>
						</ul>

						<!-- Right Side Of Navbar -->
						<ul class="navbar-nav ml-auto">
							<!-- Authentication Links -->
							@guest
								<li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
								<li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
							@else
							{{--	@role('admin')
									<li class="dropdown nav-item">
										<a href="#" class="dropdown-toggle nav-link fa fa-gear" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
											Admin
										</a>
										<div class="dropdown-menu" aria-labelled-by="dropdown-corner">
											<a href="{{ route('users.index') }}" class="dropdown-item"><i class="fa fa-users fa-fw"></i>Users</a>
											<a href="{{ route('movies.index') }}" class="dropdown-item"><i class="fa fa-film fa-fw"></i>Movies</a>
										</div>

									</li>
								@endrole--}}
								<li class="dropdown nav-item" id="dropdown-corner">
									<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
										<i class="far fa-user d-none d-sm-inline-block"></i> {{ Auth::user()->name }}
									</a>
									<div class="dropdown-menu" aria-labelled-by="dropdown-corner" style="right:0; left:auto;">
										@role('admin')
										<a href="/admin" class="dropdown-item">
											<i class="fas fa-cogs"></i>
											Admin panel
										</a>
										@endrole
										<a href="{{ route('users.show', Auth::user()->id) }}" class="dropdown-item">
											<i class="fas fa-user fa-fw"></i>
											My profile
										</a>

										<a href="{{ route('logout') }}" class="dropdown-item"
											onclick="event.preventDefault();
														document.getElementById('logout-form').submit();">
											<i class="fas fa-sign-out-alt fa-fw"></i>
											Logout
										</a>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>

										
									</div>
								</li>
							@endguest
						</ul>
					</div>
				</div>
			</nav>
			<div class="container">
				
					@include('partials.errors')
					@include('partials.success')

					@yield('content')
					
				
			</div>
			
		</div>

		@include('cookieConsent::index')
		<hr>
		<div class="container">
			<div id="footer">
				<small>
					<a href="/info/privacypolicy">Privacy policy</a>
					|
					<a href="/info/contact">Contact us</a>
					|
					<a href="https://github.com/derebaba/Terkan" target="_blank"><i class="fab fa-github"></i> Github</a>

					<span class="pull-right">
						This product uses the TMDb API but is not endorsed or certified by TMDb.
						<a href="https://www.themoviedb.org/" style="display: inline;">
							<img height="30px" 
							src="https://www.themoviedb.org/static_cache/v4/logos/primary-green-d70eebe18a5eb5b166d5c1ef0796715b8d1a2cbc698f96d311d62f894ae87085.svg">
						</a>
					</span>
				</small>
			</div>
		</div>
		<!-- Scripts; jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" 
			integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" 
			crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" 
			integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" 
			crossorigin="anonymous"></script>
		<script src="/js/jquery.barrating.min.js"></script>
		<script src="/js/jquery.autocomplete.min.js"></script>
		<script src="/js/custom.js"></script>
		{{--<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}}

		@include('footer')
	</body>
</html>
