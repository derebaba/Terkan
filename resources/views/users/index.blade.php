@extends('layouts.app')

@section('content')

<div class="card">
<!-- Default panel contents -->
  <div class="card-header font-weight-bold">List of users</div>

  <!-- Table -->
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Register Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <th scope="row"><a href="users/{{ $user->id }}">{{ $user->id }}</a></th>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->created_at }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection