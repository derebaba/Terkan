<ul class="list-group">
	<li class="list-group-item list-group-header"> Browse by genre</li>
	@foreach ($genres as $genre)
		
		@if ($genre_id == $genre['id'])
			<a href="{{ route('browseByGenre', ['genre' => $genre['id'], 'page' => 1]) }}" 
			class="list-group-item list-group-item-action active">
				<i class=""></i> {{ $genre['name'] }}
			</a>
		@else
			<a href="{{ route('browseByGenre', ['genre' => $genre['id'], 'page' => 1]) }}" 
			class="list-group-item list-group-item-action">
				<i class=""></i> {{ $genre['name'] }}
			</a>
		@endif
		
	@endforeach
</ul>