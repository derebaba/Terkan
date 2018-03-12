@if (session('success'))
	<div class="alert alert-dismissible alert-success fade show">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>
			{{ session('success') }}
		</strong>
	</div>
@endif