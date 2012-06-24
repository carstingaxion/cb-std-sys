<?php

		/**
		 *		Return if the current user has written posts or not
		 *
		 *		@since		0.2.0
		 *		@source		http://wordpress.stackexchange.com/a/5771
		 *
		 *    @param    int   User ID
		 *    @return   bool
		 */
		function current_user_has_posts($user_id) {

				$result = new WP_Query(array(
					  'author'=>$user_id,
					  'post_type'=>'any',
					  'post_status'=>'publish',
					  'posts_per_page'=>1,
				));
				return (count($result->posts)!=0);
		}



		/**
		 *
		 *  validate html5 with integrated facebook-Opengraph Markup
		 *
		 *  @since  0.1.1
		 *  @source http://earthpeople.se/labs/2010/09/html5-validation-with-facebook-opengraph/
		 *
		 */
		function is_facebook(){
				if( !( stristr( $_SERVER["HTTP_USER_AGENT"],'facebook' ) === FALSE ) )
				return true;
		}



?>