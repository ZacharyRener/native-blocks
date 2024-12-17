/*-----------------------------------*
 *	BLOCK NAME: Boilerplate
 *-----------------------------------*/

import React from 'react';
import './editor.scss';
import { edit, globe } from '@wordpress/icons';
import { BlockControls, useBlockProps } from '@wordpress/block-editor';
import { Placeholder, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Preview from './preview';
import Config from './../../utils/config';

/**
 *
 * @param {Object} props Component properties.
 * @param {Object} props.attributes Block attributes.
 * @param {Function} props.setAttributes Function to set block attributes.
 * @return {JSX.Element} The edit component.
 */
export default function Edit( { attributes, setAttributes } ) {

	const [ isPreview, setPreview ] = useState( true );
	const blockProps = useBlockProps();

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						label={ __( 'Edit', Config['text-domain'] ) }
						icon={ edit }
						onClick={ () => setPreview( !isPreview ) }
					/>
				</ToolbarGroup>
			</BlockControls>
			<section { ...blockProps } >
				{ isPreview ? (
					<Preview />
				) : (
					<Placeholder
						icon={ globe }
						label={ __( 'Boilerplate Component', Config['text-domain'] ) }
						isColumnLayout
						instructions={ __( 'Lorem ipsum dolor sit amet', Config['text-domain'] ) }
					>
						<p>{ __( 'This is the Placeholder component. Your controls will go here.', Config['text-domain'] ) }</p>
					</Placeholder>
				) }
			</section>
		</>
	);
}
