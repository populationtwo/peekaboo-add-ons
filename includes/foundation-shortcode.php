<?php

/**
 * Foundation Shortcode based on Required+ source code http://themes.required.ch/theme-features/shortcodes/
 */
class REQ_Alertbox {

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		/* Apply filters to the alert content. */
		add_filter( 'req_alertbox_content', 'shortcode_unautop' );
		add_filter( 'req_alertbox_content', 'do_shortcode' );
	}

	/**
	 * Registers the [alert] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'alert', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Returns the content of the alert shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		/* If there's no content, just return back what we got. */
		if ( is_null( $content ) ) {
			return $content;
		}

		/* Set up the default variables. */
		$output = '';

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_alertbox_defaults',
			array(
				'timeout' => '',
				'close'   => 'yes',
				'type'    => '' // success, alert, secondary
			)
		);

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_alertbox_args', $attr );

		/* Output */
		$timeout     = '';
		$closebutton = '<a href="#" class="close">&times;</a>';
		$type        = '';

		/* Check for a custom timeout */
		if ( ! empty( $attr['timeout'] ) ) {
			$timeout = ' data-alert-timeout="' . esc_attr( $attr['timeout'] ) . '"';
		}

		/* Check if the close button is not desired */
		if ( 'no' == $attr['close'] ) {
			$closebutton = '';
		}

		/* Check if there is an attribute for the type */
		if ( ! empty( $attr['type'] ) ) {
			$type = ' ' . esc_attr( $attr['type'] );
		}

		/* Create our output */
		$output = '<div data-alert class="alert-box' . $type . '"' . $timeout . '>' . apply_filters( 'req_alertbox_content', $content ) . $closebutton . '</div>';

		/* Return the output of the column. */

		return apply_filters( 'req_alertbox', $output );
	}
}

new REQ_Alertbox();


class REQ_Clearing {

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		add_action( 'wp_head', array( &$this, 'admin_bar_fix' ), 5 );
	}

	/**
	 * Registers the [clearing] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'clearing', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Returns the content of the clearing shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr ) {

		global $post;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] ) {
				unset( $attr['orderby'] );
			}
		}

		/* Set up the default variables. */
		$output         = '';
		$column_classes = '';
		$fearued_class  = '';

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_clearing_defaults',
			array(
				'order'    => 'ASC',
				'orderby'  => 'menu_order ID',
				'id'       => $post->ID,
				'columns'  => 3,
				'size'     => 'thumbnail',
				'include'  => '',
				'exclude'  => '',
				'featured' => ''
			)
		);

		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_clearing_args', $attr );

		/* Parse the arguments. */
		extract( $attr );

		$id = intval( $id );

		if ( 'RAND' == $order ) {
			$orderby = 'none';
		}

		if ( ! empty( $include ) ) {
			$include      = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array( 'include'        => $include,
			                                  'post_status'    => 'inherit',
			                                  'post_type'      => 'attachment',
			                                  'post_mime_type' => 'image',
			                                  'order'          => $order,
			                                  'orderby'        => $orderby
			) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $exclude ) ) {
			$exclude     = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array( 'post_parent'    => $id,
			                                    'exclude'        => $exclude,
			                                    'post_status'    => 'inherit',
			                                    'post_type'      => 'attachment',
			                                    'post_mime_type' => 'image',
			                                    'order'          => $order,
			                                    'orderby'        => $orderby
			) );
		} else {
			$attachments = get_children( array( 'post_parent'    => $id,
			                                    'post_status'    => 'inherit',
			                                    'post_type'      => 'attachment',
			                                    'post_mime_type' => 'image',
			                                    'order'          => $order,
			                                    'orderby'        => $orderby
			) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			}

			return $output;
		}

		/* Assign correct classes */
		$columns = intval( $columns );
		switch ( $columns ) {
			case 1:
				$column_classes = '';
				break;
			case 2:
				$column_classes = 'large-block-grid-2 small-block-grid-2';
				break;
			case 4:
				$column_classes = 'large-block-grid-4 small-block-grid-2';
				break;
			case 5:
				$column_classes = 'large-block-grid-5 small-block-grid-2';
				break;
			case 6:
				$column_classes = 'large-block-grid-6 small-block-grid-2';
				break;
			case 3:
			default:
				$column_classes = 'large-block-grid-3 small-block-grid-2';
				break;
		}

		/* Check for featured image and remove column_classes if there is a match */
		$featured = intval( $featured );
		if ( $featured != '' ) {
			$column_classes = '';
			$fearued_class  = ' has-featured';
			$size           = 'large';
		}

		/* Let the magic happen */
		$output = '<ul class="clearing-thumbs ' . $column_classes . $fearued_class . '" data-clearing>';

		foreach ( $attachments as $id => $attachment ) {

			/* Image source for the thumbnail image */
			$img_src = wp_get_attachment_image_src( $id, $size );

			/* Image source for the full image to show on the plate */
			$img_src_full = wp_get_attachment_image_src( $id, 'full' );

			/* Check for a caption */
			$caption = '';
			if ( trim( $attachment->post_excerpt ) ) {
				$caption = ' data-caption="' . strip_tags( $attachment->post_excerpt ) . '"';
			}

			/* Check if we have a featured image for this clearing */
			$item_classes = '';
			if ( $featured == $id ) {
				$item_classes = ' class="clearing-feature"';
			}

			/* Generate final item output */
			$output .= '<li' . $item_classes . '><a href="' . esc_url( $img_src_full[0] ) . '"><img src="' . esc_url( $img_src[0] ) . '"' . $caption . ' /></a></li>';
		}

		$output .= '</ul>';

		/* Return the output of the column. */

		return apply_filters( 'req_clearing', $output );
	}

	/**
	 * Helper to fix the admin bar positioning issue
	 * @return string css
	 */
	public function admin_bar_fix() {
		if ( ! is_admin() && is_admin_bar_showing() ) {
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
			$output = '<style type="text/css">' . "\n\t";
			$output .= 'body.admin-bar .clearing-close { top: 28px; }' . "\n";
			$output .= '</style>' . "\n";
			echo $output;
		}
	}

}

new REQ_Clearing();


class REQ_Column_Shortcode {

	/**
	 * The columns in our grid
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $grid = 12;

	/**
	 * The current total number of columns in the grid.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $columns = 0;

	/**
	 * Whether we're viewing the first column.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    bool
	 */
	public $is_first_column = true;

	/**
	 * Whether we're viewing the last column.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    bool
	 */
	public $is_last_column = false;

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		/* Apply filters to the column content. */
		add_filter( 'req_column_content', 'wpautop' );
		add_filter( 'req_column_content', 'shortcode_unautop' );
		add_filter( 'req_column_content', 'do_shortcode' );
	}

	/**
	 * Convert int into a word for our column classes
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  int $int
	 *
	 * @return string $word
	 */
	protected function convert_int_to_word( $int ) {

		// Make sure it's an integer
		absint( $int );

		switch ( $int ) {

			case 1:
				$word = "large-1";
				break;
			case 2:
				$word = "large-2";
				break;
			case 3:
				$word = "large-3";
				break;
			case 4:
				$word = "large-4";
				break;
			case 5:
				$word = "large-5";
				break;
			case 6:
				$word = "large-6";
				break;
			case 7:
				$word = "large-7";
				break;
			case 8:
				$word = "large-8";
				break;
			case 9:
				$word = "large-9";
				break;
			case 10:
				$word = "large-10";
				break;
			case 11:
				$word = "large-11";
				break;
			case 12:
				$word = "large-12";
				break;
			case 0:
			default:
				$word = "zero";
				break;
		}

		return $word;
	}

	/**
	 * Convert word to int for legacy support of old colmun shortcodes
	 *
	 * @since  0.1.0
	 * @access protected
	 *
	 * @param  string $word
	 *
	 * @return int $int
	 */
	protected function convert_word_to_int( $word ) {

		switch ( $word ) {

			case "one":
				$int = 1;
				break;
			case "two":
				$int = 2;
				break;
			case "three":
				$int = 3;
				break;
			case "four":
				$int = 4;
				break;
			case "five":
				$int = 5;
				break;
			case "six":
				$int = 6;
				break;
			case "seven":
				$int = 7;
				break;
			case "eight":
				$int = 8;
				break;
			case "nine":
				$int = 9;
				break;
			case "ten":
				$int = 10;
				break;
			case "eleven":
				$int = 11;
				break;
			case "twelve":
				$int = 12;
				break;
			case "zero":
			default:
				$int = 0;
				break;

		}

		return $int;
	}

	/**
	 * Registers the [column] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'column', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Returns the content of the column shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		/* If there's no content, just return back what we got. */
		if ( is_null( $content ) ) {
			return $content;
		}

		/* Set up the default variables. */
		$output         = '';
		$row_classes    = array();
		$column_classes = array();

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_column_defaults',
			array(
				'columns' => 1,
				'offset'  => 0,
				'class'   => ''
			)
		);

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_column_args', $attr );

		/* Legacy support for old column shortcode */
		if ( ! is_numeric( $attr['columns'] ) ) {
			$attr['columns'] = $this->convert_word_to_int( $attr['columns'] );
		}

		/* Columns cannot be greater than the grid. */
		$attr['columns'] = ( $this->grid >= $attr['columns'] ) ? absint( $attr['columns'] ) : 3;

		/* The offset argument should always be less than the grid. */
		$attr['offset'] = ( $this->grid > $attr['offset'] ) ? absint( $attr['offset'] ) : 0;

		/* Add to the total $columns. */
		$this->columns = $this->columns + $attr['columns'] + $attr['offset'];

		/* Column classes. */
		$column_classes[] = 'columns';
		$column_classes[] = $this->convert_int_to_word( $attr['columns'] );
		if ( $attr['offset'] !== 0 ) // Offset is only necessary if it's not 0
		{
			$column_classes[] = "offset-by-{$this->convert_int_to_word($attr['offset'])}";
		}

		/* Add user-input custom class(es). */
		if ( ! empty( $attr['class'] ) ) {
			if ( ! is_array( $attr['class'] ) ) {
				$attr['class'] = preg_split( '#\s+#', $attr['class'] );
			}
			$column_classes = array_merge( $column_classes, $attr['class'] );
		}

		/* If the $span property is greater than (shouldn't be) or equal to the $grid property. */
		if ( $this->columns >= $this->grid ) {

			/* Set the $is_last_column property to true. */
			$this->is_last_column = true;
		}

		/* Object properties. */
		$object_vars = get_object_vars( $this );

		/* Allow devs to create custom classes. */
		$column_classes = apply_filters( 'req_column_class', $column_classes, $attr, $object_vars );

		/* Sanitize and join all classes. */
		$column_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $column_classes ) ) );

		/* Output */

		/* If this is the first column. */
		if ( $this->is_first_column ) {

			/* Open a wrapper <div> to contain the columns. */
			$output .= '<div class="row">';

			/* Set the $is_first_column property back to false. */
			$this->is_first_column = false;
		}

		/* Add the current column to the output. */
		$output .= '<div class="' . $column_class . '">' . apply_filters( 'req_column_content', $content ) . '</div>';

		/* If this is the last column. */
		if ( $this->is_last_column ) {

			/* Close the wrapper. */
			$output .= '</div>';

			/* Reset the properties that have been changed. */
			$this->reset();
		}

		/* Return the output of the column. */

		return apply_filters( 'req_column', $output );
	}

	/**
	 * Resets the properties to their original states.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function reset() {

		foreach ( get_class_vars( __CLASS__ ) as $name => $default ) {
			$this->$name = $default;
		}
	}
}

/**
 * If you prefer the shortcode by http://themehybrid.com/plugins/grid-columns
 * please go ahead and use it. We don't stop you!
 */
if ( ! class_exists( 'Grid_Columns' ) ) {
	new REQ_Column_Shortcode();
}


class REQ_Orbit {

	/**
	 * Holds the stuff we want to output in the footer
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $footer_content = array();

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		add_action( 'wp_footer', array( &$this, 'add_footer_output' ), 640 );
	}

	/**
	 * Registers the [orbit] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'orbit', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Returns the content of the orbit shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		global $post;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] ) {
				unset( $attr['orderby'] );
			}
		}

		/* Set up the default variables. */
		$output  = '';
		$caption = '';

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_orbit_defaults',
			array(
				'order'   => 'ASC',
				'orderby' => 'menu_order ID',
				'id'      => $post->ID,
				'size'    => 'large',
				'include' => '',
				'exclude' => ''
			)
		);

		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_orbit_args', $attr );

		/* Parse the arguments. */
		extract( $attr );

		$id = intval( $id );

		/* Global script options */
		$orbit_script_args = apply_filters(
			'req_orbit_script_args',
			array()
		);

		$orbit_script_args = apply_filters(
			"req_orbit_script_args_{$id}",
			$orbit_script_args
		);

		if ( 'RAND' == $order ) {
			$orderby = 'none';
		}

		if ( ! empty( $include ) ) {
			$include      = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array( 'include'        => $include,
			                                  'post_status'    => 'inherit',
			                                  'post_type'      => 'attachment',
			                                  'post_mime_type' => 'image',
			                                  'order'          => $order,
			                                  'orderby'        => $orderby
			) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $exclude ) ) {
			$exclude     = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array( 'post_parent'    => $id,
			                                    'exclude'        => $exclude,
			                                    'post_status'    => 'inherit',
			                                    'post_type'      => 'attachment',
			                                    'post_mime_type' => 'image',
			                                    'order'          => $order,
			                                    'orderby'        => $orderby
			) );
		} else {
			$attachments = get_children( array( 'post_parent'    => $id,
			                                    'post_status'    => 'inherit',
			                                    'post_type'      => 'attachment',
			                                    'post_mime_type' => 'image',
			                                    'order'          => $order,
			                                    'orderby'        => $orderby
			) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			}

			return $output;
		}


		/* Let the magic happen */
		$output = '<ul class="req-orbit" id="req-orbit-' . $id . '" data-orbit>';


		$orbit_script_options = '';

		if ( ! empty( $orbit_script_args ) ) {
			$orbit_script_options = json_encode( $orbit_script_args );
		}
		$this->footer_content[] = "$('#req-orbit-{$id}').orbit({$orbit_script_options});";

		foreach ( $attachments as $id => $attachment ) {

			/* Image source for the thumbnail image */
			$img_src = wp_get_attachment_image_src( $id, $size );

			/* Check for a caption */
			$data_caption = '';

			if ( trim( $attachment->post_excerpt ) ) {
				$caption_id   = 'req-caption-' . $id;
				$data_caption = ' data-caption="#' . $caption_id . '"';
				$caption .= '<span class="orbit-caption" id="' . $caption_id . '">' . wptexturize( $attachment->post_excerpt ) . '</span>';
			}

			/* Generate final item output */
			$output .= '<li><img src="' . esc_url( $img_src[0] ) . '"' . $data_caption . ' /></li>';
		}

		$output .= '</ul>' . $caption;

		/* Return the output of the orbit. */

		return apply_filters( 'req_orbit', $output );
	}

	/**
	 * Retuns the $content of the modal as reveal html
	 */
	public function add_footer_output() {

	}

}

new REQ_Orbit();


class REQ_Reveal {

	/**
	 * Holds the stuff we want to output in the footer
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    int
	 */
	public $footer_content = array();

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		add_action( 'wp_footer', array( &$this, 'add_footer_output' ) );

		/* Apply filters to the reveal content. */
		add_filter( 'req_reveal_content', array( &$this, 'inception_helper' ) );
		add_filter( 'req_reveal_content', 'wpautop' );
		add_filter( 'req_reveal_content', 'shortcode_unautop' );

	}

	/**
	 * Registers the [reveal] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'reveal', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Returns the content of the reveal shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		global $post;

		/* If there's no content, just return back what we got. */
		if ( is_null( $content ) ) {
			return $content;
		}

		/* Set up the default variables. */
		$output         = '';
		$footer_output  = '';
		$reveal_classes = array();
		$link_classes   = array();

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_reveal_defaults',
			array(
				'link'      => '',
				'class'     => '',
				'linkclass' => ''
			)
		);

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_reveal_args', $attr );

		$reveal_classes[] = 'reveal-modal';

		if ( ! empty( $attr['class'] ) ) {
			if ( ! is_array( $attr['class'] ) ) {
				$attr['class'] = preg_split( '#\s+#', $attr['class'] );
			}

			$reveal_classes = array_merge( $reveal_classes, $attr['class'] );
		}

		$reveal_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $reveal_classes ) ) );

		if ( ! empty( $attr['linkclass'] ) ) {
			if ( ! is_array( $attr['linkclass'] ) ) {
				$attr['linkclass'] = preg_split( '#\s+#', $attr['linkclass'] );
			}

			$link_classes = array_merge( $link_classes, $attr['linkclass'] );
		}

		$link_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $link_classes ) ) );

		$modal_unique_id = 'required-reveal-' . $post->ID . '-' . rand( 1000, 9999 );

		$output = '<a href="#"  class="' . $link_class . '" data-reveal-id="' . esc_attr( $modal_unique_id ) . '">' . esc_attr( $attr['link'] ) . '</a>';

		$footer_output = '<div id="' . esc_attr( $modal_unique_id ) . '" class="' . $reveal_class . ' " data-reveal>' . apply_filters( 'req_reveal_content', $content ) . '<a class="close-reveal-modal">&#215;</a></div>';

		$this->footer_content[] = $footer_output;

		//
		/* Return the output of the reveal. */

		return apply_filters( 'req_reveal', $output );
	}

	/**
	 * Retuns the $content of the modal as reveal html
	 */
	public function add_footer_output() {

		if ( ! empty( $this->footer_content ) ) {

			echo '<!-- Output generated by [reveal] shortcode in this page: -->';

			foreach ( $this->footer_content as $reveal ) {
				echo $reveal;
			}

			echo '<!-- / [reveal] output -->';
		}

	}

	/**
	 * Makes nested reveal shortcodes possible
	 *
	 * Idea from the super ugly: http://www.jshortcodes.com/
	 *
	 * @param  string $content The content of the shortcode
	 *
	 * @return string
	 */
	public function inception_helper( $content ) {

		// Quick test for presence of possibly nested shortcodes
		if ( strpos( $content, '[=' ) !== false ) {
			// remove one '=' --> un-nest one level
			$content = preg_replace( '@(\[=*)=(r|/)@', "$1$2", $content );
		}

		return do_shortcode( $content );
	}

}

new REQ_Reveal();


class REQ_Tooltip {

	/**
	 * Sets up our actions/filters.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Register shortcodes on 'init'. */
		add_action( 'init', array( &$this, 'register_shortcode' ) );

		add_action( 'wp_head', array( &$this, 'admin_bar_fix' ), 5 );

		/* Apply filters to the tooltip content. */
		add_filter( 'req_tooltip_content', 'shortcode_unautop' );
		add_filter( 'req_tooltip_content', 'do_shortcode' );
	}

	/**
	 * Registers the [tooltip] shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'tooltip', array( &$this, 'do_shortcode' ) );
	}

	/**
	 * Fixes the position error for logged in users
	 *
	 * @since  0.1.0
	 * @return strint CSS Styles
	 */
	public function admin_bar_fix() {
		if ( ! is_admin() && is_admin_bar_showing() ) {
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
			$output = '<style type="text/css">' . "\n\t";
			//$output .= 'body.admin-bar { padding-top: 28px; }'."\n";
			$output .= 'body.admin-bar .top-bar { margin-top: 28px; }' . "\n";
			$output .= '</style>' . "\n";
			echo $output;
		}
	}

	/**
	 * Returns the content of the tooltip shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 *
	 * @param  array  $attr    The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 *
	 * @return string
	 */
	public function do_shortcode( $attr, $content = null ) {

		/* If there's no content, just return back what we got. */
		if ( is_null( $content ) ) {
			return $content;
		}

		/* Set up the default variables. */
		$output          = '';
		$tooltip_classes = array();
		$title           = '';
		$width           = '';

		/* Set up the default arguments. */
		$defaults = apply_filters(
			'req_tooltip_defaults',
			array(
				'position' => 'bottom',
				'width'    => '',
				'class'    => '',
				'title'    => ''
			)
		);

		/* Parse the arguments. */
		$attr = shortcode_atts( $defaults, $attr );

		/* Allow devs to filter the arguments. */
		$attr = apply_filters( 'req_tooltip_args', $attr );

		/* Make the title (tip text) ready */
		if ( empty( $attr['title'] ) ) {
			return $content;
		} else {
			$title = ' title="' . esc_attr( $attr['title'] ) . '"';
		}

		/* Assign default class */
		$tooltip_classes[] = 'has-tip';

		/* Switch on position attr */
		switch ( $attr['position'] ) {
			case 'top':
				$tooltip_classes[] = 'tip-top';
				break;

			case 'left':
				$tooltip_classes[] = 'tip-left';
				break;

			case 'right':
				$tooltip_classes[] = 'tip-right';
				break;

			case 'bottom':
			default:
				$tooltip_classes[] = 'tip-bottom';
				break;
		}

		/* Add user-input custom class(es). */
		if ( ! empty( $attr['class'] ) ) {
			if ( ! is_array( $attr['class'] ) ) {
				$attr['class'] = preg_split( '#\s+#', $attr['class'] );
			}
			$tooltip_classes = array_merge( $tooltip_classes, $attr['class'] );
		}

		/* Sanitize and join all classes. */
		$tooltip_class = join( ' ', array_map( 'sanitize_html_class', array_unique( $tooltip_classes ) ) );

		if ( ! empty( $attr['width'] ) ) {
			$width = ' data-width="' . esc_attr( $attr['width'] ) . '"';
		}

		/* Create our output */
		$output = '<span class="' . $tooltip_class . '"' . $title . $width . ' data-tooltip>' . apply_filters( 'req_tooltip_content', $content ) . '</span>';

		/* Return the output of the tooltip. */

		return apply_filters( 'req_tooltips', $output );
	}
}

new REQ_Tooltip();

/*-----------------------------------------------------------------------------------*/
/*  Tabs
/*-----------------------------------------------------------------------------------*/

$foundation_tabs = array( 'current_id' => 0 );

function pkb_foundation_tabs( $params, $content = null ) {
	global $foundation_tabs;
	extract(
		shortcode_atts(
			array(
				'id'    => count( $foundation_tabs ),
				'class' => ''
			),
			$params
		)
	);
	$foundation_tabs[ $id ]        = array();
	$foundation_tabs['current_id'] = count( $foundation_tabs ) - 1;
	do_shortcode( $content );

	$scontent = '<div id="ft-' . $id . '" class="pkb-foundation-tabs ' . $class . '">';
	if ( isset( $foundation_tabs[ $id ]['tabs'] ) && is_array( $foundation_tabs[ $id ]['tabs'] )
	     && isset( $foundation_tabs[ $id ]['panes'] )
	     && is_array( $foundation_tabs[ $id ]['panes'] )
	) {
		$scontent .= '<ul class="tabs" data-tab>';
		$scontent .= implode( '', $foundation_tabs[ $id ]['tabs'] );
		$scontent .= '</ul>';
		$scontent .= '<div class="tabs-content">';
		$scontent .= implode( '', $foundation_tabs[ $id ]['panes'] );
		$scontent .= '</div>';
	}
	$scontent .= '</div>';


	if ( trim( $scontent ) != "" ) {
		$foundation_tabs['current_id'] = $foundation_tabs['current_id'] - 1;

		return $scontent;
	} else {
		return "";
	}
}

add_shortcode( 'tabs', 'pkb_foundation_tabs' );


function pkb_foundation_tab( $params, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'title'  => 'title',
				'active' => '',
			),
			$params
		)
	);
	global $foundation_tabs;
	$index = $foundation_tabs['current_id'];
	if ( ! isset( $foundation_tabs[ $index ]['tabs'] ) ) {
		$foundation_tabs[ $index ]['tabs'] = array();
	}
	$pane_id = 'tabpane-' . $index . '-' . count( $foundation_tabs[ $index ]['tabs'] );

	$foundation_tabs[ $index ]['tabs'][] = '<li class="tab-title ' . $active . '"><a href="#' . $pane_id . '">' . $title
	                                       . '</a></li>';
	$foundation_tabs[ $index ]['panes'][]
	                                     =
		'<div class="content ' . $active . '" id="' . $pane_id . '"><p>' . do_shortcode( trim( $content ) ) . '</p></div>';

}

add_shortcode( 'tab', 'pkb_foundation_tab' );


function pkb_foundation_vtabs( $params, $content = null ) {
	global $foundation_tabs;
	extract(
		shortcode_atts(
			array(
				'id'    => count( $foundation_tabs ),
				'class' => ''
			),
			$params
		)
	);
	$foundation_tabs[ $id ]        = array();
	$foundation_tabs['current_id'] = count( $foundation_tabs ) - 1;
	do_shortcode( $content );

	$scontent = '<div id="fvt-' . $id . '" class="pkb-foundation-vertical-tabs ' . $class . '">';
	if ( isset( $foundation_tabs[ $id ]['tabs'] ) && is_array( $foundation_tabs[ $id ]['tabs'] )
	     && isset( $foundation_tabs[ $id ]['panes'] )
	     && is_array( $foundation_tabs[ $id ]['panes'] )
	) {
		$scontent .= '<ul class="tabs vertical" data-tab>';
		$scontent .= implode( '', $foundation_tabs[ $id ]['tabs'] );
		$scontent .= '</ul>';
		$scontent .= '<div class="tabs-content vertical">';
		$scontent .= implode( '', $foundation_tabs[ $id ]['panes'] );
		$scontent .= '</div>';
	}
	$scontent .= '</div>';


	if ( trim( $scontent ) != "" ) {
		$foundation_tabs['current_id'] = $foundation_tabs['current_id'] - 1;

		return $scontent;
	} else {
		return "";
	}
}

add_shortcode( 'vtabs', 'pkb_foundation_vtabs' );


function pkb_foundation_vtab( $params, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'title'  => 'title',
				'active' => '',
			),
			$params
		)
	);
	global $foundation_tabs;
	$index = $foundation_tabs['current_id'];
	if ( ! isset( $foundation_tabs[ $index ]['tabs'] ) ) {
		$foundation_tabs[ $index ]['tabs'] = array();
	}
	$pane_id = 'tabpane-' . $index . '-' . count( $foundation_tabs[ $index ]['tabs'] );

	$foundation_tabs[ $index ]['tabs'][] = '<li class="tab-title ' . $active . '"><a href="#' . $pane_id . '">' . $title
	                                       . '</a></li>';
	$foundation_tabs[ $index ]['panes'][]
	                                     =
		'<div class="content ' . $active . '" id="' . $pane_id . '"><p>' . do_shortcode( trim( $content ) ) . '</p></div>';

}

add_shortcode( 'vtab', 'pkb_foundation_vtab' );


/*-----------------------------------------------------------------------------------*/
/*  Accordion
/*-----------------------------------------------------------------------------------*/

$foundation_accordion = array( 'current_id' => 0 );


function pkb_foundation_accordion( $params, $content = null ) {
	global $foundation_accordion;
	extract(
		shortcode_atts(
			array(
				'id'    => count( $foundation_accordion ),
				'class' => ''
			),
			$params
		)
	);
	$foundation_accordion[ $id ]        = array();
	$foundation_accordion['current_id'] = count( $foundation_accordion ) - 1;
	do_shortcode( $content );

	$scontent = '<dl class="pkb-foundation-accordion accordion ' . $class . '" data-accordion id="pfa-' . $id . '">';
	if ( isset( $foundation_accordion[ $id ]['tabs'] ) && is_array( $foundation_accordion[ $id ]['tabs'] ) ) {
		$scontent .= implode( '', $foundation_accordion[ $id ]['tabs'] );
	}
	$scontent .= '</dl>';


	if ( trim( $scontent ) != "" ) {

		$foundation_accordion['current_id'] = $foundation_accordion['current_id'] - 1;

		return $scontent;
	} else {
		return "";
	}

}

function pkb_foundation_accordion_pane( $params, $content = null ) {
	global $foundation_accordion;
	extract(
		shortcode_atts(
			array(
				'title'  => 'title',
				'active' => '',
			),
			$params
		)
	);
	//$con = do_shortcode($content);
	$index = $foundation_accordion['current_id'];
	if ( ! isset( $foundation_accordion[ $index ]['tabs'] ) ) {
		$foundation_accordion[ $index ]['tabs'] = array();
	}
	$pane_id = 'pkb-tabpane-' . $index . '-' . count( $foundation_accordion[ $index ]['tabs'] );


	$foundation_accordion[ $index ]['tabs'][] = '<dd class="accordion-navigation ' . $active . '"><a href="#' . $pane_id . '">' . $title
	                                            . '</a><div class="content ' . $active . '" id="' . $pane_id . '">'
	                                            . do_shortcode( trim( $content ) ) . '</div></dd>';

}


add_shortcode( 'accs', 'pkb_foundation_accordion' );
add_shortcode( 'acc', 'pkb_foundation_accordion_pane' );

?>