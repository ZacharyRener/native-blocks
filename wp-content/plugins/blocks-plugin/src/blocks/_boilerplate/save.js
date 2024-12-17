/*-----------------------------------*
 *	BLOCK NAME: Boilerplate
 *-----------------------------------*/
import Preview from './preview';
import { useEffect } from '@wordpress/element';
import { useBlockProps } from '@wordpress/block-editor';
import './style.scss';

/**
 * Save component
 *
 * @param {Object} props Component properties.
 * @param {Object} props.attributes Block attributes.
 * @return {JSX.Element} The save component.
 */
export default function Save( { attributes } ) {

	/**
	 * 	EXAMPLE FOR CHECK 1:
	 * 	- in this example, backgroundColor and textColor attributes are coming from block.json, under "supports" => "color".
	 * 	- it pulls the color.palette items from theme.json, and blockProps adds a class to the block.
	 * 	- that class is targeted in color-palettes.scss to apply the palette
	 * 	- for example:
	 *

	const blockProps = useBlockProps.save({
		as: 'section',
		style: {
			backgroundColor: attributes.backgroundColor,
			color: attributes.textColor,
		},
	});

	 *
	 * CHECK 1: did you add any "supports" => [x] to block.json? do you want them to add a class name to the block?
	 * - if so, the example above is a good starting point.
	*/
	const blockProps = useBlockProps.save( { as: 'section' } );

	return (
		<section { ...blockProps }>
			<Preview { ...attributes } />
		</section>
	);
}
