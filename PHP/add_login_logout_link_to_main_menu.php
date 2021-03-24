<?php
// sorce https://pastebin.com/ZTGVHPqw
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) { 
	/*
    *If login in button is not in main menu uncoment print_r and seartch for  [theme_location] key and 
    *change the  if statment so $args->theme_location == 'value of [theme_location]'
    */
    //print_r($args);
	if ( $args->theme_location == 'primary' ) {	

        ob_start();

        wp_loginout('index.php');

        $loginoutlink = ob_get_contents();
		print_r($loginoutlink);
        ob_end_clean(); 

        $items .= '<li>'. $loginoutlink .'</li>';
    }

    return $items;

}
?>