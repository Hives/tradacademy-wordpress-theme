<?php
/*
Template Name: Tutors
*/
?>
<?php get_header(); ?>

<main class="clearfix">
	<article>
		<?php // First loop displays page content ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; else: ?>
			<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	  	<?php endif; ?>

		<?php // Second loop displays list of tutors ?>
		<?php
		$temp = $wp_query; // assign ordinal query to temp variable for later use

		$args = array(
			'post_type' => 'tutor',
			'post_status' => 'publish',
			'posts_per_page' => 0,
			'ignore_sticky_posts'=> 1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$wp_query = null;
		$wp_query = new WP_Query($args);

		if ( have_posts() ) : while ( have_posts() ) : the_post();
			$counter = $counter == "odd" ? "even" : "odd"; ?>

			<section class="course brief <?php echo $counter; ?>">
				<h3><a href='<?php the_permalink(); ?>' title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<p>
					<a href='<?php the_permalink(); ?>' title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
					</a>
				</p>
				<?php the_excerpt(); ?>
			</section>

		<?php endwhile; endif; ?>

	</article>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>