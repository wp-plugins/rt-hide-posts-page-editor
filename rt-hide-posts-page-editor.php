<?php
/**
 * Plugin Name: RT Hide Posts Page Editor
 * Plugin URI: http://www.roytanck.com
 * Description: Hides the content editor for the page that is set as the site's "posts page".
 * Version: 0.9
 * Author: Roy Tanck
 * Author URI: http://www.roytanck.com
 * Text Domain: rt-hide-editor
 * Domain Path: /languages
 * License: GPL2
*/

// if called without WordPress, exit
if( !defined('ABSPATH') ){ exit; }


/**
 * RT_Hide_Posts_Page_Editor class
 */
if( !class_exists('RT_Hide_Posts_Page_Editor') ){

	class RT_Hide_Posts_Page_Editor {

		/**
		 * Constructor
		 */
		public function __construct(){
			add_action( 'load-post.php', array( $this, 'remove_editor' ), 10 );
			// load the text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		/**
		 * Function to load the text domain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'rt-hide-posts-page-editor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		}

		/**
		 * Hook into load-post.php, and hide the editor
		 */
		function remove_editor(){
			// check if we're in the admin
			if( !is_admin() ){ return; }

			// get the page ID for the page set as home page
			$frontpage_id = intval( get_option('page_for_posts') );

			// if we're editing the posts page, hide the editor
			if( $frontpage_id != 0 && isset( $_GET['post'] ) && intval( $_GET['post'] ) == $frontpage_id ){
				// get rid of the editor
				remove_post_type_support( 'page', 'editor' );
				// show admin notice
				add_action('admin_notices', array( $this, 'display_notice' ) );
			}

		}

		/**
		 * Display a notice in case the editor is hidden
		 */
		function display_notice(){
			echo '<div class="updated"><p>';
			printf( __( 'This page is currently set as the <a href="%s">posts page</a>. Because it will display your most recent posts, the content editor is hidden.', 'rt-hide-posts-page-editor' ), admin_url('options-reading.php') );
			echo '</p></div>';
		}

	}
}


/**
 * Create an instance of the class, so it actually does something
 */
if(class_exists('RT_Hide_Posts_Page_Editor') ){
    // instantiate the plugin class
    $rt_hide_posts_page_editor = new RT_Hide_Posts_Page_Editor();
}

?>