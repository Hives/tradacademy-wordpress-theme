<?php

  global $new;

  if ($new) {

/**
 * New Footer
 */

?>
        <footer>
            &copy; Trad Academy 2013
        </footer>
        <?php // wp_footer(); ?>

        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/jquery-3.1.1.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/lib/slick/slick/slick.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/main.js"></script>
        <!-- Google Analytics -->
        <!--
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-77457980-1', 'auto');
          ga('send', 'pageview');

        </script>
        -->
    </body>
</html>

<?php

  } else {

/**
 * Old footer
 */

?>

        <footer>
            &copy; Trad Academy 2013
        </footer>
        <?php wp_footer(); ?>

        <!-- Google Analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-77457980-1', 'auto');
          ga('send', 'pageview');

        </script>

    </body>
</html>

<?php } ?>