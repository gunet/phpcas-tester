<?php

require_once('vendor/autoload.php');
require_once('include/GuestAuth.class.php');
require_once('include/CasAuth.class.php');

if ($_REQUEST['ticket']){
    
    echo "Logged in with ticket: " . $_REQUEST['ticket'];
    // Get the user's attributes
    $attributes = phpCAS::getAttributes();

    // Print all the attributes
    foreach ($attributes as $key => $value) {
        echo "$key: $value<br>";
    }

}
else {
    $casAuth = new CasAuth();
    $user = $casAuth->user();

    // Get the user's attributes
    $attributes = phpCAS::getAttributes();

    // Print all the attributes
    foreach ($attributes as $key => $value) {
        echo "$key: $value<br>";
    }
}
?>

