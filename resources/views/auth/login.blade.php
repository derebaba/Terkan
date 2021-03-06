@extends('layouts.app')

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
				<div class="card">
					<div class="card-header">Login</div>

					<div class="card-body">
						<form class="form-horizontal" method="POST" action="{{ route('login') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
								<label for="email" class="col-md-4 control-label">E-Mail Address</label>

								
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
								
							</div>

							<div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
								<label for="password" class="col-md-4 control-label">Password</label>

								<input id="password" type="password" class="form-control" name="password" required>

								@if ($errors->has('password'))
									<span class="form-text">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
									
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="offset-1">
									<button type="submit" class="btn btn-primary">
										Login
									</button>

									<a class="btn btn-link" href="{{ route('password.request') }}">
										Forgot Your Password?
									</a>
								</div>
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
