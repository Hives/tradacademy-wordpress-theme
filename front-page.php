<?php
/*
Template Name: Front Page
*/

$new = 1; // triggers new header, <head> element etc.

global $wpdb;

$courses = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'course' AND post_status = 'publish'");

$coming_up = array();
$cutoff = new DateTime();

foreach ($courses as $course) {
    $meta = get_post_meta( $course->ID );
    $location = get_post( $meta['_cmb_location'][0] );
    $location_meta = get_post_meta( $meta['_cmb_location'][0] );

    if (isset($location_meta['_cmb_short_address']) && $location_meta['_cmb_short_address'][0] !== "") {
        $short_address = $location_meta['_cmb_short_address'][0];
    } elseif (isset($location->post_title) && $location->post_title !== "") {
        $short_address = $location->post_title;
    }

    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($course->ID), 'thumbnail');

    $initial_date = intval($meta['_ta_initial_date'][0]);
    // $start_time = intval($meta['_ta_start_time'][0]);
    $duration = intval($meta['_ta_duration'][0]);
    $num_weeks = intval($meta['_ta_num_weeks'][0]);

    $date = new DateTime();
    $date->setTimestamp($initial_date);


    for ($i=0; $i < $num_weeks; $i++) {
        $timestamp = $date->getTimestamp();

        if ($date > $cutoff) {
	        $coming_up[] = array(
	            "url" => get_permalink( $course->ID ),
	            "title" => $course->post_title,
	            "thumbnail" => $thumbnail,
	            "timestamp" => $timestamp,
	            "date" => date( 'D, d M, Y, g:ia', $timestamp ),
	            "location"  => $short_address,
				"excerpt" => wp_trim_words( $course->post_content, 40 ),
	        );
	        break;
        }

        $date->add(new DateInterval("P1W"));
    }

}
usort($coming_up, 'event_sorter');

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

	<section id="coming-up">
		<div class="container">
			<h2 class="text-center">What's coming up?</h2>
			<div class="carousel">

			<?php foreach ($coming_up as $course) { ?>

				<div class="col-md-4 col-sm-6 col-xs-12">
					<a href="<?= $course['url']; ?>">
						<h3><?= $course['title']; ?></h3>
					</a>
					<img src="<?= $course['thumbnail'][0]; ?>">
					<div class="date"><?= $course['date']; ?></div>
					<div class="location"><?= $course['location']; ?></div>
					<div><?= $course['excerpt']; ?></div>
				</div>

			<?php } ?>				

			</div>
			<div class="button col-sm-12 col-md-4 col-md-offset-4 col">View all upcoming courses</div>
		</div>
	</section>

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
