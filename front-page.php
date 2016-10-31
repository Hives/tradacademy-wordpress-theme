<?php
/*
Template Name: Front Page
*/

$new = 1; // triggers new header, <head> element etc.

?>

<?php get_header(); ?>

<main>

	<?php /* Commented this out, but could be useful for allowing editable content on front page? */ ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<section id="homepage-content">
			<article class="clearfix container">
				<header class="visuallyhidden">
					<? // dont show the title on the homepage ?>
					<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				</header>
				<?php the_content(); ?>
			</article>			
		</section>
	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>

	<?php print_whats_coming_up_carousel(); ?>

</main>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
