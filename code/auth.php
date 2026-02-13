<?php
// Do PHP processing before printing any HTML so that we can return proper headers
// if needed.

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

// Handle back-channel logout requests before anything else
$casAuth->handleLogoutRequests();

if ($casAuth->isAuthenticated()) {
    if (isset($_POST['logout'])) {
        $redirectUrl = $_ENV['CAS_SERVICE_NAME'];
        $casAuth->logout($redirectUrl);
    }
    // Handle MFA re-authentication request
    else if (isset($_POST['mfa'])) {
        // Set MFA parameters
        $loginURL = $casAuth->setMFA();
        // Remove session value for user
        if ($debug)
            error_log("Removing session for re-authentication with MFA");
        unset($_SESSION['phpCAS']['user']);
        // Redirect to the login URL with MFA parameters
        header("Location: $loginURL");
        exit();
    }
}
    ?>
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
        if ($casAuth->isAuthenticated()) {
?>
        <div class="fancy-box">
          <h1 class="fancy-title">Secured Content</h1>
          <p class="description">If you are seeing this, you were authenticated successfully.</p>
          <form method="POST">
              <input type="submit" name="logout" value="Logout" class="fancy-button">
          </form>
          <p></p>
          <form method="POST">
              <input type="submit" name="mfa" value="Re-authenticate with MFA" class="fancy-button">
          </form>
          <br>
          <?php
          $user = $casAuth->user();
          echo "<h3>SSO Username: <b>$user</b></h3>";

          // Get the user's attributes
          if ($casAuth->hasAttributes()) {
              $attributes = $casAuth->getAttributes();
            //   Search for attribute 'authnContextClass' with value 'mfa-gauth'
              if (isset($attributes['authnContextClass']) && $attributes['authnContextClass'] == 'mfa-gauth') {
                  echo "<h4 style='color: green;'>MFA Authentication was used for this session.</h4>";
                // Don't show the submit input for re-authentication with MFA
                  echo "<style>form input[name='mfa'] { display: none; }</style>";
              }

              echo "<h2>Attributes Returned by CAS</h2>";
              echo "<table>";
              echo "<tr><th>Attribute</th><th>Value</th></tr>";

              // Print all the attributes in a table
              foreach ($attributes as $key => $value) {
              if (is_array($value)) {
                  // If the value is an array, convert it to a comma-separated string with brackets around it
                  $value = '[' . implode(', ', $value) . ']';
              }
              if (is_null($value)) {
                  $value = 'NULL';
              }
                  echo "<tr><td>$key</td><td>$value</td></tr>";
              }

              echo "</table>";
          }
      } else {
        // Handle MFA authentication request
        if (isset($_GET['mfa'])) {
            // Set MFA parameters
            $casAuth->setMFA();
        }
        $casAuth->login();
      }

      ?>
      </div>
    </div>
  </body>
  </html>
