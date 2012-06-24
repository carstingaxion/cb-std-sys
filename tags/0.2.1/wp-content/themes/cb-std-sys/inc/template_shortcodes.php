<?php
// http://donalmacarthur.com/articles/cleaning-up-wordpress-shortcode-formatting/
function atlas_clean_shortcode_content( $content ) {

    /* Parse nested shortcodes and add formatting. */
    $content = trim( wpautop( do_shortcode( $content ) ) );

    /* Remove '</p>' from the start of the string. */
    if ( substr( $content, 0, 4 ) == '</p>' )
        $content = substr( $content, 4 );

    /* Remove '<p>' from the end of the string. */
    if ( substr( $content, -3, 3 ) == '<p>' )
        $content = substr( $content, 0, -3 );

    /* Remove any instances of '<p></p>'. */
    $content = str_replace( array( '<p></p>' ), '', $content );

    return $content;
}

function half_left($atts, $content = null) {
    $content = atlas_clean_shortcode_content( $content );
    return '<div class="half-left">' . $content . '</div>';
}
function half_right($atts, $content = null) {
    $content = atlas_clean_shortcode_content( $content );
    return '<div class="half-right">' . $content . '</div>';
}
add_shortcode('half_left', 'half_left');
add_shortcode('half_right', 'half_right');
?>