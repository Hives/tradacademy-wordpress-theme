<?php 

require_once( explode( "wp-content" , __FILE__ )[0] . "wp-load.php" );

get_header();

$page = $_GET['page'];

?>

<main>
<article class="clearfix vertical-section">
<section class="">
<h2>°º¤ø,¸¸,ø¤º°`°º¤ø,¸ ( ?page=<?= $page; ?> ) °º¤ø,¸¸,ø¤º°`°º¤ø,¸</h2>
<pre>
<?php require_once($page . '.php'); ?>
</pre>
</section>
</article>
</main>