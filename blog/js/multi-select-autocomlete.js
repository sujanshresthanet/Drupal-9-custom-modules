(function ($, Drupal, drupalSettings) {
	jQuery(document).ready(function() {

		var autocomplete_path = drupalSettings.autocomplete_path;
		var entity_type = drupalSettings.entity_type;
		jQuery("select.autocomplete-search").select2({
			multiple: true,
			ajax: {
				url: autocomplete_path,
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term ,
						type: entity_type
					};
				},
				processResults: function(data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                var resData = [];
                data.forEach(function(value) {
                	if (value.title.indexOf(params.term) != -1)
                		resData.push(value)
                })
                return {
                	results: jQuery.map(resData, function(item) {
                		return {
                			text: item.title,
                			id: item.id
                		}
                	})
                };
            },
            cache: true
        },
        minimumInputLength: 1
    })
	});


})(jQuery, Drupal, drupalSettings);


