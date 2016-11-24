<?php get_header(); ?>

<main class="clearfix">
	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		$meta = get_post_meta( get_the_ID() );
	?>

	<article class="course">
		<header>
			<h1><?php the_title(); ?></h1>
			<div class="course-info">
				<?php print_course_info($meta); ?>
			</div>
			<?php print_social_media_buttons(); ?>
		</header>
		<?php the_content(); ?>
		<section class="location">
			<h3>Location</h3>
			<div class="clearfix">
				<?php print_course_location_map($meta); ?>
			</div>
		</section>
	</article>

	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>