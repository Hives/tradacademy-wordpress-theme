<?php 

$new = 1; // triggers new header, <head> element etc.

?>

<?php get_header(); ?>

<main class="clearfix">
	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		$meta = get_post_meta( get_the_ID() );
	?>

	<article class="course container clearfix">
		<header>
			<h1><?php the_title(); ?></h1>
			<div class="course-info">
				<?php print_course_info($meta); ?>
			</div>
			<?php print_social_media_buttons(); ?>
		</header>
		<section class="course-description">
			<?php the_content(); ?>			
		</section>
		<aside class="location">
			<h3>Location</h3>
			<div class="clearfix">
				<?php print_course_location_map($meta); ?>
			</div>
		</aside>
	</article>

	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>

	<?php print_whats_coming_up_carousel(); ?>
</main>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>