<script>

$('#average-rating').barrating({
	theme: 'fontawesome-stars-o',
	showSelectedRating: true,
	initialRating: window.averageRating,
	readonly: true
});

$('#star-rating').barrating({
	theme: 'fontawesome-stars-o',
	showSelectedRating: true,
	initialRating: 0
});

//	reviews.edit
$('#edit-star-rating').barrating({
	theme: 'fontawesome-stars-o',
	showSelectedRating: true,
	initialRating: window.stars,
});

//	search, home:news
if ($('#star-rating-0').length) {
	for (i = 0; i < 40; i++) {
		$('#star-rating-' + i).barrating({
			theme: 'fontawesome-stars-o',
			showSelectedRating: true,
			initialRating: window.stars[i],
			readonly: true
		});
	}
}

//	home:recommendations
if ($('#recommendation-rating-0').length) {
	for (i = 0; i < 40; i++) {
		$('#recommendation-rating-' + i).barrating({
			theme: 'fontawesome-stars-o',
			showSelectedRating: true,
			initialRating: window.recommendationStars[i],
			readonly: true
		});
	}
}

</script>