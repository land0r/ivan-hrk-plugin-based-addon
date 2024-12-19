/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @param {Object} props            The block's props.
 * @param {Object} props.attributes The block's attributes.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 */
export default function save( { attributes } ) {
	const { columnsVisibility } = attributes;

	// Prepare visible columns as a comma-separated string
	const visibleColumns = columnsVisibility
		?.filter( ( column ) => column.visible )
		.map( ( column ) => column.id )
		.join( ',' );

	return (
		<div
			{ ...useBlockProps.save() }
			data-visible-columns={ visibleColumns }
		></div>
	);
}
