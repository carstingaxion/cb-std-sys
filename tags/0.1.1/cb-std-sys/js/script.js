jQuery(document).ready(function($) {

  /**
   *  404 Page
   */
    // focus on search field after it has loaded
    $('body.error404 #s').focus();
    // insert "back" button"			
    $('<button value="'+std_script.goback+'" onClick="history.go(-1);return true;">'+std_script.goback+'</button>').insertBefore($('body.error404 a.gohome'));

        

//	$('#cycle').cycle({
//		fx: 'fade'
//	});

//	$('figure.gallery-item a').attr('rel','gallery');
//	$('figure.gallery-item a[rel="gallery"]').fancybox();

});