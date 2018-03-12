@extends('layouts.app')

@section('content')

<div class="card">
<!-- Default panel contents -->
  <div class="card-header font-weight-bold">Followers of <a href="{{ route('users.show', $user->id) }}">{{$user->name}}</a></div>

  <!-- Table -->
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Follow Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($followers as $follower)
      <tr>
        <th scope="row"><a href="users/{{ $follower->id }}">{{ $follower->id }}</a></th>
        <td>{{ $follower->name }}</td>
        <td>{{ $follower->created_at }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection