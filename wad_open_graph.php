<?php
/**
 * @package   WADOpenGraph
 * @author    Luke DeWitt <dewey@whatadewitt.com>
 * @license   GPL-2.0+
 * @link      http://www.whatadewitt.ca
 * @copyright 2014 Luke DeWitt
 *
 * @wordpress-plugin
 * Plugin Name: Dewey's Open Graph Helper
 * Plugin URI:  http://www.whatadewitt.ca
 * Description: Simplifies the use of Open Graph.
 * Version:     2.0.5
 * Author:      Luke DeWitt
 * Author URI:  http://www.whatadewitt.ca
 * Text Domain: wad_open_graph
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-wad_open_graph.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'WADOpenGraph', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WADOpenGraph', 'deactivate' ) );

WADOpenGraph::get_instance();
