@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="/css/bootstrap-social.css">
@endsection

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
			<div class="card">
				<div class="card-header">Register</div>

				<div class="card-body">
					<form class="form-horizontal" method="POST" action="{{ route('register') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							<label for="name" class="control-label">Name</label>


							<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

							@if ($errors->has('name'))
								<span class="help-block">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif

						</div>

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="control-label">E-Mail Address</label>

							<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif

							<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>

						</div>

						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="password" class="control-label">Password</label>


							<input id="password" type="password" class="form-control" name="password" required>

							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif

						</div>

						<div class="form-group">
							<label for="password-confirm" class="control-label">Confirm Password</label>


							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

						</div>

						<div class="form-group">
							<div class="col-6 offset-1">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</div>
						<hr>

						@include('partials.facebookButton')
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
