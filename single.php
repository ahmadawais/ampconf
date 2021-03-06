<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AMPConf
 */

get_header(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="wrap">
			<main class="wrap__item wrap__item--full-width">
				<amp-live-list id="ampconf-live-post-<?php the_ID(); ?>" class="live-list" data-poll-interval="<?php echo esc_attr( AMPCONF_LIVE_LIST_POLL_INTERVAL ); ?>" data-max-items-per-page="1">
					<div update class="live-list__button">
						<button class="button" on="tap:ampconf-live-post-<?php the_ID(); ?>.update"><?php esc_html_e( 'Load updates!', 'ampconf' ); ?></button>
					</div>
					<div items>
						<?php get_template_part( 'templates/entry/full' ); ?>
					</div>
				</amp-live-list>
			</main>
		</div>

		<div class="wrap wrap--single">
			<?php get_template_part( 'templates/more-stories' ); ?>

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>
		<?php
	endwhile;
	?>

<?php
get_footer();
