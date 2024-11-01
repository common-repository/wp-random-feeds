<?php
/*
Plugin Name: WP Random Feeds
Plugin URI: http://www.myfastblog.com/
Description: Send your post and category feeds random. Useful for be deployed on another website/blog.
Author: Haotik
Version: 0.1
Author URI: http://www.haotik.ro
*/

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
#---------------------------------------------------------------------

//	error_reporting(E_ALL);


function wprf_admin() {
	
	if(isset($_POST['wprf_hidden']) && ($_POST['wprf_hidden'] == 'Y')) {
		//Form data sent
		$wprf_randomize = isset($_POST['wprf_randomize']);
		update_option('wprf_randomize', $wprf_randomize);

		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$wprf_randomize = get_option('wprf_randomize');
	}


		echo '<div class="wrap">';
		echo "<h2>" . __( 'Random Feeds Options', 'wprf_trdom' ) . "</h2>"; 
		echo '<form name="wprf_form" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
		echo '		<input type="hidden" name="wprf_hidden" value="Y">'; 	
				
		echo "<h4>" . __( 'Random Feeds Enable/Disable', 'wprf_trdom' ) . "</h4>"; 
		
		echo '<input type="checkbox" name="wprf_randomize" value="1" '.($wprf_randomize==1? 'checked=yes': '').'> Randomize my feeds. <br \> <br \>';

		echo '<input type="submit" name="Submit" value="Update Options" />';
		echo '	</p></form></div>';

}


function wprf_admin_actions() {  
	add_options_page("Random Feeds", "Random Feeds", 1, "Random Feeds", "wprf_admin");
}  
add_action('admin_menu', 'wprf_admin_actions'); 



function wprf_random_feeds($query) {
	$wprf_randomize = get_option('wprf_randomize');
    if (($query->is_feed) && ($wprf_randomize == 1)) {
        $query->set('orderby','rand');
    }
    return $query;
}

add_filter('pre_get_posts','wprf_random_feeds');

add_action('do_feed', 'wprf_random_feeds', 1);
add_action('do_feed_rdf', 'wprf_random_feeds', 1);
add_action('do_feed_rss', 'wprf_random_feeds', 1);
add_action('do_feed_rss2', 'wprf_random_feeds', 1);
add_action('do_feed_atom', 'wprf_random_feeds', 1);

?>
