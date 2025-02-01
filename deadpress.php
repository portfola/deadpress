<?php
/**
 * Plugin Name: DeadPress
 * Description: A plugin for Deadheads who use WordPress. Like WordPress, the Grateful Dead represent openness, freedom and creativity.
 * Author: Rindy Portfolio
 * Version: 2.0.2
 * Tested up to: 6.7.1
 * Stable tag: 2.0.2
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
    $month = date('m');
    $day = date('d');
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

    if ( !isset($items) )
         $items = 5;

         foreach ( $rss->get_items( 0, $items ) as $item ) {
              $publisher = '';
              $site_link = '';
              $link = '';
              $content = '';
              $date = '';
              $link = esc_url( strip_tags( $item->get_link() ) );
              $title = esc_html( $item->get_title() );
              $content = $item->get_content();
              $content = wp_html_excerpt( $content, 250 ) . ' ...';

             echo "<li><a target='_blank' class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content</div>\n";
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
    $rss = fetch_feed( "https://archive.org/advancedsearch.php?q=collection%3AGratefulDead+AND+title%3A%22" . $year . "-" . $month . "%22&fl%5B%5D=identifier&sort%5B%5D=avg_rating+desc&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&callback=callback&save=yes&output=rss" );

     if ( is_wp_error($rss) ) {
          if ( is_admin() || current_user_can( 'manage_options' ) ) {
               echo '<p>';
               printf( __( '&#x1F6AB; <strong>Error</strong>: Bummer, signal is unclear.' ) );
               echo '</p>';
          }
     return;
    }

    if ( !$rss->get_item_quantity() ) {
         echo '<p>Apparently, no shows can be found!</p>';
         $rss->__destruct();
         unset($rss);
         return;
    }

    echo "<ul>\n";

    if ( !isset($items) )
         $items = 5;

         foreach ( $rss->get_items( 0, $items ) as $item ) {
              $publisher = '';
              $site_link = '';
              $link = '';
              $content = '';
              $date = '';
              $link = esc_url( strip_tags( $item->get_link() ) );
              $title = esc_html( $item->get_title() );
              $content = $item->get_content();
              $content = wp_html_excerpt( $content, 250 ) . ' ...';

             echo "<li><a target='_blank' class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content</div>\n";
    }

    echo "</ul>\n";
    echo '<p><a href="https://archive.org/search.php?query=collection:GratefulDead%20AND%20title:%22-' . $year . '-' . $month . '%22&sort=-date" target="_blank">See full list of shows from ' . $month_name . ' ' . $year . ' at Archive.org</a></p>';
    $rss->__destruct();
    unset($rss);
}