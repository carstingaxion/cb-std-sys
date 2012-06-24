<?php
if ( cbstdsys_opts('m_search') ) {
		if (!isset($GLOBALS['search_form_counter']))
		$GLOBALS['search_form_counter'] = 1;
		$id = '-'.$GLOBALS['search_form_counter'];
?>
<form role="search" method="get" id="searchform<?php echo $id ?>" class="searchform" action="<?php echo home_url( '/' ); ?>">
        <label for="s<?php echo $id ?>" class="searchlabel visuallyhidden"><?php _e('Search for:'); ?></label>
        <input type="search" name="s" id="s<?php echo $id ?>" class="searchinput" value="<?php the_search_query(); ?>" required placeholder="<?php _e('Your search-phrase','cb-std-sys'); ?>">
	      <input type="submit" value="<?php _e('Search'); ?>" id="searchsubmit<?php echo $id ?>" class=" button searchsubmit">
</form>
<?
		$GLOBALS['search_form_counter']++;
}
?>