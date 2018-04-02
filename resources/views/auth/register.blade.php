@extends('layouts.app')

@section('head')

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

						<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
							<label for="name" class="control-label">Name</label>


							<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

							@if ($errors->has('name'))
								<span class="form-text">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif

						</div>

						<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
							<label for="email" class="control-label">E-Mail Address</label>

							<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

							@if ($errors->has('email'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif

							<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>

						</div>

						<div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
							<label for="password" class="control-label">Password</label>


							<input id="password" type="password" class="form-control" name="password" required>

							@if ($errors->has('password'))
								<span class="form-text">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif

						</div>

						<div class="form-group">
							<label for="password-confirm" class="control-label">Confirm Password</label>


							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary" style="width: 100%;">
								Register
							</button>
						</div>
						<div class="hr-text">or</div>

						@include('partials.facebookButton')
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
