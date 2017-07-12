<?php
/**
 * Dewey's Open Graph Helper
 *
 * @package   WADOpenGraph
 * @author    Luke DeWitt <dewey@whatadewitt.com>
 * @license   GPL-2.0+
 * @link      http://www.whatadewitt.ca
 * @copyright 2014 Luke DeWitt
 */

/**
 * Plugin class.
 *
 * @package wad_open_graph
 * @author  Luke DeWitt <dewey@whatadewitt.com>
 */
class WADOpenGraph {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   1.0.0
   *
   * @var     string
   */
  protected $version = '2.0.4';

  /**
   * Unique identifier for your plugin.
   *
   * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
   * match the Text Domain file header in the main plugin file.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'wad_open_graph';

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Slug of the plugin screen.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_screen_hook_suffix = 'wad_open_graph';

  /**
   * Initialize the plugin by setting localization, filters, and administration functions.
   *
   * @since     1.0.0
   */
  private function __construct() {

    // Load plugin text domain
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    // Open graph
    add_action( 'wp_head', array( $this, 'generate_open_graph' ) );

    // 200x200 image for facebook...
    add_action( 'init', array( $this, 'add_og_image_size' ) );

    // add og meta box
    add_action( 'add_meta_boxes', array( $this, 'add_og_meta_box' ) );

    // save og meta
    add_action( 'save_post', array( $this, 'save_og_data' ) );
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {

    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    $domain = $this->plugin_slug;
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
  }

  /**
   * Add 200x200 image for OG, now default for Facebook
   *
   * @since    1.0.0
   */
  public static function add_og_image_size() {
    add_image_size('200x200', 200, 200, true); // old size
    add_image_size('1200x630', 1200, 630, true); // new size
  }

  /**
   * Print the OG tags to the page head
   *
   * @since    1.0.0
   */
  public function generate_open_graph() {
    global $post;
    $tags = array();

    if ( is_singular() ) {
      if ( post_type_supports( $post->post_type, 'ogtags' ) ) {
        // excerpt
        $excerpt = get_the_excerpt();
        if ( '' == $excerpt ) {
          $excerpt = strip_tags($post->post_content);
          $excerpt = strip_shortcodes($excerpt);
          $excerpt = str_replace(array("\n", "\r", "\t"), ' ', $excerpt);
          $excerpt = substr($excerpt, 0, 155);
          $excerpt = $excerpt.'...';
        }

        // define defaults
        $tags['site_name'] = get_bloginfo( 'name' );
        $tags['title'] = get_the_title();
        $tags['type'] = 'article';
        $tags['url'] = get_permalink();
        $tags['description'] = $excerpt;

        //Check for a post thumbnail.
        if ( current_theme_supports('post-thumbnails') && has_post_thumbnail( $post->ID ) ) {
          $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '200x200', false);
          $tags['image'] = $thumbnail[0];
        }

        if ( $og_title = get_post_meta($post->ID, 'og_title', true) ) {
          $tags['title'] = $og_title;
        }

        if ( $og_desc = get_post_meta($post->ID, 'og_desc', true) ) {
          $tags['description'] = $og_desc;
        }

        if ( $og_type = get_post_meta($post->ID, 'og_type', true) ) {
          $tags['type'] = $og_type;
        }

        // filter post tags
        $tags = apply_filters( "og_tags" , $tags );
        $tags = apply_filters( "{$post->post_type}_og_tags" , $tags );
      }
    } else if ( is_front_page() ) {
      $tags['site_name'] = get_bloginfo( 'name' );
      $tags['title'] = get_bloginfo( 'name' );
      $tags['type'] = 'website';
      $tags['url'] = get_bloginfo( 'url' );
      $tags['description'] = get_bloginfo( 'description' );

      $tags = apply_filters( "og_tags" , $tags );
      $tags = apply_filters( "front_page_og_tags" , $tags );
    }

    //Loop through the tags and generate the open graph tags
    foreach ( $tags as $key => $value ) {
      echo("<meta property=\"og:" . $key . "\" content=\"" . htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false) . "\" />\n");
    }
  }

  /**
   * Add the og overrides meta box to the page
   *
   * @since    2.0.0
   */
  public function add_og_meta_box($post_type) {
    if ( post_type_supports($post_type, 'ogtags') ) {
      add_meta_box(
        'og_meta',
        'Custom Open Graph Overrides',
        array( $this, 'add_og_meta_box_callback' ),
        $post_type
        );
    }
  }

  /**
   * Callback function to display the meta box
   *
   * @since    2.0.0
   */
  public function add_og_meta_box_callback() {
    wp_enqueue_style( 'wad_og', plugin_dir_url( __FILE__ ) . '/css/admin.css' );

    wp_nonce_field( 'wad_og', 'wad_og_nonce' );
    include plugin_dir_path( __FILE__ ) . 'templates/ogform.php';
  }

  /**
   * Save the OG data
   *
   * @since    2.0.0
   */
  public function save_og_data($post_id) {
    if ( !post_type_supports($_POST['post_type'], 'ogtags') ) {
      return;
    }

    // verify nonce
    if ( ! wp_verify_nonce( $_POST['wad_og_nonce'], 'wad_og' ) ) {
      return;
    }

    if ( isset($_POST['og_title']) && !empty($_POST['og_title']) ) {
      update_post_meta($post_id, 'og_title', stripslashes($_POST['og_title']));
    } else {
      delete_post_meta($post_id, 'og_title');
    }

    if ( isset($_POST['og_desc']) && !empty($_POST['og_desc']) ) {
      update_post_meta($post_id, 'og_desc', stripslashes($_POST['og_desc']));
    } else {
      delete_post_meta($post_id, 'og_desc');
    }

    if ( isset($_POST['og_type']) && !empty($_POST['og_type']) ) {
      update_post_meta($post_id, 'og_type', stripslashes($_POST['og_type']));
    } else {
      delete_post_meta($post_id, 'og_type');
    }

    return $post_id;
  }
}
