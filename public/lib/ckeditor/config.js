/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights
 *          reserved. For licensing, see LICENSE.md or
 *          http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	config.width = 850;
	config.height = 500; 
	config.allowedContent = true;
	config.filebrowserBrowseUrl = '/admin/media';
	config.filebrowserImageBrowseUrl = '/admin/media';
	config.filebrowserWindowWidth = 1024;
	config.filebrowserWindowHeight = 700;
	config.removePlugins = 'forms,about';
	config.bodyClass = 'body-ckeditor';
	config.extraPlugins = 'oembed';
//	config.enterMode = CKEDITOR.ENTER_BR; 
	config.contentsCss = [
			'/lib/bootstrap/css/bootstrap.min.css?' + Math.random(),
			'/css/style.css?' + Math.random() ];
	config.font_names = "bold;bolder;boldest";
	config.fontSize_sizes = 'small/size-sm;normal/size-nm;semi medium/size-semi-md;medium/size-md;large/size-lg;xxxlarge/size-xxxlg';
//	config.colorButton_colors = 'light/E6FBFF,inverse/8E9AA9,warning/FFB674,info/0099CC,dark-primary/0D1D42';
	config.colorButton_colors = 'E6FBFF,8E9AA9,FFB674,0099CC,0D1D42';
	config.coreStyles_bold = {
		element : 'span',
		attributes : {
			'class' : 'bold'
		}
	};

	config.coreStyles_italic = {
		element : 'span',
		attributes : {
			'class' : 'italic'
		}
	};

	config.font_style = {
		element : 'span',
		attributes : {
			'class' : '#(family)'
		},
		overrides : [ {
			element : "font",
			attributes : {
				face : null
			}
		} ]
	};

	config.fontSize_style = {
		element : 'span',
		attributes : {
			'class' : '#(size)'
		},
		overrides : [ {
			element : "font",
			attributes : {
				size : null
			}
		} ]
	};
};
