/**
 * Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// This file contains style definitions that can be used by CKEditor plugins.
//
// The most common use for it is the "stylescombo" plugin, which shows a combo
// in the editor toolbar, containing all styles. Other plugins instead, like
// the div plugin, use a subset of the styles on their feature.
//
// If you don't have plugins that depend on this file, you can simply ignore it.
// Otherwise it is strongly recommended to customize this file to match your
// website requirements and design properly.

CKEDITOR.stylesSet.add( 'default', [

	{
		name: 'Dropcap',
		element: 'span',
		attributes: {'class': 'dropcap'}
	},
	
	{
		name: 'Dropcap Background Warning',
		element: 'span',
		attributes: {'class': 'dropcap-bg dropcap-bg-warning'}
	},
	
	{
		name: 'Dropcap Background Dark Primary',
		element: 'span',
		attributes: {'class': 'dropcap-bg dropcap-bg-dark-primary'}
	},
	/* Object Styles */

	{
		name: 'Styled image (left)',
		element: 'img',
		attributes: { 'class': 'pull-left space-mg-right-nm' }
	},

	{
		name: 'Styled image (right)',
		element: 'img',
		attributes: { 'class': 'pull-right space-mg-left-nm' }
	},

	{
		name: 'Compact table',
		element: 'table',
		attributes: {
			cellpadding: '5',
			cellspacing: '0',
			border: '1',
			bordercolor: '#ccc'
		},
		styles: {
			'border-collapse': 'collapse'
		}
	}
] );

