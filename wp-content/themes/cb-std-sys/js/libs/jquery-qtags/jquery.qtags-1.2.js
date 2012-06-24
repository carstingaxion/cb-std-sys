/*
 * jQuery bbcode editor plugin
 *
 * Copyright (C) 2010 Joe Dotoff
 * http://www.w3theme.com/jquery-bbedit/
 *
 * Version 1.4
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 *  almost completely rewritten by (c) Carsten Bach 2011
 *  http://carsten-bach.de
 *
 */

jQuery(document).ready(function ($) {
  $.qtag = {
    baseURL: null,
    i18n: {'default': {
				'strong': jq_qtags.strong,
				'em': jq_qtags.em,
				's': jq_qtags.s,
				'quote': jq_qtags.quote,
				'url': jq_qtags.url,
				'code': jq_qtags.code,
    }}
  };

  function insertTag(data, tag, tag2) {
    var val, startPos, endPos;
    var ta = data.ta;
    var range = data.range;
    var text = '';
    if (range !== null) {
      text = range.text;
    } else if (typeof ta.selectionStart != 'undefined') {
      startPos = ta.selectionStart;
      endPos = ta.selectionEnd;
      text = ta.value.substring(startPos, endPos);
    }
    if (typeof tag === 'function' || typeof tag === 'object') {
      val = tag(text);
      if (val === false) {
        if (range !== null) {
          range.moveStart('character', text.length);
          range.select();
        } else if (typeof ta.selectionStart !== 'undefined') {
          ta.selectionStart = startPos + text.length;
        }
        ta.focus();
        return;
      }
    } else {
      if (!tag2 || tag2 === '') {
        val = text + tag;
      } else {
        val = tag + text + tag2;
      }
    }
    if (range !== null) {
      range.text = val;
      if (data.highlight) {
        range.moveStart('character', -val.length);
        //range.moveEnd('character', 0);
      } else {
        range.moveStart('character', 0);
        //range.moveEnd('character', 0);
      }
      range.select();
    } else if (typeof ta.selectionStart != 'undefined') {
      ta.value = ta.value.substring(0, startPos) + val + ta.value.substr(endPos);
      if (data.highlight) {
        ta.selectionStart = startPos;
        ta.selectionEnd = startPos + val.length;
      } else {
        ta.selectionStart = startPos + val.length;
        ta.selectionEnd = startPos + val.length;
      }
    } else {
      ta.value += val;
    }
    ta.focus();
  }

  $.fn.extend({
    qtag: function (settings) {
      this.defaults = {
        highlight: true,
        enableToolbar: true,
        lang: 'default',
        tags: 'strong,em,s,url,code,quote'
      };
      var settings = $.extend(this.defaults, settings);
      var tags = settings.tags.split(/,\s*/);

      var toolHtml = '<div class="qtag-toolbar">';
      for (var i in tags) {
        toolHtml += '<button type="button" class="button-secondary qtag-' + tags[i] + '" title="' + $.qtag.i18n[settings.lang][tags[i]] + '">' + $.qtag.i18n[settings.lang][tags[i]] + '</button> ';
      }
      toolHtml += '</div>';

      return this.each(function () {

        var data = settings;
//        var container = $('<div class="qtags-container"></div>');
//        container.append($(this).clone());
//console.log($(this));
//console.log(container);
//        $(this).replaceWith(container);
//console.log($(this));
//console.log(container);
        data.range = null;

//        var ta = data.ta = container.children("textarea")[0];
        var ta = data.ta = $(this);

        //container.width($(ta).attr("offsetWidth"));
        $(ta).bind("select click keyup", function() {
          if (document.selection) {
            data.range = document.selection.createRange();
          }
        });
          var toolbar = $(toolHtml);
          $(ta).before(toolbar);

					toolbar.find(".qtag-strong").click(function () {
						// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
	       		data.ta = $(this).parent().next()[0];
						insertTag(data, '<strong>', '</strong>');
          });
          toolbar.find(".qtag-em").click(function () {
							// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
		       		data.ta = $(this).parent().next()[0];
							insertTag(data, '<em>', '</em>');
          });
          toolbar.find(".qtag-s").click(function () {
							// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
		       		data.ta = $(this).parent().next()[0];
	            insertTag(data, '<del>', '</del>');
          });
          toolbar.find(".qtag-code").click(function () {
							// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
		       		data.ta = $(this).parent().next()[0];
							insertTag(data, '<code>', '</code>');
          });
          toolbar.find(".qtag-quote").click(function () {
							// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
		       		data.ta = $(this).parent().next()[0];
	            insertTag(data, function (text) {
	                var cite = prompt(jq_qtags.quotecite+': ', '');
	                if (cite != null && cite != '') {
	                  if (text == '') {
	                    return '<blockquote cite="' + cite + '"></blockquote>';
	                  } else {
	                    return '<blockquote cite="' + cite + '">' + text + '</blockquote>';
	                  }
	                return false;
	              	}
	            });
          });
          toolbar.find(".qtag-url").click(function () {
							// make it work with multiple textareas, e.g. on the /wp-admin/widgets.php
		       		data.ta = $(this).parent().next()[0];
							insertTag(data, function (text) {
	                var url = prompt(jq_qtags.urlhref+': ', '');
	                if (url != null && url != '') {
	                  if (text == '') {
	                    return '<a href="' + url + '">' + url + '</a>';
	                  } else {
	                    return '<a href="' + url + '" title="' + text + '">' + text + '</a>';
	                  }
	                return false;
	              	}
	            });
          });
      });
    }
  });
});

jQuery(document).ready(function ($) {
		$('.allowed-comment-tags').hide();
	  $('#comment, body.wp-admin textarea[name^="widget-text"]').qtag();
});