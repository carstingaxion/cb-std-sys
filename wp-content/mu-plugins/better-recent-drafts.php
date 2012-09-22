<?php
/* 
Plugin Name: Better Recent Drafts Dashboard Widegt ( UI improved )
Plugin URI: http://www.jonlynch.co.uk/wordpress-plugins/better-recent-drafts/ 
Description: Displays an improved recent drafts widget on the dashboard
Author: Carsten Bach
Version: 0.2
Author URI:

original snippet taken from Marco at 
http://wordpress.org/extend/ideas/topic/recent-drafts-on-dashboard-see-drafts-and-pendings-of-all-users

based on the PLugin from Jon Lynch
http://www.jonlynch.co.uk/wordpress-plugins/better-recent-drafts/

*/

add_action ('wp_dashboard_setup', 'jl_brd_add_recent_drafts' );
function jl_brd_add_recent_drafts () {

  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // recent drafts
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');  // recent drafts

  wp_add_dashboard_widget( 'cb_dashboard_recent_drafts', __('Recent Drafts'), 'jl_brd_dashboard_recent_drafts' );

};

function jl_brd_dashboard_recent_drafts( $drafts = false ) {
	if ( ! $drafts ) {
		$drafts_query = new WP_Query( array(
			'post_type' => 'any',
			'post_status' => array('draft', 'pending'),
			'posts_per_page' => -1,
			'orderby' => 'modified',
			'order' => 'DESC'
		) );
		$drafts =& $drafts_query->posts;
	}

	if ( $drafts && is_array( $drafts ) ) {
		$list = array();
		foreach ( $drafts as $draft ) {
			$url = get_edit_post_link( $draft->ID );
			$title = _draft_or_post_title( $draft->ID );
			$last_id = get_post_meta( $draft->ID, '_edit_last', true);
			$last_user = get_userdata($last_id);
			$item  = '<h4><a href="' . $url . '" title="' . __( 'Edit' ) . '">' . esc_html($title) . '</a>';
			switch ( $draft->post_status ) {
				case 'pending':
					$post_status = __('Pending Review');
					break;
				case 'draft':
				case 'auto-draft':
					$post_status = __('Draft');
					break;
			}
			$item .= ' - <span class="post-state"><span class="' . strtolower( str_replace( ' ', '-', $draft->post_status ) ) . '">' . $post_status . '</span></span>';
			$obj = get_post_type_object( $draft->post_type );
			$item .= '   <small>' . $obj->labels->singular_name . '</small>';
			$item .= '<abbr class="howto alignright" title="' . sprintf(__('Last edited by %1$s on %2$s at %3$s'), esc_html( $last_user->display_name ), mysql2date(get_option('date_format'), $draft->post_modified), mysql2date(get_option('time_format'), $draft->post_modified)) . '">' . mysql2date(get_option('date_format'), $draft->post_modified) . ', ' . mysql2date(get_option('time_format'), $draft->post_modified) . '</abbr></h4>';
			if ( $the_content = preg_split( '#\s#', strip_shortcodes(strip_tags( $draft->post_content ), 11, PREG_SPLIT_NO_EMPTY )) )
				$item .= '<p style="padding-left:10px;">' . join( ' ', array_slice( $the_content, 0, 10 ) ) . ( 10 < count( $the_content ) ? '&hellip;' : '' ) . '</p>';
			// http://old.nabble.com/Passing-arguments-to-current_user_can()-function-td31716337.html
			if ( current_user_can( $obj->cap->edit_post, $draft->ID ) )
				$list[] = $item;

		}
		if ( !empty( $list ) ) {
		?>	<ul>
				<li><?php echo join( "</li>\n<li>", $list ); ?></li>
			</ul>
		<?php
		} else {
				_e('There are no drafts at the moment');
		}
	} else {
		_e('There are no drafts at the moment');
	}
}