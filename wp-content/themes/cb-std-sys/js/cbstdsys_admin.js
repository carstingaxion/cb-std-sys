jQuery.fn.countExcerptWords = function( $excerpt, $counter ){

    var $number = 0;
    var $matches = $excerpt.val().match(/\b/g);
    if( $matches ) {
        $number = $matches.length/2;
    }
    $counter.css({color:( $number > cbstdsys_admin_js_vars.excerpt_length ? '#B00' : 'inherit')});
    $counter.html( cbstdsys_admin_js_vars.i18n_wordcount + ' ' + $number + '/' + cbstdsys_admin_js_vars.excerpt_length);    
    
};

jQuery(document).ready(function($) {

/******************************************************************************
 * Edit Posts & Pages etc..
/******************************************************************************/

		// move excerpt above tinymce and disable moving-cursor
		$("#postexcerpt").insertAfter("#titlediv");
		$("#postexcerpt h3").css('cursor','pointer');

		// remove excerpt-description with Link to WP-Codex
		$("#postexcerpt p").remove();
		
    // Add Excerpt Counter
    var $excerpt = $('#excerpt');
		// Only activate if there's an excerpt field:
		if ( $excerpt.length ) {
				
        // Create counter 
				var $counter = $('<small class="alignright subtitle"/>').insertAfter( $('#postexcerpt h3 span') );
				
				// Bind keystrokes:
				$excerpt.on('keydown keyup keypress focus blur paste', function() { $.fn.countExcerptWords( $excerpt, $counter ) } );
				
				// Do it the first time:
				$.fn.countExcerptWords( $excerpt, $counter );
		}

		// remove 'title-form' checkbox and label from Screensettings-Tab
		$("#slugdiv-hide").parent().remove();


/******************************************************************************
 * Gallery Settings
/******************************************************************************/

		// link gallery elements to images, not to attachement-pages
		$("#gallery-settings tbody input#linkto-file").attr('checked','checked');
		
		
/******************************************************************************
 * User Profile Page
/******************************************************************************/
		// hide whole form until everything is fine
		$('form#your-profile').hide();

		// remove 'personal Options'  from user Profile pages, ugly but working
		$('body:not(.super-admin-logged-in) form#your-profile > h3:first, body:not(.super-admin-logged-in) form#your-profile > table:first').remove();

		// move "UserPhoto" into "About me"-section
		var aboutMeSection  = $('#password').parent().parent();
		$('#userphoto').insertBefore( aboutMeSection );

		// move modified "Bio" into "About me"-section
		var bioDescription  = $('label[for="description"]').parent().parent().parent().parent();
		$( bioDescription ).insertAfter( '#userphoto + table' );

		// show whole form now, because everything is fine
		$('form#your-profile').show();

});