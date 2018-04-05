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
				return { value: dataItem.original_title, data: dataItem };
			})
		};
	},
	onSelect: function (suggestion) {
		window.location = '/movies/' + suggestion.data.id;
	}
});