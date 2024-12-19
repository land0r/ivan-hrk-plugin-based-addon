/* eslint-disable */
jQuery( document ).ready( function ( $ ) {
	$( '#clear-cache-btn' ).on( 'click', function ( e ) {
		e.preventDefault();

		// Add loading state to button
		const button = $( this );
		button.prop( 'disabled', true ).text( 'Clearing...' );

		$.ajax( {
			url: IvanApiBasedAddon.ajax_url, // Localized in PHP
			method: 'POST',
			data: {
				action: 'ivan_api_based_clear_cache',
				nonce: IvanApiBasedAddon.nonce, // Security nonce
			},
			success: function ( response ) {
				if ( response.success ) {
					alert( response.data.message ); // Show success message
					location.reload();
					// TODO: show a dismissible message in the admin panel + live reload of the data.
				} else {
					alert( response.data.message || 'Error clearing cache.' );
				}
			},
			error: function () {
				alert( 'An error occurred while clearing the cache.' );
			},
			complete: function () {
				// Re-enable the button
				button.prop( 'disabled', false ).text( 'Clear Cache' );
			},
		} );
	} );
} );
