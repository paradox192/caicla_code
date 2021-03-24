<?php
//sorce https://www.wpbeginner.com/wp-themes/how-to-show-different-menus-to-logged-in-users-in-wordpress/

/*
* to change the main menu if user is logged in or not
*/

add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
function my_wp_nav_menu_args( $args = '' ) {
 //print_r($args);
if( is_user_logged_in() ) { 
    $args['menu'] = 'logged-in';
} else { 
    $args['menu'] = 'logged-out';
} 
    return $args;
}