<?php
/**
 * default search form
 */
?>
<form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-wrap">
    	<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'retail-motion' ); ?></label>
        <input class="search-input" type="search" placeholder="<?php echo esc_attr( 'Search…', 'retail-motion' ); ?>" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
        <div class="submit-wrap">
        	 <input class="search-submit" type="submit" id="search-submit" value="Search" />
        </div>
    </div>
</form>
