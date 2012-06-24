/**
 *  universell console-log
 *
 *  @usage		log('inside coolFunc', this, arguments);
 *  @source		paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
 *  @since    0.1.2
 *
 */
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  arguments.callee = arguments.callee.caller;
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};
// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});

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
});


jQuery(document).ready(function($) {

		//	$('#cycle').cycle({
		//		fx: 'fade'
		//	});



		/**
		 *  404 Page
		 */
		// focus on search field after it has loaded
		$('.error404 .searchinput:first').focus();
		// insert "back" button"
		$('<button value="'+std_script.goback+'" onClick="history.go(-1);return true;">'+std_script.goback+'</button>').insertBefore($('.error404 a.gohome'));


		/**
		 *  Single with comment form
		 */
		 // move 'Subscribe-to-comments' Checkbox behind Email filed
		 $('.logged-in .subscribe-to-doi-comments').wrapInner('<div class="subscribe-to-doi-comments" />').children().unwrap().insertAfter($('.social-psst'));
		 $('body:not(.logged-in) .subscribe-to-doi-comments').wrapInner('<div class="subscribe-to-doi-comments" />').children().unwrap().insertAfter($('.social-input-row-email .social-help'));
		 $('label[for="subscribe"]').prepend( $('#subscribe') );
		 $('.social-twitter-icon .subscribe-to-doi-comments, .social-facebook-icon .subscribe-to-doi-comments,').remove();

		 // move 'Post to ...' also on top
		 $('#post_to').insertAfter($('.social-psst'));
		 // precheck this for sharing our webcontent
		 $('#post_to_service').attr('checked',true);
		 
		 $('#cancel-comment-reply-link').addClass('button');

		 // reload page after successful login trough
		 // facebook or twitter via 'Mailchimp-SOCIAL'-Plugin
/*
		 $('body').live('classNameChanged', function(){
					console.log($(this));
					if ( $(this).hasClass('logged-in') ) {
			        location.reload();
			    }
		 });
*/
});