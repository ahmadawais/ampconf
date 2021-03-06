<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AMPConf
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array Classes.
 */
function ampconf_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'ampconf_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function ampconf_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'ampconf_pingback_header' );

/**
 * Filters the string displayed after the excerpt.
 *
 * @return string $more_string More text.
 */
function ampconf_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'ampconf_excerpt_more' );

/**
 * Filters the archive title and wraps the type of archive in a span element.
 *
 * @param string $title Archive title to be displayed.
 * @return string $title Title.
 */
function ampconf_get_the_archive_title( $title ) {
	$parts = explode( ':', $title );

	if ( 2 <= count( $parts ) ) {
		$title = str_replace( $parts[0] . ': ', '', $title );

		$title = wp_kses(
			sprintf(
				'<span>%1$s</span>%2$s',
				$parts[0],
				$title
			),
			array(
				'span' => array(),
			)
		);
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'ampconf_get_the_archive_title' );

/**
 * Filter the featured image for AMP.
 *
 * There is a problem with the AMP sanitizer that is not properly converting img into amp-img,
 * so this is a workaround to preempt the sanitizer.
 *
 * @see get_the_post_thumbnail()
 *
 * @param string $html The post thumbnail HTML.
 * @return string Amplified HTML.
 */
function ampconf_filter_post_thumbnail_html( $html ) {
	if ( is_amp_endpoint() ) {
		$html = str_replace( '<img ', '<amp-img ', $html ) . '</amp-img>';
	}
	return $html;
}
if ( function_exists( 'is_amp_endpoint' ) ) {
	add_filter( 'post_thumbnail_html', 'ampconf_filter_post_thumbnail_html' );
}

/**
 * Filter the custom logo image for AMP.
 *
 * There is a problem with the AMP sanitizer that is not properly converting img into amp-img,
 * so this is a workaround to preempt the sanitizer.
 *
 * @see get_custom_logo()
 *
 * @param string $html The custom logo HTML.
 * @return string Amplified HTML.
 */
function ampconf_filter_get_custom_logo( $html ) {
	if ( is_amp_endpoint() ) {
		$html = str_replace( '<img ', '<amp-img ', $html ) . '</amp-img>';
	}
	return $html;
}
if ( function_exists( 'is_amp_endpoint' ) ) {
	add_filter( 'get_custom_logo', 'ampconf_filter_get_custom_logo' );
}
