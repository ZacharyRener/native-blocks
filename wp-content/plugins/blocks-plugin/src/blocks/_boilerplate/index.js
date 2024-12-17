/*-----------------------------------*
 *	BLOCK NAME: Boilerplate
 *-----------------------------------*/

import React from 'react';
import blockData from './block.json';
import edit from './edit';
import save from './save';

export const name = blockData.name;
export const settings = {
	...blockData,
	edit: edit,
	save: save,
};
