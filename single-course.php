<?php 

$new = 1; // triggers new header, <head> element etc.

?>

<?php get_header(); ?>

<main class="clearfix">
	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		$meta = get_post_meta( get_the_ID() );
	?>

	<article class="single-course vertical-section clearfix">
		<header>
			<h1><?php the_title(); ?></h1>
			<div class="course-info details">
				<?php print_course_info($meta); ?>
			</div>
			<?php print_social_media_buttons(); ?>
		</header>
		<section class="course-description two-col-main">
			<?php the_content(); ?>			
		</section>
		<aside class="location two-col-side">
			<div class="container">
				<h3>Where is it?</h3>
				<div class="sidebar-content details clearfix">
					<?php print_course_location_map($meta); ?>
				</div>
			</div>
		</aside>
	</article>

	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>

</main>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>