/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	tinymce.create('tinymce.plugins.ChangeLangPlugin', {
		init : function(ed, url) {
			ed.addCommand('mceClang', function() {
				ed.windowManager.open({
					file : url + '/edit_lang.htm',
					width : 350 + parseInt(ed.getLang('changelang.clang_delta_width', 0)),
					height : 300 + parseInt(ed.getLang('changelang.clang_delta_width', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			ed.addCommand('mceAddlang', function() {
				ed.windowManager.open({
					file : url + '/add_lang_attributes.htm',
					width : 380,
					height : 380,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			// Register buttons
			ed.addButton('clang', {
				title : 'changelang.clang_desc',
				cmd : 'mceClang',
				image : url + '/img/change_lang_picto.png'});
			ed.addButton('langattribs', {
				title : 'changelang.attribs_desc',
				cmd : 'mceAddlang',
				image : url + '/img/add_lang_picto.png'});

			ed.onNodeChange.add(function(ed, cm, n, co) {
				n = ed.dom.getParent(n, 'SPAN');

				cm.setDisabled('span', co);
				cm.setDisabled('attribs', n && n.nodeName == 'BODY');
				cm.setActive('span', 0);

				// Activate all
				if (n) {
					do {
						cm.setDisabled(n.nodeName.toLowerCase(), 0);
						cm.setActive(n.nodeName.toLowerCase(), 1);
					} while (n = n.parentNode);
				}
			});

			ed.onPreInit.add(function() {
				// Fixed IE issue where it can't handle these elements correctly
				ed.dom.create('span');
			});
		},

		getInfo : function() {
			return {
				longname : 'Change Language Plugin',
				author : 'Sylvia Egger',
				authorurl : 'http://sprungmarker.de',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/changelang',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('changelang', tinymce.plugins.ChangeLangPlugin);
})();