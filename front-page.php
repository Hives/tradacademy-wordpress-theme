<?php
/*
Template Name: Front Page
*/

$new = 1; // triggers new header, <head> element etc.

// dump($coming_up);

?>

<?php get_header(); ?>

<main>

	<section id="mission-statement">
		<div class="container">
			<h2 class="text-center">Trad Academy Mission Statement</h2>
			<div class="statement col-sm-12 col-md-8 col-md-offset-2">The Trad Academy believes that it is possible for everyone to enjoy taking part in making music, singing and dancing. Through teaching people participatory folk music, song and dance the Trad Academy want to empower people to self­-organise, make their own entertainment and change the world.</div>
		</div>
	</section>

	<?php print_whats_coming_up_carousel(); ?>

	<?php /* Commented this out, but could be useful for allowing editable content on front page?
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="blog-post clearfix">
			<header class="visuallyhidden">
				<? // dont show the title on the homepage ?>
				<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</header>
			<?php the_content(); ?>
		</article>
	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>
	*/ ?>

</main>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
