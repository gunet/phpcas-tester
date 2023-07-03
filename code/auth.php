<!DOCTYPE html>
<html lang="en">
<head>
<title>Hello, World!</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container">
<?php

require_once('vendor/autoload.php');
require_once('include/GuestAuth.class.php');
require_once('include/CasAuth.class.php');

$debug = false;
$env_debug = getenv('DEBUG');

if ($env_debug !== false && $env_debug == '1') {
    error_log("Enabling debugging");
    $debug = true;
}
$casAuth = new CasAuth($debug);

if ($casAuth->isAuthenticated()) {?>

<h1>Secured Content</h1>
<p><big>If you are seeing this, you were authenticated sucessfully.</big></p>
<?php
    $user = $casAuth->user();
    echo "SSO Username: <b>$user</b>";
    // Get the user's attributes
    if ($casAuth->hasAttributes()) {
        $attributes = $casAuth->getAttributes();

        echo "<h2>Attributes Returned by CAS</h2>";

        // Print all the attributes
        foreach ($attributes as $key => $value) {
            if (is_array($value))
                echo "$key: <i>(Array)</i> " . json_encode($value) . "<br>";
            else
                echo "$key: $value<br>";
        }
    }
}
else {
    $casAuth->login();
}

?>
</div>
</body>
</html>