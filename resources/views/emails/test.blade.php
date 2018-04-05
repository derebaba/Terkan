@extends('layouts.app')

@section('content')
	@foreach ($mail as $field)
		{{ $field }}
	<br>
	@endforeach
@endsection