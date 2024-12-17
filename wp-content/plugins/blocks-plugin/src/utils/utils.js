import React from 'react';
import { useEffect, useState } from 'react';

export const PreviewWrapper = ( { blockName, children } ) => {

	// blockName needs its "/" replaced with "-"
	blockName = blockName.replaceAll( '/', '-' );

	return (
		<div
			className={blockName}
		>
			{ children }
		</div>
	);
};

export const getCurrentStyle = ( blockProps ) => {
	// blockProps.className has multiple classes, but also is-style-x
	// we need to extract the is-style-x class (if it exists)
	// then extract the x from is-style-x
	// then return x
	let style = '';
	const classes = blockProps.className.split(' ');
	classes.forEach( ( className ) => {
		if ( className.includes( 'is-style-' ) ) {
			style = className.split( 'is-style-' )[1];
		}
		if( style.includes( '-' + blockProps[ 'data-block' ] ) ) {
			style = style.replaceAll( '-' + blockProps[ 'data-block' ], '' );
		}
	} );
	return style;
}

export const Button = ({ url, text, is_new_tab }) => {
	return (
		<a
			href={url}
			target={is_new_tab ? '_blank' : '_self'}
			rel="noopener noreferrer"
			className="btn"
		>
			{ text }
		</a>
	);
}