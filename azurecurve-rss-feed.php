<?php
/*
Plugin Name: azurecurve RSS Feed
Plugin URI: http://development.azurecurve.co.uk/plugins/rss-feed

Description: Provides opposite rss feed to that configured in WordPress; e.g. if WordPress is configured for summary then an alternative feed called detail will be created
Version: 2.0.2

Author: azurecurve
Author URI: http://development.azurecurve.co.uk

Text Domain: azc_rssf
Domain Path: /languages

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt
*/

//include menu
require_once( dirname(  __FILE__ ) . '/includes/menu.php');

add_action('init', 'azc_rssf_init');

function azc_rssf_init(){
	if (get_option('rss_use_excerpt')){
		$rss_type = 'detail';
	}else{
		$rss_type = 'summary';
	}
	add_feed($rss_type, 'azc_rssf_create_feed');
	
	//Ensure the $wp_rewrite global is loaded
	global $wp_rewrite;
	//Call flush_rules() as a method of the $wp_rewrite object
	$wp_rewrite->flush_rules( false );
}

function azc_rssf_create_feed(){
	load_template( plugin_dir_path( __FILE__ ) . 'templates/rss_feed2.php' );
}


// azurecurve menu
function azc_create_rssf_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"RSS Feed"
						,"RSS Feed"
						,'manage_options'
						,"azc-rssf"
						,"azc_rssf_settings" );
}
add_action("admin_menu", "azc_create_rssf_plugin_menu");

function azc_rssf_settings() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error('not_found', __('You do not have sufficient permissions to access this page.' , 'azc_rssf'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	?>
	<div id="azc-t-general" class="wrap">
			<h2>azurecurve RSS Feed</h2>

			<?php _e('<p>This plugin provides opposite rss feed to that configured in WordPress; e.g. if WordPress is configured for summary then an alternative feed called detail will be created, or if WordPress is configured for a detailed feed then an alternative feed called summary is created.</p>

<p>Once active, both summary and detail feeds cab be access using the following paths:', 'azc_rssf');

		if (get_option('rss_use_excerpt')){
			echo '<ul><li><li><a href="'.site_url().'/feed">'.site_url().'/feed</a></li><a href="'.site_url().'/feed/detail">'.site_url().'/feed/detail</a></li></ul></p>';
		}else{
			echo '<ul><li><a href="'.site_url().'/feed/summary">'.site_url().'/feed/summary</a></li><li><a href="'.site_url().'/feed">'.site_url().'/feed</a></li></ul></p>';
		}
	?>
	</div>
	
<?php
}

?>