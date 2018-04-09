<select id="star-rating-{{$loop->index}}" name="stars" autocomplete="off">
	<option value=""></option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
</select>
<p class="{{--text-truncate--}}">
	{{ $review->body }}
</p>
<div class="mic-info">
	by <a href="{{ route('users.show', [$review->user->id]) }}">{{ $review->user->name }}</a>
	on {{ $review->created_at }}
	@if ($review->created_at->ne($review->updated_at))
		(edited on {{ $review->updated_at }})
	@endif
</div>
<div class="mic-info text-success font-weight-bold">
	<?php $likerCount = $review->likers->count() ?>
	@if ($likerCount > 1)
		@if (Auth::check() && Auth::user()->hasLiked($review))
			You and {{$likerCount - 1}} others approve this review.
		@else
			{{$likerCount}} people approve this review.
		@endif
	@elseif ($likerCount == 1)
		@if (Auth::check() && Auth::user()->hasLiked($review))
			You approve this review.
		@else
			1 person approves this review.
		@endif
	@endif
</div>
<div class="action">
	@if (Auth::check())
		<form method="GET" action="/reviews/{{$review->id}}/like" class="review-form" style="">
			{{ csrf_field() }}
			@if (!Auth::user()->hasLiked($review))
				<button type="submit" class="btn btn-success btn-sm review-button" title="Approve"> 
					<i class="far fa-thumbs-up"></i> Approve
				</button>
			@else
				<button type="submit" class="btn btn-dark btn-sm review-button" title="Unapprove"> 
					<i class="fas fa-thumbs-down"></i> Unapprove
				</button>
			@endif
		</form>
		@if (Auth::user() == $review->user)
			<a href="{{ route('reviews.edit', [$review->id]) }}" type="button" 
				class="btn btn-primary btn-sm review-button" title="Edit" style="">
				<i class="fas fa-pencil-alt"></i> Edit
			</a>
			<a href="#" type="submit" class="btn btn-danger btn-sm review-button" title="Delete"
				onclick="
					var result = confirm('Are you sure you wish to delete your review?');
					if( result ){
						event.preventDefault();
						document.getElementById('delete-review').submit();
					}
				"> 
				<i class="fas fa-trash-alt"></i> Delete
			</a>
			<form id="delete-review" method="POST" action="{{ route('reviews.destroy', [$review]) }}" class="review-form">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				
			</form>
		@endif
	@endif
</div>