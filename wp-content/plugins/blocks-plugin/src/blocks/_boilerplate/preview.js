/*-----------------------------------*
 *	BLOCK NAME: Boilerplate
 *-----------------------------------*/

import React from 'react';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Config from '../../utils/config';
import blockData from './block.json';
import { PreviewWrapper } from '../../utils/utils';
import './style.scss';

/**
 * Preview function
 *
 * @param {Object} props Component properties.
 * @return {JSX.Element|null} The preview component
 *
 * CHECK 1: the block name comment above should match the name of this block
 */
export default function Preview( { } ) {
	/**
	 * EXAMPLE:
	 * if you pass values to this component, access them like this: Preview( { valueOne, valueTwo } )
	 */

	return (
		<PreviewWrapper blockName={ blockData.name }>
			<h1>This is the preview React component.</h1>
		</PreviewWrapper>
	);
}
