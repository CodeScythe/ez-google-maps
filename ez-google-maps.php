<?php
/**
 * Plugin Name: EZ Google Maps
 * Version: 1.0
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Plugin URI: https://github.com/CodeScythe/ez-goolge-maps
 * Description: Easily create and embed Google Maps into Pages and Posts
 * Author: Nick Ayoola
 * Author URI: https://github.com/CodeScythe/ez-google-maps
 * Requires at least: 4.5.4
 * Tested up to: 4.6.1
 *
 * Text Domain: ez-google-maps
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Nick Ayoola
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// Load plugin class files.
require_once( 'includes/class-ez-google-maps.php' );
require_once( 'includes/class-ez-google-maps-settings.php' );

// Load plugin libraries.
require_once( 'includes/lib/class-ez-google-maps-admin-api.php' );
require_once( 'includes/lib/class-ez-google-maps-post-type.php' );
require_once( 'includes/lib/class-ez-google-maps-taxonomy.php' );

require_once( ABSPATH . 'wp-admin/includes/screen.php' );

/**
 * Returns the main instance of EZ_Google_Maps to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object EZ_Google_Maps
 */
function ez_google_maps() {
	$instance = EZ_Google_Maps::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = EZ_Google_Maps_Settings::instance( $instance );
	}

	return $instance;
}

ez_google_maps();

require_once( 'cmb/custom-meta-boxes.php' );

/**
 * Get a field class by type
 *
 * @param array $meta_boxes plugin meta boxes.
 * @return string $class, or false if not found.
 */
function ez_gm_metaboxes( array $meta_boxes ) {

	$ez_gm_post_types = get_option( 'wpt_ez_gm_post_types' );

	$marker_fields = array(
				array(
					'id' => 'ez-gm-address',
					'name' => 'Address',
					'type' => 'text',
					'cols' => 3,
					),
				array(
					'id' => 'ez-gm-coordinates',
					'name' => 'Coordinates',
					'type' => 'text',
					'cols' => 2,
					),
				array(
					'id' => 'ez-gm-content',
					'name' => 'Content',
					'type' => 'textarea',
					'cols' => 3,
					),
				array(
					'id' => 'ez-gm-map-pin',
					'name' => 'Pin',
					'type' => 'image',
					'show_size' => true,
					'cols' => 4,
					),
			);

	$groups_and_cols = array(
		array(
			'id' => 'ez-gm-map-width',
			'name' => 'Map width',
			'type' => 'number',
			),
		array(
			'id' => 'ez-gm-map-height',
			'name' => 'Map height',
			'type' => 'number',
			),
		array(
			'id' => 'ez-gm-shortcode',
			'name' => 'Map shortcode',
			'desc' => 'Copy and paste into content area',
			'readonly' => true,
			'type' => 'text',
			'default' => '[ez-google-map]',
			),
		array(
			'id' => 'ez-gm-marker',
			'name' => 'Markers',
			'type' => 'group',
			'cols' => 12,
			'repeatable' => true,
			'string-repeat-field' => 'Add New Marker',
			'string-delete-field' => 'Remove Marker',
			'sortable' => true,
			'fields' => $marker_fields,
		),
	);

	$meta_boxes[] = array(
		'title' => 'EZ Google Maps',
		'pages' => $ez_gm_post_types,
		'repeatable' => true,
		'string-repeat-field' => 'true',
		'fields' => $groups_and_cols,
	);

	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'ez_gm_metaboxes' );

/**
 *
 * Adds Google Maps API script to post
 */
function add_script_to_post() {

	$ez_gm_api_key = get_option( 'wpt_ez_gm_api_key' );

	$current_screen = get_current_screen();

	$url = plugins_url( '/ez-google-maps' );

	if ( 'post' == $current_screen->base ) {

		wp_enqueue_script( 'google-map-api', 'https://maps.googleapis.com/maps/api/js?key='. $ez_gm_api_key );

		wp_enqueue_script( 'ez-geocoder', $url . '/assets/js/ez-geocoder.min.js', array( 'google-map-api' ) );

	}
}

add_action( 'current_screen', 'add_script_to_post' );

/**
 *
 * Creates a shortcode to embed
 *
 * @param array $atts shortcode attibutes.
 * @return string $shortcode
 */
function ez_gm_map_tag( $atts ) {

	global $post;

	if ( isset( $atts['post_id'] ) ) {

		$post_id = $atts['post_id'];

	} else {

		$post_id = $post->ID;

	}

	$ez_gm_map_width = get_post_meta( $post_id, 'ez-gm-map-width', true );

	$ez_gm_map_height = get_post_meta( $post_id, 'ez-gm-map-height', true );

	if ( null == $ez_gm_map_width ) {
		$ez_gm_map_width = '320px';
	}

	if ( null == $ez_gm_map_height ) {
		$ez_gm_map_height = '320px' ;
	}

	update_option( '_ez_gm_shortcode_id', $post_id );

	$shortcode = '<div id="ez-google-map" style="width:'. $ez_gm_map_width .'px; height:'. $ez_gm_map_height .'px; max-width: 100%"></div>';

	$ez_gm_map_zoom_level = get_post_meta( $post_id, 'ez-gm-map-zoom-level', true );

	$shortcode .= apply_filters( 'ez_shortcode', $shortcode );

	return $shortcode;

}

add_shortcode( 'ez-google-map' , 'ez_gm_map_tag' );

/**
 *
 * Setup map markers
 *
 * @uses set_google_map()
 */
function ez_gm_set_markers() {

	$ez_shortcode_id = get_option( '_ez_gm_shortcode_id' );

	$ez_gm_map_zoom_level = get_post_meta( $ez_shortcode_id, 'ez-gm-map-zoom-level', true );

	$ez_gm_marker_array = get_post_meta( $ez_shortcode_id, 'ez-gm-marker', false );

	$ez_gm_markers = array();

	foreach ( $ez_gm_marker_array as $ez_gm_marker ) {

		$ez_gm_marker['ez-gm-map-pin'] = wp_get_attachment_url( $ez_gm_marker['ez-gm-map-pin'] );

		$ez_gm_markers[] = $ez_gm_marker;

	}

	set_google_map( $ez_gm_markers, $ez_gm_map_zoom_level );

}

add_filter( 'ez_shortcode' , 'ez_gm_set_markers', 100, 1 );

/**
 * Setup Google Map
 *
 * @param array $markers an array of markers to be placed on map.
 * @param int   $zoom_level map zoom level as integer.
 */
function set_google_map( $markers, $zoom_level ) {

	$ez_gm_api_key = get_option( 'wpt_ez_gm_api_key' );

	wp_enqueue_script( 'google-map-api', 'https://maps.googleapis.com/maps/api/js?key='. $ez_gm_api_key );

	wp_enqueue_script( 'google-map', plugins_url( '/ez-google-maps' ) . '/assets/js/ez-google-map.min.js', array( 'google-map-api' ), '20130115', true );

	$ez_gm_markers = json_encode( $markers );

	$ez_gm_params = array(
		'ez_gm_markers' => $ez_gm_markers,
		'ez_gm_zoom_level' => $zoom_level,
	);

	wp_localize_script( 'google-map', 'php_vars', $ez_gm_params );
}
