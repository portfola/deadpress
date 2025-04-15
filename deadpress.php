<?php
/**
 * Plugin Name: DeadPress
 * Description: A plugin for Deadheads who use WordPress. Like WordPress, the Grateful Dead represent openness, freedom and creativity.
 * Author: Rindy Portfolio
 * Version: 2.1
 * Tested up to: 6.8
 * Stable tag: 2.1
 * Tags: dashboard, login, art, music, gratefuldead
 * Author URI: https://rindyportfolio.com/deadpress
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 */

// Add CSS
function deadpress_css() { 
    wp_enqueue_style( 'deadpress', plugin_dir_url( __FILE__ ) . 'deadstyles.css' );
}
add_action( 'login_enqueue_scripts', 'deadpress_css' );

// Update link and text for Stealie login
function stealie_login_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'stealie_login_url' );

function stealie_login_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'stealie_login_title' );

// Add Grateful Dead News dashboard widget
function deadnews() {
    add_meta_box( 'deadnews', 'Today in Grateful Dead History &#x1f480', 'deadnews_content', 'dashboard', 'side', 'high' );
}
add_action( 'wp_dashboard_setup', 'deadnews' );

/**
 * Output the content for the Grateful Dead Today in History dashboard widget.
 *
 * Function is a callback for add_meta_box for the dashboard widget.
 *
 * @since 1.0
 */
function deadnews_content() {
    // Get the WordPress timezone setting
    $wp_timezone = wp_timezone();

    // Create a DateTime object with the current time in WordPress timezone
    $datetime = new DateTime('now', $wp_timezone);

    // Format the date to get month and day
    $month = $datetime->format('m');
    $day = $datetime->format('d');
    
    $rss = fetch_feed( "https://archive.org/advancedsearch.php?q=collection%3AGratefulDead+AND+title%3A%22-" . $month . "-" . $day . "%22&fl%5B%5D=identifier&sort%5B%5D=avg_rating+desc&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&callback=callback&save=yes&output=rss" );

     if ( is_wp_error($rss) ) {
          echo '<p>';
          printf( __( '&#x1F6AB; <strong>Error</strong>: Bummer, signal is unclear.' ) );
          echo '</p>';
     return;
    }

    if ( !$rss->get_item_quantity() ) {
         echo '<p>Apparently, no shows can be found!</p>';
         $rss->__destruct();
         unset($rss);
         return;
    }

    echo "<ul>\n";

    // Array to track years we've already displayed
    $displayed_years = array();
    // Maximum number of items to display
    $max_items = 5;
    // Counter for displayed items
    $displayed_count = 0;

    // Get all items from the feed
    $all_items = $rss->get_items();

    foreach ( $all_items as $item ) {
        // Stop if we've reached our maximum
        if ($displayed_count >= $max_items) {
            break;
        }
        
        $link = esc_url( strip_tags( $item->get_link() ) );
        $title = esc_html( $item->get_title() );
        $content = $item->get_content();
        $content = wp_html_excerpt( $content, 250 ) . ' ...';

        // Extract the year from the title using regex
        if (preg_match('/(\d{4})-\d{2}-\d{2}/', $title, $matches)) {
            $year = $matches[1];

            // Check if we've already displayed this year
            if (!in_array($year, $displayed_years)) {
                // Add this year to our tracking array
                $displayed_years[] = $year;
                $displayed_count++;

                // Display the item
                echo "<li><a target='_blank' class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content</div>\n";
            }
        }
    }

    echo "</ul>\n";
    echo '<p><a href="https://archive.org/search.php?query=collection:GratefulDead%20AND%20title:%22-' . $month . '-' . $day . '%22&sort=-date" target="_blank">See full list of shows from this day at Archive.org</a></p>';
    $rss->__destruct();
    unset($rss);
}

// Add Grateful Dead 50 Years Ago dashboard widget
function deadfifty() {
    add_meta_box( 'deadfifty', '50 Years Ago in Grateful Dead History &#x1f339', 'deadfifty_content', 'dashboard', 'side', 'high' );
}
add_action( 'wp_dashboard_setup', 'deadfifty' );

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Output the content for the Grateful Dead 50 Years Ago dashboard widget.
 *
 * Function is a callback for add_meta_box for the dashboard widget.
 *
 * @since 2.0
 */
/******  fcbd5957-af7e-4cf1-b237-b19185d6c160  *******/
function deadfifty_content() {
    $month = date('m');
    $month_name = date('F');
    $year = date('Y')-50;
    $rss_50 = fetch_feed( "https://archive.org/advancedsearch.php?q=collection%3AGratefulDead+AND+title%3A%22" . $year . "-" . $month . "%22&fl%5B%5D=identifier&sort%5B%5D=avg_rating+desc&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&callback=callback&save=yes&output=rss" );

     if ( is_wp_error($rss_50) ) {
          if ( is_admin() || current_user_can( 'manage_options' ) ) {
               echo '<p>';
               printf( __( '&#x1F6AB; <strong>Error</strong>: Bummer, signal is unclear.' ) );
               echo '</p>';
          }
     return;
    }

    if ( !$rss_50->get_item_quantity() ) {
         echo '<p>Apparently, no shows can be found!</p>';
         $rss_50->__destruct();
         unset($rss);
         return;
    }

    echo "<ul>\n";

    // Array to track dates we've already displayed
    $displayed_dates_50 = array();
    // Maximum number of items to display
    $max_items_50 = 5;
    // Counter for displayed items
    $displayed_count_50 = 0;

    // Get all items from the feed
    $all_items_50 = $rss_50->get_items();

    foreach ( $all_items_50 as $item_50 ) {
        // Stop if we've reached our maximum
        if ($displayed_count_50 >= $max_items_50) {
            break;
        }

        $link_50 = esc_url( strip_tags( $item_50->get_link() ) );
        $title_50 = esc_html( $item_50->get_title() );
        $content_50 = $item_50->get_content();
        $content_50 = wp_html_excerpt( $content_50, 250 ) . ' ...';

        // Extract the date from the title using regex
        if (preg_match('/(\d{4}-\d{2}-\d{2})/', $title_50, $matches_50)) {
            $date_50 = $matches_50[1];

            // Check if we've already displayed this date
            if (!in_array($date_50, $displayed_dates_50)) {
                // Add this date to our tracking array
                $displayed_dates_50[] = $date_50;
                $displayed_count_50++;

                // Display the item
                echo "<li><a target='_blank' class='rsswidget' href='$link_50'>$title_50</a>\n<div class='rssSummary'>$content_50</div>\n";
            }
        }
    }

    echo "</ul>\n";
    echo '<p><a href="https://archive.org/search.php?query=collection:GratefulDead%20AND%20title:%22-' . $year . '-' . $month . '%22&sort=-date" target="_blank">See full list of shows from ' . $month_name . ' ' . $year . ' at Archive.org</a></p>';
    $rss_50->__destruct();
    unset($rss_50);
}