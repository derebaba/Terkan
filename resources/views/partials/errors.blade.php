@if ($errors->any())
	<div class="alert alert-dismissible alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<li><strong>{!! $errors !!}</strong></li>
	</div>
@endif