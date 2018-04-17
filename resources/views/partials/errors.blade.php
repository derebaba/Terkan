@if ($errors->any())
	<div class="alert alert-dismissible alert-danger" role="alert">
		@foreach ($errors->all() as $error)
			<li>{!! $error !!}</li>
		@endforeach
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif