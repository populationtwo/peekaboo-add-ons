<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://population-2.com
 * @since      1.0.0
 *
 * @package    Peekaboo_Add_Ons
 * @subpackage Peekaboo_Add_Ons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Peekaboo_Add_Ons
 * @subpackage Peekaboo_Add_Ons/admin
 * @author     Population2 <info@population-2.com>
 */
class Peekaboo_Add_Ons_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
//	public function enqueue_styles() {
//
//		/**
//		 * This function is provided for demonstration purposes only.
//		 *
//		 * An instance of this class should be passed to the run() function
//		 * defined in Peekaboo_Add_Ons_Loader as all of the hooks are defined
//		 * in that particular class.
//		 *
//		 * The Peekaboo_Add_Ons_Loader will then create the relationship
//		 * between the defined hooks and the functions defined in this
//		 * class.
//		 */
//
//		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/peekaboo-add-ons-admin.css', array(), $this->version, 'all' );
//
//	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
//	public function enqueue_scripts() {
//
//		/**
//		 * This function is provided for demonstration purposes only.
//		 *
//		 * An instance of this class should be passed to the run() function
//		 * defined in Peekaboo_Add_Ons_Loader as all of the hooks are defined
//		 * in that particular class.
//		 *
//		 * The Peekaboo_Add_Ons_Loader will then create the relationship
//		 * between the defined hooks and the functions defined in this
//		 * class.
//		 */
//
//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/peekaboo-add-ons-admin.js', array( 'jquery' ), $this->version, false );
//
//	}


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
