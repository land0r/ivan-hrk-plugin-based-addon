/* eslint-disable */
jQuery( document ).ready( function ( $ ) {
	$( '#clear-cache-btn' ).on( 'click', function ( e ) {
		e.preventDefault();

		const button = $( this );
		button.prop( 'disabled', true ).text( IvanApiBasedAddon.clearingCache );

		$.ajax( {
			url: IvanApiBasedAddon.ajax_url,
			method: 'POST',
			data: {
				action: 'ivan_api_based_clear_cache',
				nonce: IvanApiBasedAddon.nonce,
			},
			success: function ( response ) {
				if ( response.success ) {
					alert( response.data.message );
					location.reload();
					// TODO: Possible enhancement – show a dismissible message in the admin panel + live reload of the data.
				} else {
					alert(
						response.data.message || IvanApiBasedAddon.errorMessage
					);
				}
			},
			error: function () {
				alert( IvanApiBasedAddon.errorMessage );
			},
			complete: function () {
				button.prop( 'disabled', false ).text( 'Clear Cache' );
			},
		} );
	} );
} );
