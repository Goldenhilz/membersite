$(document).ready(function() {
		var countries = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('countries'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: 'php_includes/get_country.php?query=%QUERY'
		});
	
	countries.initialize();

	$('#countries').typeahead({
		hint: true,
		hightlight: true,
		minLength: 2
	}, {
		name: 'country',
		displayKey: 'name',
		source: countries.ttAdapter()
	});

});