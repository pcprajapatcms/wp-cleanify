jQuery(document).ready(function ($) {
	$('.checkbox_inp').change(function () {
		const checkbox = $(this);
		const optionName = checkbox
			.attr('name')
			.replace('wp_cleanify_options[', '')
			.replace(']', '');
		const newValue = checkbox.is(':checked') ? 1 : 0;

		// AJAX request to update option value
		$.ajax({
			url: ajaxurl, // WordPress AJAX URL
			type: 'POST',
			data: {
				action: 'wp_cleanify_handle_checkbox_toggle',
				security: $('#wp_cleanify_ajax_nonce').val(), // Nonce value
				option_name: optionName,
				new_value: newValue,
			},
			success(response) {
				// Handle success response
				console.log(response.data);
				$('.wp-cleanify-toast').fadeIn(100);
				setTimeout(() => {
					$('.wp-cleanify-toast').fadeOut(100);
				}, 4000);
			},
			error(error) {
				// Handle error response
				console.log(error.responseText);
			},
		});
	});
	$(document).on('click', '.msg-close-btn', function () {
		$('.wp-cleanify-toast').fadeOut();
	});
});
