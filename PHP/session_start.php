<?php
//to start a session
add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
	  	//$_SESSION['C_Name']='Galactic Empire';
    }
}