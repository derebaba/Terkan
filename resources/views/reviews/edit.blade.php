@extends('layouts.app')
{{--Needs $review, $movie --}}
@section('content')
	@auth
		@if (Auth::user()->id == $review->user_id)
			<div class="card">
				@if ($review->isMovie())
					<div class="card-header">
						<h3>Edit review on <a href="{{ route('movies.show', [$review->reviewable_id]) }}">
							{{$reviewable['original_title']}}</a>
						</h3>
					</div>
				@elseif ($review->isTv())
					<div class="card-header">
						<h3>Edit review on <a href="{{ route('tvs.show', [$review->reviewable_id]) }}">
							{{$reviewable['original_name']}}</a>
						</h3>
					</div>
				@endif
				<div class="card-body">
					<form method="post" action="{{ route('reviews.update', [$review]) }}">
						{{ csrf_field() }}
						{{ method_field('PUT') }}

						<div class="form-group">
							<label for="body">Review</label>
							<textarea rows="3" class="form-control autosize-target" placeholder="Enter review" name="body">{{$review->body}}</textarea>
						</div>

						<div class="form-group row">
							<label for="star-rating" class="col-3 col-md-2 col-lg-1 offset-1">Stars: </label>
							<select id="edit-star-rating" class="col-2" name="stars" autocomplete="off">
								<option value=""></option>
							@for ($i = 1; $i <= 5; $i++)
								@if ($i == $review->stars)
									<option value="{{$i}}" selected>{{$i}}</option>
								@else
									<option value="{{$i}}">{{$i}}</option>
								@endif
							@endfor
							</select>
						</div>

						<input type="hidden" name="reviewable_id" value="{{ $reviewable['id'] }}">
						<input type="hidden" name="review_id" value="{{ $review->id }}">
						<button type="submit" class="btn btn-primary offset-1 align-self-end">Submit</button>
					</form>
				</div>
			</div>
		@else
			<strong>You cannot edit someone else's review.</strong>
		@endif
	@endauth
@endsection