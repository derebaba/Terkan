$(document).ready(function () {
	$(".btn").click(function () {
		$(this).addClass("disabled").attr('aria-disabled', 'true');
	});
});

//	layouts.app
$('#search-bar').devbridgeAutocomplete({
	dataType: 'json',
	minChars: 3,
	onSelect: function (suggestion) {
		if (suggestion.data.media_type === 'movie')
			window.location = '/movies/' + suggestion.data.id;
		else if (suggestion.data.media_type === 'tv')
			window.location = '/tvs/' + suggestion.data.id;
		else if (suggestion.data.media_type === 'user')
			window.location = '/users/' + suggestion.data.id;
	},
	paramName: 'search',
	serviceUrl: '/search/autocomplete',
	showNoSuggestionNotice: true,
	transformResult: function (response) {
		return {
			suggestions: $.map(response, function (dataItem) {
				if (dataItem.media_type === 'movie')
					return { value: dataItem.original_title, data: dataItem };
				else if (dataItem.media_type === 'tv')
					return { value: dataItem.original_name, data: dataItem };
				else if (dataItem.media_type === 'user')
					return { value: dataItem.name, data: dataItem };
			})
		};
	},
	width: 'flex'
});