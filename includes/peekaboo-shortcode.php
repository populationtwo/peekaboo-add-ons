<?php

/*-----------------------------------------------------------------------------------*/
/*	Removes ugly codes in shortcodes
/*-----------------------------------------------------------------------------------*/
function parse_shortcode_content( $content ) {

	/* Parse nested shortcodes and add formatting. */
	$content = trim( wpautop( do_shortcode( $content ) ) );

	/* Remove '</p>' from the start of the string. */
	if ( substr( $content, 0, 4 ) == '</p>' ) {
		$content = substr( $content, 4 );
	}

	/* Remove '<p>' from the end of the string. */
	if ( substr( $content, - 3, 3 ) == '<p>' ) {
		$content = substr( $content, 0, - 3 );
	}

	/* Remove any instances of '<p></p>'. */
	$content = str_replace( array( '<p></p>' ), '', $content );

	return $content;
}


/*-----------------------------------------------------------------------------------*/
/*	Grid Shortcodes
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'one_half_alpha', 'pkb_one_half_alpha' );
add_shortcode( 'one_half_omega', 'pkb_one_half_omega' );
add_shortcode( 'one_third_alpha', 'pkb_one_third_alpha' );
add_shortcode( 'one_third', 'pkb_one_third' );
add_shortcode( 'one_third_omega', 'pkb_one_third_omega' );
add_shortcode( 'one_fourth_alpha', 'pkb_one_fourth_alpha' );
add_shortcode( 'one_fourth', 'pkb_one_fourth' );
add_shortcode( 'one_fourth_omega', 'pkb_one_fourth_omega' );
add_shortcode( 'large_8_alpha', 'pkb_large_8_alpha' );
add_shortcode( 'large_8_omega', 'pkb_large_8_omega' );
add_shortcode( 'clear', 'pkb_clearfix' );
add_shortcode( 'line', 'pkb_line' );

/* Columns with Sidebar */
function pkb_one_half_alpha( $atts, $content = null ) {
	return '<div class="row"><div class="large-6 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_one_half_omega( $atts, $content = null ) {
	return '<div class="large-6 columns">' . parse_shortcode_content( $content ) . '</div></div>';
}

function pkb_one_third_alpha( $atts, $content = null ) {
	return '<div class="row"><div class="large-4 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_one_third( $atts, $content = null ) {
	return '<div class="large-4 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_one_third_omega( $atts, $content = null ) {
	return '<div class="large-4 columns">' . parse_shortcode_content( $content ) . '</div></div>';
}

function pkb_one_fourth_alpha( $atts, $content = null ) {
	return '<div class="row"><div class="large-3 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_one_fourth( $atts, $content = null ) {
	return '<div class="large-3 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_one_fourth_omega( $atts, $content = null ) {
	return '<div class="large-3 columns">' . parse_shortcode_content( $content ) . '</div></div>';
}

function pkb_large_8_alpha( $atts, $content = null ) {
	return '<div class="row"><div class="large-8 columns">' . parse_shortcode_content( $content ) . '</div>';
}

function pkb_large_8_omega( $atts, $content = null ) {
	return '<div class="large-8 columns">' . parse_shortcode_content( $content ) . '</div></div>';
}

function pkb_line() {
	return '<hr/>';
}


/*-----------------------------------------------------------------------------------*/
/*	Notification Shortcodes
/*-----------------------------------------------------------------------------------*/
add_shortcode( 'alert', 'pkb_alert' );

function pkb_alert( $attr, $content = null ) {

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
	$output = '<div class="alert-box' . $type . '"' . $timeout . '>' . apply_filters( 'req_alertbox_content', $content ) . $closebutton . '</div>';

	/* Return the output of the column. */

	return apply_filters( 'req_alertbox', $output );
}

/*-----------------------------------------------------------------------------------*/
/*	Action Button Shortcodes
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'btn_yellow', 'pkb_button_yellow' );
add_shortcode( 'btn_green', 'pkb_button_green' );
add_shortcode( 'btn_teal', 'pkb_button_teal' );
add_shortcode( 'btn_blue', 'pkb_button_blue' );
add_shortcode( 'btn_orange', 'pkb_button_orange' );
add_shortcode( 'btn_red', 'pkb_button_red' );
add_shortcode( 'btn_gray', 'pkb_button_gray' );

add_shortcode( 'btn_arrow_yellow', 'pkb_button_arrow_yellow' );
add_shortcode( 'btn_arrow_green', 'pkb_button_arrow_green' );
add_shortcode( 'btn_arrow_teal', 'pkb_button_arrow_teal' );
add_shortcode( 'btn_arrow_blue', 'pkb_button_arrow_blue' );
add_shortcode( 'btn_arrow_orange', 'pkb_button_arrow_orange' );
add_shortcode( 'btn_arrow_red', 'pkb_button_arrow_red' );
add_shortcode( 'btn_arrow_gray', 'pkb_button_arrow_gray' );

function pkb_button_red( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button red " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_orange( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button orange " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_yellow( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button yellow " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_green( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button green " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_blue( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button blue " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_teal( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button teal " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_gray( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action replace button secondary " . $position . "\">" . do_shortcode( $content ) . "</a>";

	return $out;
}

function pkb_button_arrow_red( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace red " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_orange( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace orange " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_yellow( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace yellow " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_green( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace green " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_blue( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace blue " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_teal( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace teal " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}

function pkb_button_arrow_gray( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'      => '#',
		'target'   => '_self',
		'position' => 'left'
	), $atts ) );
	$out = "<a href=\"" . $url . "\" target=\"" . $target . "\" class=\"button-action button replace secondary " . $position . "\">" . do_shortcode( $content ) . "<i class=\"fontawesome-right-open\"></i></a>";

	return $out;
}


/*-----------------------------------------------------------------------------------*/
/*  Misc Shortcodes
/*-----------------------------------------------------------------------------------*/
add_shortcode( 'checklist', 'pkb_checklist' );
add_shortcode( 'checklist2', 'pkb_checklist2' );
add_shortcode( 'pullquote_left', 'pkb_pullquote_left' );
add_shortcode( 'pullquote_right', 'pkb_pullquote_right' );
add_shortcode( 'quote', 'pkb_blockquote' );

function pkb_checklist( $atts, $content = null ) {
	return '<ul class="checklist">' . parse_shortcode_content( $content ) . '</ul>';
}

function pkb_checklist2( $atts, $content = null ) {
	return '<ul class="checklist2">' . parse_shortcode_content( $content ) . '</ul>';
}

function pkb_pullquote_left( $atts, $content = null ) {
	return '<span class="pullquote_left">' . $content . '</span>';
}

function pkb_pullquote_right( $atts, $content = null ) {
	return '<span class="pullquote_right">' . $content . '</span>';
}

function pkb_blockquote( $atts, $content = null ) {
	return '<blockquote>' . parse_shortcode_content( $content ) . '</blockquote>';
}


?>