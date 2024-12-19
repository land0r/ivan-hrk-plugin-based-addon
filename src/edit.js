/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import { ProgressBar } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

export default function Edit() {
	const blockProps = useBlockProps();

	// State for data, loading, and error handling
	const [ response, setResponse ] = useState( [] );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );

	useEffect( () => {
		// Fetch data from the AJAX handler
		const fetchData = async () => {
			try {
				setLoading( true );

				const ajaxResponse = await wp.ajax.post(
					'ivan_api_based_fetch_data',
					{
						// eslint-disable-next-line no-undef
						nonce: IvanApiBasedAddon.nonce,
					}
				);

				setResponse( ajaxResponse );
				setLoading( false );
			} catch ( err ) {
				setError(
					__( 'Failed to fetch data.', 'ivan-hrk-api-based-addon' )
				);
				setLoading( false );
			}
		};

		fetchData();
	}, [] ); // Empty dependency array to fetch data only once on load

	// Render loading, error, or data table
	return (
		<div { ...blockProps }>
			{ loading && (
				<>
					<ProgressBar />
					<p>{ __( 'Loading…', 'ivan-hrk-api-based-addon' ) }</p>
				</>
			) }

			{ error && <p className="error">{ error }</p> }

			{ ! loading && ! error && (
				<table className="api-data-table">
					<thead>
						<tr>
							{ response.data?.headers?.map(
								( header, index ) => (
									<th key={ index }>{ header }</th>
								)
							) }
						</tr>
					</thead>
					<tbody>
						{ response.data?.rows?.map( ( row, rowIndex ) => (
							<tr key={ rowIndex }>
								{ Object.values( row ).map(
									( cell, cellIndex ) => (
										<td key={ cellIndex }>{ cell }</td>
									)
								) }
							</tr>
						) ) }
					</tbody>
				</table>
			) }
		</div>
	);
}
