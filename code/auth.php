<!DOCTYPE html>
<html lang="en">
<head>
  <title>CAS Authentication Tester</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/main.css"> 

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

    if ($casAuth->isAuthenticated()) {
        if (isset($_POST['logout'])) {
            $redirectUrl = $_ENV['CAS_SERVICE_NAME'];
            $casAuth->logout($redirectUrl);
        }
        ?>
        <div class="fancy-box">
          <h1 class="fancy-title">Secured Content</h1>
          <p class="description">If you are seeing this, you were authenticated successfully.</p>
          <form method="POST">
              <input type="submit" name="logout" value="Logout" class="fancy-button">
          </form>
          <br>
          <?php
          $user = $casAuth->user();
          echo "<h3>SSO Username: <b>$user</b></h3>";

          // Get the user's attributes
          if ($casAuth->hasAttributes()) {
              $attributes = $casAuth->getAttributes();

              echo "<h2>Attributes Returned by CAS</h2>";
              echo "<table>";
              echo "<tr><th>Attribute</th><th>Value</th></tr>";

              // Print all the attributes in a table
              foreach ($attributes as $key => $value) {
                  echo "<tr><td>$key</td><td>$value</td></tr>";
              }

              echo "</table>";
          }
      } else {
          $casAuth->login();
      }

      ?>
      </div>
    </div>
  </body>
  </html>
