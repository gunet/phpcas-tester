<?php

require_once('vendor/autoload.php');
require_once('include/GuestAuth.class.php');
require_once('include/CasAuth.class.php');
  
$casAuth = new CasAuth(true);

if ($casAuth->isAuthenticated()) {
    echo "User Authenticated<br>\n";
    // Get the user's attributes
    if ($casAuth->hasAttributes()) {
        $attributes = $casAuth->getAttributes();

        echo "attributes: " . json_encode($attributes);

        // Print all the attributes
        foreach ($attributes as $key => $value) {
            echo "$key: $value<br>";
        }
    }
}
else {
    $casAuth->login();
}
?>

