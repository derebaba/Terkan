@extends('layouts.app')

@section('content')
<div class="container">
	@if (Auth::check() && Auth::user()->id == $user->id)
	<div class="card">
		<div class="card-header"><h3>Change account information</h3></div>

		<div class="card-body">

			<form method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
				{{ csrf_field() }}

				
				<input type="hidden" name="_method" value="put">

				<div class="form-group">
					<label for="email">Email address</label>
					<input readonly type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $user->email }}" name="email">
				</div>

				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" class="form-control" id="exampleInputPassword1" value="{{ $user->name }}" placeholder="Enter name" required name="name">
				</div>

				<div class="form-group">
					<label for="exampleFormControlFile1">Upload new profile picture</label>
					<input type="file" class="form-control-file" id="exampleFormControlFile1" name="pic">
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
	@else
	<strong>You are not authorized to edit this user.</strong>
	@endif
</div>
@endsection