<?php get_header(); ?>

<main class="clearfix">
	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		$meta = get_post_meta( get_the_ID() );
	?>

	<article class="course">
		<header>
			<h1><?php the_title(); ?></h1>
		</header>
		<?php the_content(); ?>
	</article>

	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>
</main>

<?php /*
<main class="clearfix">
	<article>
		<?php // Second loop displays list of tutors ?>
		<?php
		$temp = $wp_query; // assign ordinal query to temp variable for later use

		$type = 'tutor';
		$args = array(
			'post_type' => $type,
			'post_status' => 'publish',
			'paged' => $paged,
			'posts_per_page' => 0,
			'ignore_sticky_posts'=> 1
		);
		$wp_query = null;
		$wp_query = new WP_Query($args);
		?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<section>
				<!-- <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2> -->
				<h2><?php the_title(); ?></h2>
				<?php the_content(); ?>
			</section>
		<?php endwhile; else: ?>
			<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	  	<?php endif; ?>
	</article>

</main>
*/ ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>