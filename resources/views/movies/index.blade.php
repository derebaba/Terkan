@extends('layouts.app')

@section('content')

<div class="card">
<!-- Default panel contents -->
  <div class="card-header font-weight-bold">List of popular movies</div>
	
  <!-- Table -->
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Release Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($movies as $movie)
      <tr>
        <th scope="row"><a href="movies/{{ $movie->getId() }}">{{ $movie->getId() }}</a></th>
        <td>{{ $movie->getOriginalTitle() }}</td>
        <td>{{ $movie->getReleaseDate()->format('Y-m-d') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection