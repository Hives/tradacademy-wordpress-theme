<?php 
$courses=get_forthcoming_courses_and_metadata();
foreach ($courses as $course) {
	$t = get_the_post_thumbnail( $course->ID, 'small' );
	dump($t);
}
?>