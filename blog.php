<?php
/*
Template Name: BLOG
*/
?>
<?php get_header(); ?>

<main>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="blog-post clearfix">
			<header>
				<strong>BLOG</strong>
				<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<div class="blog-meta">
					Posted by <?php the_author(); ?> on <?php the_date(); ?>
				</div>
			</header>
			<?php the_content(); ?>
		</article>
		<hr/ class="blog-divider">
	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
  	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
