﻿/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width = 600;
	config.toolbar =
	[
	    ['Source','Preview'],
	    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
	    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    '/',
	    ['Bold','Italic','Underline','Strike'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','Anchor'],
	    ['Image','HorizontalRule','SpecialChar'],
	    '/',
	    ['Styles','Format','Font','FontSize'],
	    ['TextColor','BGColor'],
	    ['Maximize', 'ShowBlocks']
	];
};
