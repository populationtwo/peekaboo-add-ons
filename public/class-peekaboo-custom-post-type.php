<?php
/**
 * Peekaboo_Custom_Post_Type
 *
 * @package   Peekaboo_Custom_Post_Type
 * @author    Population2 <populationtwo@gmail.com>
 * @license   GPL-2.0+
 * @link      http://population-2.com
 * @copyright 2014 Population2
 */

/**
 * Peekaboo_Custom_Post_Type class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-peekaboo-custom-post-type-admin.php`
 *
 * @package Peekaboo_Custom_Post_Type
 * @author  Population2 <populationtwo@gmail.com>
 */
class Peekaboo_Custom_Post_Type {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '0.0.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'peekaboo-custom-post-type';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'init', array( $this, 'pkb_register_post_type_slides' ) );
		add_action( 'init', array( $this, 'pkb_register_post_type_gallery' ) );
		add_action( 'init', array( $this, 'pkb_register_post_type_testimonial' ) );

		add_action( 'init', array( $this, 'pkb_gallery_taxonomies' ) );

		add_filter( 'post_updated_messages', array( $this, 'pkb_slide_updated_messages' ) );
		add_filter( 'post_updated_messages', array( $this, 'pkb_gallery_updated_messages' ) );
		add_filter( 'post_updated_messages', array( $this, 'pkb_testimonial_updated_messages' ) );


	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}



public function pkb_register_post_type_slides() {
		$labels     = array(
			'name'               => __( 'Slides', 'peekaboo' ),
			'singular_name'      => __( 'Slide', 'peekaboo' ),
			'rewrite'            => array( 'slug' => __( 'Slides', 'peekaboo' ) ),
			'add_new'            => _x( 'Add New', 'Slide', 'peekaboo' ),
			'add_new_item'       => __( 'Add New Slide', 'peekaboo' ),
			'edit_item'          => __( 'Edit Slide', 'peekaboo' ),
			'new_item'           => __( 'New Slide', 'peekaboo' ),
			'view_item'          => __( 'View Slide', 'peekaboo' ),
			'search_items'       => __( 'Search Slides', 'peekaboo' ),
			'not_found'          => __( 'No slides found', 'peekaboo' ),
			'not_found_in_trash' => __( 'No slides found in Trash', 'peekaboo' ),
			'parent_item_colon'  => ''
		);
		$taxonomies = array();
		$supports   = array( 'title', 'thumbnail', 'custom-fields' );
		$args       = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => $supports,
			'taxonomies'         => $taxonomies

		);

		register_post_type( __( 'slide', 'peekaboo' ), $args );
	}

public function pkb_slide_updated_messages( $messages ) {
		global $post;

		$messages[ __( 'slide', 'peekaboo' ) ] =
			array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( 'Slide updated. <a href="%s">View slide</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				2  => __( 'Custom field updated.', 'peekaboo' ),
				3  => __( 'Custom field deleted.', 'peekaboo' ),
				4  => __( 'Slide updated.', 'peekaboo' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Slide restored to revision from %s', 'peekaboo' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Slide published. <a href="%s">View slide</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				7  => __( 'Slide saved.', 'peekaboo' ),
				8  => sprintf( __( 'Slide submitted. <a target="_blank" href="%s">Preview slide</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
				9  => sprintf( __( 'Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>', 'peekaboo' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'peekaboo' ), strtotime( $post->post_date ) ), esc_url( get_permalink() ) ),
				10 => sprintf( __( 'Slide draft updated. <a target="_blank" href="%s">Preview slide</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
			);

		return $messages;

	}


	public function pkb_register_post_type_gallery() {
		$labels = array(
			'name'               => __( 'Gallery', 'peekaboo' ),
			'singular_name'      => __( 'Gallery', 'peekaboo' ),
			'rewrite'            => array( 'slug' => __( 'gallery', 'peekaboo' ) ),
			'add_new'            => _x( 'Add New', 'slide', 'peekaboo' ),
			'add_new_item'       => __( 'Add New Gallery', 'peekaboo' ),
			'edit_item'          => __( 'Edit Gallery', 'peekaboo' ),
			'new_item'           => __( 'New Gallery', 'peekaboo' ),
			'view_item'          => __( 'View Gallery', 'peekaboo' ),
			'search_items'       => __( 'Search Gallery', 'peekaboo' ),
			'not_found'          => __( 'No gallery found', 'peekaboo' ),
			'not_found_in_trash' => __( 'No gallery found in Trash', 'peekaboo' ),
			'parent_item_colon'  => ''
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' )
		);

		register_post_type( __( 'gallery', 'peekaboo' ), $args );
	}

	public function pkb_gallery_taxonomies() {
		register_taxonomy( __( 'media-type', 'peekaboo' ), array( __( 'gallery', 'peekaboo' ) ), array(
			"hierarchical"   => true,
			"label"          => __( 'Item Categories', 'peekaboo' ),
			"singular_label" => __( 'Item Categories', 'peekaboo' ),
			"rewrite"        => array(
				'slug'         => 'media-type',
				'hierarchical' => true
			)
		) );
	}

	public function pkb_gallery_updated_messages( $messages ) {
		global $post;

		$messages[ __( 'gallery', 'peekaboo' ) ] =
			array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( 'Gallery updated. <a href="%s">View gallery</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				2  => __( 'Custom field updated.', 'peekaboo' ),
				3  => __( 'Custom field deleted.', 'peekaboo' ),
				4  => __( 'Gallery updated.', 'peekaboo' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Gallery restored to revision from %s', 'peekaboo' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Gallery published. <a href="%s">View gallery</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				7  => __( 'Gallery saved.', 'peekaboo' ),
				8  => sprintf( __( 'Gallery submitted. <a target="_blank" href="%s">Preview gallery</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
				9  => sprintf( __( 'Gallery scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview gallery</a>', 'peekaboo' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'peekaboo' ), strtotime( $post->post_date ) ), esc_url( get_permalink() ) ),
				10 => sprintf( __( 'Gallery draft updated. <a target="_blank" href="%s">Preview gallery</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
			);

		return $messages;

	}


	public function pkb_register_post_type_testimonial() {
		$labels     = array(
			'name'               => __( 'Testimonials', 'peekaboo' ),
			'singular_name'      => __( 'testimonial', 'peekaboo' ),
			'rewrite'            => array( 'slug' => __( 'Testimonials', 'peekaboo' ) ),
			'add_new'            => _x( 'Add New', 'testimonial', 'peekaboo' ),
			'add_new_item'       => __( 'Add New testimonial', 'peekaboo' ),
			'edit_item'          => __( 'Edit testimonial', 'peekaboo' ),
			'new_item'           => __( 'New testimonial', 'peekaboo' ),
			'view_item'          => __( 'View testimonial', 'peekaboo' ),
			'search_items'       => __( 'Search Testimonials', 'peekaboo' ),
			'not_found'          => __( 'No testimonials found', 'peekaboo' ),
			'not_found_in_trash' => __( 'No testimonials found in Trash', 'peekaboo' ),
			'parent_item_colon'  => ''
		);
		$taxonomies = array();
		$supports   = array( 'title', 'editor', 'custom-fields', 'excerpt' );
		$args       = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => $supports,
			'taxonomies'         => $taxonomies

		);

		register_post_type( __( 'testimonial', 'peekaboo' ), $args );
	}

	public function pkb_testimonial_updated_messages( $messages ) {
		global $post;

		$messages[ __( 'testimonial', 'peekaboo' ) ] =
			array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( 'Testimonial updated. <a href="%s">View testimonial</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				2  => __( 'Custom field updated.', 'peekaboo' ),
				3  => __( 'Custom field deleted.', 'peekaboo' ),
				4  => __( 'Testimonial updated.', 'peekaboo' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s', 'peekaboo' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( 'Testimonial published. <a href="%s">View testimonial</a>', 'peekaboo' ), esc_url( get_permalink() ) ),
				7  => __( 'Testimonial saved.', 'peekaboo' ),
				8  => sprintf( __( 'Testimonial submitted. <a target="_blank" href="%s">Preview testimonial</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
				9  => sprintf( __( 'Testimonial scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview testimonial</a>', 'peekaboo' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'peekaboo' ), strtotime( $post->post_date ) ), esc_url( get_permalink() ) ),
				10 => sprintf( __( 'Testimonial draft updated. <a target="_blank" href="%s">Preview testimonial</a>', 'peekaboo' ), esc_url( add_query_arg( 'preview', 'true', get_permalink() ) ) ),
			);

		return $messages;

	}


}
