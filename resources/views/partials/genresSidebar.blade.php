<ul class="list-group">
	<li class="list-group-item list-group-header"> Browse by genre</li>
	@for ($i = 0; $i < 19; $i++)
		
		@if ($genre_id == $genres[$i]['id'])
			<a href="{{ route('browseByGenre', ['genre' => $genres[$i]['id'], 'page' => 1]) }}" 
			class="list-group-item list-group-item-action active">
				<i class=""></i> {{ $genres[$i]['name'] }}
			</a>
		@else
			<a href="{{ route('browseByGenre', ['genre' => $genres[$i]['id'], 'page' => 1]) }}" 
			class="list-group-item list-group-item-action">
				<i class=""></i> {{ $genres[$i]['name'] }}
			</a>
		@endif
		
	@endfor
</ul>