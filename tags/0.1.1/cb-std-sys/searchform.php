<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
        <label for="s"><?php _e('Search for:'); ?></label>
        <input type="search" name="s" id="s" value="<?php the_search_query(); ?>" results=5 required placeholder="<?php _e('Your search-phrase','cb-std-sys'); ?>">
	      <input type="submit" value="<?php _e('Search'); ?>" id="searchsubmit">
</form>