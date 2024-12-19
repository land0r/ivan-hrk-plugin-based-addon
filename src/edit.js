import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import { PanelBody, ProgressBar, ToggleControl } from '@wordpress/components';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { columnsVisibility } = attributes;

	// State for data, loading, and error handling
	const [ response, setResponse ] = useState( [] );
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );

	// Handle toggling column visibility
	const handleToggleColumn = ( columnId ) => {
		// Update visibility for the selected column
		const updatedVisibility = columnsVisibility.map(
			( column ) =>
				column.id === columnId
					? { ...column, visible: ! column.visible } // Toggle visibility
					: column // Keep other columns unchanged
		);

		// Update attributes with the new visibility state
		setAttributes( { columnsVisibility: updatedVisibility } );
	};

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

				setResponse(
					ajaxResponse || { data: { headers: [], rows: [] } }
				);
				setLoading( false );
			} catch ( err ) {
				setError(
					__( 'Failed to fetch data.', 'ivan-hrk-api-based-addon' )
				);
				setLoading( false );
			}
		};

		fetchData();
	}, [] ); // Empty dependency array ensures fetch runs only once on load

	useEffect( () => {
		if ( response?.data?.headers?.length && response?.data?.rows?.length ) {
			// Extract keys from the first row to use as IDs
			const rowKeys = Object.keys( response.data.rows[ 0 ] );

			// Map headers with rowKeys to initialize visibility
			const updatedVisibility = response.data.headers.map(
				( header, index ) => ( {
					id: rowKeys[ index ] || `col-${ index }`, // Match keys with headers
					label: header,
					visible: columnsVisibility
						? columnsVisibility.find(
								( column ) => column.id === rowKeys[ index ]
						  )?.visible ?? true
						: true, // Preserve visibility state if already set
				} )
			);

			setAttributes( { columnsVisibility: updatedVisibility } );
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ response, setAttributes ] );

	// Render loading, error, or data table
	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Table Settings', 'ivan-hrk-api-based-addon' ) }
				>
					{ columnsVisibility?.map( ( header ) => (
						<ToggleControl
							key={ header.id }
							label={ header.label }
							checked={ header.visible }
							onChange={ () => handleToggleColumn( header.id ) }
						/>
					) ) }
				</PanelBody>
			</InspectorControls>

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
							{ columnsVisibility
								?.filter( ( column ) => column.visible )
								.map( ( column ) => (
									<th key={ column.id }>{ column.label }</th>
								) ) }
						</tr>
					</thead>
					<tbody>
						{ response.data?.rows?.map( ( row, rowIndex ) => (
							<tr key={ rowIndex }>
								{ columnsVisibility
									?.filter( ( column ) => column.visible )
									.map( ( column ) => (
										<td key={ column.id }>
											{ row[ column.id ] || '-' }
										</td>
									) ) }
							</tr>
						) ) }
					</tbody>
				</table>
			) }
		</div>
	);
}
