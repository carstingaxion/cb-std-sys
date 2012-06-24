$(function(){ 

    /**
     *  hook jQuery default functions to trigger 'classNameChanged' event
     *  
     *  http://stackoverflow.com/questions/3139608/class-name-change-event-in-jquery
     *  
     */    
    var _addClass    = $.fn.addClass,
        _removeClass = $.fn.removeClass;
    $.fn.addClass    = function(){
        return _addClass.apply(this, arguments).trigger('classNameChanged');
    }
    $.fn.removeClass = function(){
        return _removeClass.apply(this, arguments).trigger('classNameChanged');
    }



    /**
     *  de translateion for UI datepicker
     *  
     *  http://www.blogrammierer.de/jquery-ui-datepicker-in-deutscher-sprache/
     *  
     */                        
    $.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
            closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
            prevText: '&#x3c;zurück', prevStatus: 'letzten Monat zeigen',
            nextText: 'Vor&#x3e;', nextStatus: 'nächsten Monat zeigen',
            currentText: 'heute', currentStatus: '',
            monthNames: ['Januar','Februar','März','April','Mai','Juni',
            'Juli','August','September','Oktober','November','Dezember'],
            monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
            'Jul','Aug','Sep','Okt','Nov','Dez'],
            monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
            weekHeader: 'Wo', weekStatus: 'Woche des Monats',
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
            dateFormat: 'dd.mm.yy', firstDay: 1, 
            initStatus: 'Wähle ein Datum', isRTL: false,
            showWeek: true};
    $.datepicker.setDefaults($.datepicker.regional['de']); 



    /**
     *  set validation options
     *  (this creates range messages from max/min values)
     *  
     *  http://docs.jquery.com/Plugins/Validation
     *  http://bassistance.de/jquery-plugins/jquery-plugin-validation/
     *            
     */                 
    $.validator.autoCreateRanges = true;
    $.validator.setDefaults({
        highlight: function(input) {
            $(input).addClass("ui-state-highlight error");
        },
        unhighlight: function(input) {
            $(input).removeClass("ui-state-highlight error");
        },
        errorClass: 'error_msg',
        wrapper : 'dd',
        errorPlacement : function(error, element) {
            error.addClass('ui-state-error');
            error.prepend('<span class="ui-icon ui-icon-alert"></span>');
            error.appendTo(element.closest('dl.ui-helper-clearfix').effect('highlight', {}, 2000));
        }
    });
    
    /**
     *  define what to style with jQuery Uniform Plugin
     *  
     *  http://uniformjs.com/
     *  
     */                          
    $('#Briefing_Fragebogen').reformed({
                styleFileInputs : false, //use the uniform plugin to style file input boxes
                styleRadios : true, //style radios with uniform plugin
                styleCheckboxes : true, //style checkboxes with uniform plugin
                styleSelects : false, //style selects with uniform plugin
                styleButtonsWithUniform : false, //style all form buttons with uniform (false = styled by jquery UI)
                styleDatepicker : true //use jqueryUI datepicker
    }).validate();




    
    
    
    /**
    * $.unserialize
    *
    * Takes a string in format "param1=value1&param2=value2" and returns an object { param1: 'value1', param2: 'value2' }. If the "param1" ends with "[]" the param is treated as an array.
    *
    * Example:
    *
    * Input: param1=value1&param2=value2
    * Return: { param1 : value1, param2: value2 }
    *
    * Input: param1[]=value1&param1[]=value2
    * Return: { param1: [ value1, value2 ] }
    *
    * @source   https://gist.github.com/242617#file_jquery.unserialize.js
    */

    $.fn.unserialize = function(serializedString) {
        var str = decodeURI(serializedString);
        var pairs = str.split('&');
        var obj = {},p,idx,val;
        for (var i = 0, n = pairs.length; i < n; i++) {
            p = pairs[i].split('=');
            idx = p[0];
            if (idx.indexOf("[]") == (idx.length - 2)) {
                var ind = idx.substring(0, idx.length - 2); 
                if (obj[ind] === undefined) {
                    obj[ind] = [];
                }
                obj[ind].push( p[1] );
            } else {
                obj[idx] =  p[1];
            }
        }
        return obj;
    }


    /**
     *  add leading zeros
     *  
     *  @source   http://www.electrictoolbox.com/pad-number-zeroes-javascript-improved/
     *  
     */    
     $.fn.pad = function(n, len) {
        s = n.toString();
        if (s.length < len) {
            s = ('0' + s).slice(-len);
        }
        return s;
    }
    
    
});




jQuery(document).ready(function($) {


/**
 *  textarea auto-resize
 *  
 *  http://james.padolsey.com/javascript/jquery-plugin-autoresize/
 *
 */
$('textarea').autoResize({extraSpace : 25, limit : 10000  });     




/**
 *  set focus on current fieldset and remove it, when "go on"
 *  
 */ 
$.fn.switchFieldsetFocus = function(e) {
   // remove class="focus" from last used fieldsets 
   $(e.target).parents('.nav-wrap').siblings().removeClass('focus');
   // remove class="ui-state-active" from last used #toc a (containing to a fieldset)   
   $('#toc a[href="#'+ $(e.target).parents('.nav-wrap').attr('id') + '"]').siblings().removeClass('ui-state-active');
   // add class="focus" to current used fieldset
   $(e.target).parents('.nav-wrap').addClass('focus');
   // add class="ui-state-active" to current #toc a (containing to a fieldset)   
   $('#toc a[href="#'+ $(e.target).parents('.nav-wrap').attr('id') + '"]').addClass('ui-state-active'); 
}
  
$('#Briefing_Fragebogen input, #Briefing_Fragebogen textarea, #Briefing_Fragebogen select, #Briefing_Fragebogen .nav-wrap').live("focus", function(e) {
    $.fn.switchFieldsetFocus(e);  
});

$('#Briefing_Fragebogen dd.radio, #Briefing_Fragebogen dd.checker, #Briefing_Fragebogen fieldset').click( function(e) {
    $.fn.switchFieldsetFocus(e);  
});


$('#toc a').live( "click", function() {  
   // remove class="focus" from last used fieldsets 
   $( $(this).attr('href') ).siblings().removeClass('focus');
   // add class="focus" to current used fieldset
   $( $(this).attr('href') ).addClass('focus'); 
}); 






/**
 *  build progress bar
 *  
 *  http://jqueryui.com/demos/progressbar/
 *
 */
$.fn.getProgressBarVal = function() {
    // count all required fields
    var allNum  = new Number( 
                              $('#Briefing_Fragebogen dd:not(".cloned, .radio") input.required[type="text"]').length 
                            + $('#Briefing_Fragebogen dd.radio.required').length   
                            + $('#Briefing_Fragebogen dd.checker.required').length   
                            + $('#Briefing_Fragebogen textarea.required:not("[tabindex="-1"]")').length 
                            );
    // count all validated and filled fields                        
    var newNum  = new Number( 
                              $('#Briefing_Fragebogen dd:not(".cloned, .radio") input.required[type="text"][value!=""]:not(".error")').length 
                            + $('#Briefing_Fragebogen dd.radio.required .checked').length     
                            + $('#Briefing_Fragebogen dd.checker.required').contents().find('.checked:first').length   
                            + $('#Briefing_Fragebogen textarea.required[value!=""]:not(".error, [tabindex="-1"]")').length 
                            );
    var pr = (newNum/allNum)*100;    
    //console.log(newNum + " von " + allNum + " sind " + Math.round(pr) + " Prozent");
    //console.log( typeof pr);
   if ( pr == 0 ) { $( "#progressbar" ).fadeOut('100'); } else{ $( "#progressbar" ).fadeIn('100'); }
   $( "#progressbar" ).progressbar( "option", "value", pr );
   $('.ui-progressbar-value').text( Math.round(pr) + '%');
}

$('<div id="progressbar-holder"><div id="progressbar"></div></div>').insertBefore('#Briefing_Fragebogen');
$( "#progressbar" ).progressbar({	 value: 0		});

$('#Briefing_Fragebogen input, #Briefing_Fragebogen textarea, #Briefing_Fragebogen select').live("focusout", function() { $.fn.getProgressBarVal(); });  
$('#Briefing_Fragebogen .radio input, #Briefing_Fragebogen .checker input').live("click", function() { $.fn.getProgressBarVal(); });


/**
 *  rewrite <title> from 'Arbeitstitel'
 *  
 */
var original_site_title =  $('title').text();
$('#Arbeitstitel').live("keyup", function() { $('title').text(  $(this).val() + ' | ' + original_site_title ); });




/**
 *  setup buttons for additional inputs
 *  
 *  'add' Button
 *  'delete' Button
 *  rebind jQuery UIs 'datepicker'
 *       
 */
// setup new 'add element' button on all   relevant input-sets 
var addButton =  $('<button class="btnAdd">Feld hinzufügen</button>');
// define them as jQueryUI Buttons
$(addButton).button({
            icons: {  primary: "ui-icon-circle-plus" },
            text: false });	
$('button.formAdd').button({
            icons: {  primary: "ui-icon-circle-plus" } });	            
// attach them to all 'dl's
$('dl.additional-inputs dd:not(".cloned")').append( addButton );
// define new added 'delete input' fields as jQueryUI Buttons
$("button.btnDel").live('dom_element_added', function() { $(this).button({
            icons: {  primary: "ui-icon-circle-minus" },
            text: false }); });
$("button.formDel").live('dom_element_added', function() { $(this).button({
            icons: {  primary: "ui-icon-circle-minus" } }); });            
// bind 'datepicker' to new inputs.datepicker         
$('input.datepicker').live('dom_element_added', function() {
	 $(this).removeClass('hasDatepicker') .datepicker({showOn: 'focus'});
});    

             
$.fn.delEle = function(e) {
        e.preventDefault();
        var base    = $(e.target).parents('dl');             
    //console.log(base);
        base.children('dd.cloned:last').remove();
}

$.fn.addEle = function(e) {
        e.preventDefault();             
        var base    = $(e.target).parents('dl');
        var num     = base.find('dd').length;
        var newNum  = $.fn.pad( new Number( num + 1 ), 2 );

        var newElem = base.find('dd:first').clone().addClass('cloned');
        newElem.prev('.clone').removeClass('last'); 
        newElem.children('button.btnAdd').remove();
        // iterate over each input field
        newElem.find('input').each(function(index) {
                var eleId   = $(this).attr('id');
            //console.log(num + ' '+newNum + ' '+eleId); 
            //console.log(index + ': ' + eleId );
                $(this).attr('id', eleId + '_' + newNum).attr('name', eleId + '_' + newNum).val('').removeClass('ui-state-highlight');                
        });
    //console.log(base);
        base.find('dd:last').after(newElem);
        if ( base.is('.w_50') )
             base.find('dd:odd').addClass('last');  

        newElem.append('<button class="btnDel">Feld löschen</button>');      
        newElem.children('button').trigger('dom_element_added');
        newElem.children('input.datepicker').trigger('dom_element_added');  
}	
// add element on button click
$('.btnAdd').click(function(e) {  $.fn.addEle(e);    });
// remove last element of set
$('.btnDel').live("click", function(e) {   $.fn.delEle(e);    });

$.fn.delForm = function(e) {
        e.preventDefault();
        var base    = $(e.target).parents('fieldset');             
        base.remove();
        
        $.fn.buildToc();     
        $.fn.resizeToc();   
}

$.fn.addForm = function(e) {
        e.preventDefault();             
        var base    = $(e.target).parents('div.nav-wrap');

        var num     = base.find('fieldset').length;
        var newNum  = $.fn.pad( new Number( num + 1 ), 2 );

        var newElem = base.find('fieldset:first').clone().addClass('cloned');
        // iterate over each input field
        newElem.find('input').each(function(index) {
            var eleId   = $(this).attr('id');
            //console.log(num + ' '+newNum + ' '+eleId); 
            //console.log($(this) + ' ' + index + ': ' + eleId );
            $(this).attr('id', eleId + '_' + newNum).attr('name', eleId + '_' + newNum).removeClass('ui-state-highlight');                
            //console.log( newElem.children("input").eq(index) );            
            
            if ( $(this).is('div:not(".formsetup") input[type="text"]') ) {
                $(this).val('').prop('disabled', true);
            } else {
                $(this).val( eleId + '_' + newNum );
                $(this).parent().removeClass('checked');
            }
        });
        // iterate over each label field
        newElem.find('label').each(function(index) {
            var eleFor   = $(this).attr('for');
            $(this).attr('for', eleFor + '_' + newNum);                
        });     
        // iterate over each label field
        newElem.find('div.checker').each(function(index) {
            var eleId   = $(this).attr('id');
            $(this).attr('id', eleId + '_' + newNum);                
        });             
        //newElem.find('label[for="f_add_form"]').text(' ');
        newElem.find('.formAdd').text('Formular löschen').removeClass('formAdd').addClass('formDel');
        
        base.after(newElem);
        newElem.find('input:first').val('').focus();
        newElem.find('.formDel').trigger('dom_element_added').removeClass('ui-state-hover ui-state-focus');
        
        $.fn.buildToc();     
        $.fn.resizeToc();         
}	
// add element on button click
$('.formAdd').click(function(e) {  $.fn.addForm(e);    });
// remove last element of set
$('.formDel').live("click", function(e) {   $.fn.delForm(e);    });


/**
 *  dynamically show or change form elements
 *  
 */
// private or password-protected areas
$('#uniform-public_areas_pw input, #uniform-public_areas_pr input').live("click", function() {  $('#uniform-public_areas_no span').removeClass('checked'); $(this).parent().addClass('checked');  });
$('#uniform-public_areas_no input').live("click", function() {   $('#uniform-public_areas_pw span, #uniform-public_areas_pr span').removeClass('checked'); $(this).parent().addClass('checked');  });
 
 
// Add Languages 
$('#add_langs').hide();
$('#uniform-langs_yes span').live("change, classNameChanged", function() {  $('#add_langs').show(); });   
$('#uniform-langs_no span').live("change, classNameChanged", function() {  $('#add_langs').hide(); });

// Galleries
$('#uniform-gallery_global input, #uniform-gallery_single input').live("click", function() {  $('#uniform-gallery_no span').removeClass('checked'); $(this).parent().addClass('checked');  });
$('#uniform-gallery_no input').live("click", function() {   $('#uniform-gallery_global span, #uniform-gallery_single span').removeClass('checked'); $(this).parent().addClass('checked');  });

// Medias
$('#uniform-media_audio input, #uniform-media_video input').live("click", function() { $('#uniform-media_no span').removeClass('checked'); $(this).parent().addClass('checked');  });
$('#uniform-media_no input').live("click", function() {  $('#uniform-media_audio span, #uniform-media_video span').removeClass('checked'); $(this).parent().addClass('checked');  });

// Newsletter
$('#uniform-newsletter_design input, #uniform-newsletter_plain input').live("click", function() { $('#uniform-newsletter_no span').removeClass('checked'); $(this).parent().addClass('checked');  });
$('#uniform-newsletter_no input').live("click", function() {  $('#uniform-newsletter_design span, #uniform-newsletter_plain span').removeClass('checked'); $(this).parent().addClass('checked');  });

// Forms
$('.forms input.activate-field[type="checkbox"]').click( function(){ 
    if ( $(this).parents('div.checker').next().is(':disabled') ) {
        $(this).parents('div.checker').next().removeAttr('disabled').css('width','67%').focus();
        $(this).parents('div.checker').siblings('.duty').show();
    } else {
        $(this).parents('div.checker').next().prop('disabled', true ).css('width','89%');
        $(this).parents('div.checker').siblings('.duty').hide();
    }   
});

// CorporateIdentity
$('#d_fonts_fields, #d_colorscheme_fields').hide();
$('#uniform-ci_yes span').live("change, classNameChanged", function() {  $('#d_fonts_fields, #d_colorscheme_fields').hide(); });   
$('#uniform-ci_no span').live("change, classNameChanged", function() {  $('#d_fonts_fields, #d_colorscheme_fields').show(); });

// inidividuell Design-Parts
$('#uniform-d_individ_bg_img input, #uniform-d_individ_bg_clr input, #uniform-d_individ_header input').live("click", function() { $('#uniform-d_individ_no span').removeClass('checked'); $(this).parent().addClass('checked');  });
$('#uniform-d_individ_no input').live("click", function() {  $('#uniform-d_individ_bg_img span, #uniform-d_individ_bg_clr span, #uniform-d_individ_header span').removeClass('checked'); $(this).parent().addClass('checked');  });

// BackUp frequency
$('#bu_f_fields').hide();
$('#bu_fields span').live("change, classNameChanged", function() {  
  if ( $('#bu_core, #bu_files, #bu_db').parent('span').hasClass('checked') ) {
      $('#bu_f_fields').show();  
  } else {
      $('#bu_f_fields').hide();  
  }
});   

// add checked class to selected radio and checker LIs
$('.radio span, .checker span').live('classNameChanged', function(){
    if ( $(this).hasClass('checked') ) {
        $(this).parents('li').addClass('selected');
    } else {
        $(this).parents('li').removeClass('selected');   
    }
    //console.log( $(this).parents('li') );
}); 



/**
 *  build table of contents
 *  
 *  @source   http://www.jankoatwarpspeed.com/post/2009/08/20/Table-of-contents-using-jQuery.aspx
 *  
 */                 
$('<div id="toc" />').insertBefore('#Briefing_Fragebogen');

$.fn.buildToc = function() {
    $("#toc").empty();
    $("#toc a").live('dom_element_added', function() { $(this).button(); });
    $("legend").each(function(i) {
        var current = $(this);
        current.parent().wrap('<div class="nav-wrap" id="schritt-' + (i+1) + '"/>');
        current.not('[title]').attr('title', current.text() )
        current.text( (i+1) + '. ' + current.attr('title') );
        //  " + bf.slug + "
        $("#toc").append("<a id='toc-link" + i + "' href='#schritt-" + (i+1) + "' title='Springe zu " + current.attr('title') + "'>" + current.text() + "</a>");
        $("#toc-link" + i).trigger('dom_element_added');
    });
}
$.fn.resizeToc = function() {
    $("legend").each(function(i) {
        var current = $(this);
        // 4em #progressbar-holder == 52px
        var pos = current.offset().top / $("#Briefing_Fragebogen").height() * ( $('#toc').height() - 52 );
        $("#toc-link" + i).css("top", pos);
    });
}
$.fn.buildToc();     
$.fn.resizeToc();  
$(window).resize( function() { $.fn.resizeToc();   });

 


/**
 *  Add collaboration control
 *  
 */
$('<div id="collab-control" />').insertBefore('#progressbar');
$('#collab-control').append('<button id="cc-refresh" title="Aktualisieren" />');   
$('#cc-refresh').button({
            icons: {  primary: "ui-icon-refresh" },
            text: false });
var info_msg  = '<div  id="cc-status" class="ui-widget ui-state-highlight ui-corner-all"><span class="ui-icon ui-icon-info"></span><strong class="ui-button-text">Aktualisiert!</strong></div>';
$('#collab-control').append( info_msg );
 
$('body.superadmin-logged-in button#cc-refresh').click( function() {
    $.fn.saveBriefingData();
});

$('body:not(".superadmin-logged-in") button#cc-refresh').click( function() {
    $.fn.updateBriefingData();
});

$.fn.saveBriefingData = function () {
    var form_data = $("#Briefing_Fragebogen :input[value]").serialize();
    //console.log(form_data);
    $.post(
       bf.ajaxurl, 
       {
          'action':'save_briefing_form',
          'post_id':  bf.post_ID,
          'data':   form_data
       }, 
       function(response){
          $('#cc-status strong').text(response);
          $('#cc-status').show().delay('1500').fadeOut('3000');
       }
    );
    window.setTimeout($.fn.saveBriefingData, 20000);    
}







$.fn.updateBriefingData = function () {  
    $.post(
       bf.ajaxurl, 
       {
          'action':'update_briefing_form',
          'post_id':  bf.post_ID
       }, 
       function(response){
            //console.log('The server responded: '+  typeof response + ' ' + response + ' ' + decodeURI(response) );
            if ( typeof response != 'undefined' && response != '' && response != '-1' ) {
                data  = $.fn.unserialize ( response );
                //console.log(data);
    
                $.each( data , function( k, v ) {
                    if ( typeof v == 'object' ) {
                        $.each( v , function( i, val ) {
                            //console.log(k + ' ' + i + ' + '+ val);                    
                            $('#' + val).prop('checked', true);
                            $('#' + val).parent().addClass('checked');
                         });
                    } else {
                        if ( $('[name=' + k + ']').length == 0 ){
                            var el_to_clone = k.substring(0, k.length-3);
                            $( '#' + el_to_clone ).next().click();
                        }
                        $('[name=' + k + ']').val( decodeURIComponent( v.replace(/\+/g, '%20') ) ).trigger('change');
                        
                    }
                });
                $('#cc-status strong').text('Aktualisiert!');
                $('#cc-status').show().delay('1500').fadeOut('3000');            
                // reBuild Progressbar
            }
            $.fn.getProgressBarVal();
        }
    );
    if ( $('body:not(".superadmin-logged-in")').length )
    window.setTimeout($.fn.updateBriefingData, 20000);    
}







/**
 *  load freemind flashbrowser
 *  
 *  http://freemind.cvs.sourceforge.net/viewvc/freemind/flash/source/readme.txt?revision=1.5&view=markup
 *  http://freemind.sourceforge.net/wiki/index.php/Flash_browser  
 *  
 *  http://blog.powerflasher.de/swfobject2/  
 *
 */
$.fn.loadFreemind = function() { 

    $( '<div id="mm" />' ).insertBefore( $('#freemind-container img') );
    var height  = new Number ($('#freemind-container img').height() ) + 100;
    $('#freemind-container').delay('10000').height(height);
    
		var flasher = $('#mm');
		flasher.flash(
			{
				swf: bf.path + 'freemind/visorFreemind.swf',
				width:'100%',
				height: height + 'px',
				flashvars: {
  					openUrl:                 '_blank',
  					startCollapsedToLevel:   3,
  					maxNodeWidth:            200,
  					offsetX:                 -50,
  					offsetY:                 -5,
//            buttonsPos:
//            min_alpha_buttons
//            max_alpha_buttons
  					mainNodeShape:           'elipse',
  					justMap:                 true,
  					maxNodeWidth:            200,
  					initLoadFile:            $('#freemind a.mm-file').attr('href'),
  					defaultToolTipWordWrap:  200,
  					scaleTooltips:           false,
            CSSFile:                 bf.path + 'freemind/flashfreemind.css'					
				}
			}
		);
}
$.fn.loadFreemind();





/**
 *
 *  remove #loader
 *  and focus first fieldset 
 *  
 */

$('.nav-wrap:first').addClass('focus');    
$('#toc a:first').addClass('ui-state-active');
$.fn.updateBriefingData();
$('#loader').delay('2000').fadeOut('slow');
//$('#loader').hide();    
});