/* eslint-disable */
jQuery( document ).ready( function ( $ ) {
	$( '.wp-block-ivan-hrk-api-based-addon' ).each( function () {
		const block = $( this );

		// Get the visible columns from the block's data attribute
		const visibleColumns =
			block.data( 'visible-columns' )?.split( ',' ) || [];

		// Fetch and render data
		fetchAndRenderTable( block, visibleColumns );
	} );

	/**
	 * Fetches data from the server and renders the table.
	 *
	 * @param {jQuery} block         The block element.
	 * @param {Array} visibleColumns Array of visible column IDs.
	 */
	function fetchAndRenderTable( block, visibleColumns ) {
		$.ajax( {
			url: IvanApiBasedAddon.ajax_url,
			method: 'POST',
			data: {
				action: 'ivan_api_based_fetch_data',
				nonce: IvanApiBasedAddon.nonce,
				visible_columns: visibleColumns, // Pass visible columns to the server
			},
			success: function ( response ) {
				if ( validateResponse( response ) ) {
					renderTable( block, response.data.data );
				} else {
					renderMessage( block, 'No data available.' );
				}
			},
			error: function () {
				renderMessage( block, 'Failed to load data.' );
			},
		} );
	}

	/**
	 * Validates the AJAX response.
	 *
	 * @param {Object} response - The AJAX response.
	 * @return {boolean} - True if the response is valid, false otherwise.
	 */
	function validateResponse( response ) {
		return (
			response.success &&
			response?.data?.data?.headers &&
			response?.data?.data?.rows
		);
	}

	/**
	 * Renders a message inside the block.
	 *
	 * @param {jQuery} block   The block element.
	 * @param {string} message The message to display.
	 */
	function renderMessage( block, message ) {
		block.html( `<p class="block-message">${ message }</p>` );
	}

	/**
	 * Renders a table inside the block.
	 *
	 * @param {jQuery} block The block element.
	 * @param {Object} data  The table data (headers and rows).
	 */
	function renderTable( block, data ) {
		const table = $( '<table>' ).addClass( 'api-data-table' );

		// Render header
		const thead = $( '<thead>' );
		const headerRow = $( '<tr>' );
		data.headers.forEach( ( header ) => {
			headerRow.append( $( '<th>' ).text( header ) );
		} );
		thead.append( headerRow );
		table.append( thead );

		// Render body
		const tbody = $( '<tbody>' );
		data.rows.forEach( ( row ) => {
			const rowElement = $( '<tr>' );
			Object.values( row ).forEach( ( value ) => {
				rowElement.append( $( '<td>' ).text( value || '-' ) );
			} );
			tbody.append( rowElement );
		} );
		table.append( tbody );

		block.html( table );
	}
} );
