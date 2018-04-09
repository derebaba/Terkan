$(document).ready(function () {
	$(".btn").click(function () {
		$(this).addClass("disabled").attr('aria-disabled', 'true');
	});
});

//	layouts.app
$('#search-bar').devbridgeAutocomplete({
	serviceUrl: '/search/autocomplete',
	paramName: 'q',
	minChars: 3,
	dataType: 'json',
	transformResult: function (response) {
		return {
			suggestions: $.map(response.results, function (dataItem) {
				if (dataItem.media_type === 'movie')
					return { value: dataItem.original_title, data: dataItem };
				else if (dataItem.media_type === 'tv')
					return { value: dataItem.original_name, data: dataItem };
			})
		};
	},
	onSelect: function (suggestion) {
		if (suggestion.data.media_type === 'movie')
			window.location = '/movies/' + suggestion.data.id;
		else
			window.location = '/tvs/' + suggestion.data.id;
	}
});