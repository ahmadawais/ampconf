<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AMPConf
 */

if ( ! function_exists( 'ampconf_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function ampconf_posted_on() {
		$time_string = '<time class="entry__date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry__date published" datetime="%1$s">%2$s</time><time class="screen-reader-text updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			wp_kses(
				/* translators: %s: post date. */
				_x( '<span class="screen-reader-text">Posted on</span> %s', 'post date', 'ampconf' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'ampconf_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ampconf_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'ampconf' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ampconf' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ampconf' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ampconf' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ampconf' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'ampconf' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'ampconf_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 */
	function ampconf_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		// TODO: Create custom markup for ampconf's specific image sizes.
	}
endif;

if ( ! function_exists( 'ampconf_branding_tag' ) ) :
	/**
	 * Outputs the branding in header as h1 or p, depending on the current view.
	 */
	function ampconf_branding_tag() {
		echo esc_html( ( is_front_page() && is_home() ) ? 'h1' : 'p' );
	}
endif;

/**
 * Render attributes for a post's article, including id, class, and amp-live-list attributes.
 *
 * @see post_class()
 * @see the_ID()
 *
 * @param array $attributes Attributes to output.
 */
function ampconf_the_post_attributes( $attributes = array() ) {
	$attributes = wp_parse_args( $attributes, array(
		'id'               => sprintf( 'post-%d', get_the_ID() ),
		'class'            => '',
		'data-sort-time'   => get_the_date( 'U' ),
		'data-update-time' => get_the_modified_time( 'U' ),
	) );

	// Fill out the classes.
	$attributes['class'] = implode( ' ', get_post_class( $attributes['class'] ) );

	foreach ( $attributes as $name => $value ) {
		printf( ' %s="%s"', $name, esc_attr( $value ) ); // WPCS: XSS OK.
	}
}
