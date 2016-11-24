<?php
/**
*   -----------------UTILITIES----------------------
*/


function dump($v) {
    echo "<pre class='dumped'>" . print_r($v, true) . "</pre>";
}

function timestamp_to_date($s) {
    dump( date('l jS \of F Y h:i:s A', $s) );
}

function get_current_courses($var) {
    $meta = get_post_meta( $var->ID, $key = '', $single = false );
    $initial_date = $meta['_ta_initial_date'][0];
    $num_weeks = $meta['_ta_num_weeks'][0];
    $final_date = strtotime("+" . ($num_weeks - 1) . " weeks", $initial_date);

    if ( $final_date > time() ) {
        return true;
    } else {
        return false;
    }

}

function event_sorter($a, $b)
{
    if ($a['timestamp'] == $b['timestamp']) {
        return 0;
    }
    return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
}

function buildMenuTree( $menu_items, $parentId = 0, $level = 0 )
{
    $branch = array();
    foreach ( $menu_items as $item )
    {
        $item['level'] = $level;
        if ( $item['parent'] == $parentId )
        {
            $children = buildMenuTree( $menu_items, $item['ID'], $level + 1 );
            if ( $children ) { 
                $item['sub'] = $children;
            }

            $branch[$item['ID']] = $item;
        }
    }
    return $branch;
}

function print_menu_recursive($menu_tree, $level = 0)
{
    $indent = 2 * $level;

    echo "\n";

    echo str_repeat("   ", $indent);
    echo "<ul>\n";

    foreach ( $menu_tree as $item ) {

        if(empty($item['sub'])){

            if ($item['type'] == 'divider') {
                echo str_repeat("   ", $indent + 1);
                echo "<li role='separator' class='divider'></li>\n";
            } elseif ($item['type'] == "course") {

                echo str_repeat("   ", $indent + 1);
                echo "<li>\n";

                echo str_repeat("   ", $indent + 2);
                echo "<a href='" . $item['permalink'] . "'>";
                echo $item['title'];
                echo "<br>";
                echo "<span class='course-dates'>" . $item['dates'] . "</span>";
                echo "</a>\n";

                echo str_repeat("   ", $indent + 1);
                echo "</li>\n";

            } else {

                echo str_repeat("   ", $indent + 1);
                echo "<li>\n";

                echo str_repeat("   ", $indent + 2);
                echo "<a href='" . $item['permalink'] . "'>";
                echo $item['title'];
                echo "</a>\n";

                echo str_repeat("   ", $indent + 1);
                echo "</li>\n";

            }

        } else {

            echo str_repeat("   ", $indent + 1);
            echo "<li>\n";

            echo str_repeat("   ", $indent + 2);
            echo "<a href='" . $item['permalink'] . "'>";
            echo $item['title'];
            if ($level == 0) {
                echo " <span class='arrow'>&#x25BE;</span>";
            } else {
                echo "<span class='arrow'>&#x25B8;</span>";
            }
            echo "</a>";

            print_menu_recursive($item['sub'], $level + 1);

            echo str_repeat("   ", $indent + 1);
            echo "</li>\n";

        }

    }

    echo str_repeat("   ", $indent) . "</ul>\n";

}