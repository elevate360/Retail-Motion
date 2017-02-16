<?php
/*
 * Sidebar
 * displays the standard page sidebar
 */

if(is_active_sidebar( 'widget-page-sidebar' )){
	echo '<aside id="secondary" class="el-col-small-12 el-col-medium-3 widget-area small-margin-top-small medium-margin-top-medium" role="complementary">';
		dynamic_sidebar( 'widget-page-sidebar' );
	echo '</aside><!-- #secondary -->';
}
?>
