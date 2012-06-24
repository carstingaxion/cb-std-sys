=== MCE Accessible Language Change ===
Contributors: Sylvia Egger (sprungmarker.de)
Donate link: http://sprungmarker.de
Tags: editor, WYSIWYG, buttons, language change, accessibility, tinymce, rich text editor, accessible, language
Requires at least: 3.0
Tested up to: 3.3
Stable tag: trunk
License: GNU General Public License v2.0

Adds language change buttons in the WordPress WYSIWYG editor.

== Description ==

Adds language change buttons in the WordPress WYSIWYG editor.

A very light weight plug-in with two button for adding language change. The buttons will
be added in the first row of the editor.

First you can add language changeon the basis of an span element. Just highlight the word
or phrase you want to add language change, klick on the "lang" button an add the appropriate
language (e.g. "fr" for a french word or phrase).

Second you can add language change to existing html elements as paragraphs, headlines and links.
Just klick in the context of a block element, klick on the button "lang attr" and add the appropriate language
(e.g. "fr" for a french word or phrase).

The plugin ist localized for englisch and german; please let me know if you would like additional
localizations added.

Hoping to add more features in a future update.

== Installation ==

1. Upload `mce-accessible-language-change` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. To show your language change in a visual way in the editor - not on your website:

   You have to do nothing for it - the file in css > changelang.css will do everything for you.
   
   If you can not see any visual change, you probably have a file named editor-styles.css in your WordPress theme.
   If you have already an file named editor-style.css in your theme, you have to copy the styles below into your editor-styles.css to get your language changes visible in the editor.
   
CSS styles to add:

`span[lang], .lang {
    background: #f8f8f8;
    border: 1px solid #d2d0ce;
    padding: 2px;
}`

== Frequently Asked Questions ==

= No questions right now =

== Screenshots ==

1. The editor with the new button for language change "lang" and "lang attr" and showing the language change in the text highlighted.
2. The editor popup showing language change options.
3. The editor popup showing language attributes options.

== Changelog ==

=1.1=

* Path corrections for TinyMCE.

=1.0=

* First release
* Minor changes renaming files according to the name of zip file

== Upgrade Notice ==

= 1.0 =
No upgrades right now.
Just some minor changes in readme.txt.

= 1.1 =
Path corrections for TinyMCE.
Just some minor changes in readme.txt.