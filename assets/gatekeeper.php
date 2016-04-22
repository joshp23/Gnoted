<?php
session_start();

### OPTIONS

// Enter login/password pairs. 
// Alternatively, set single terms for password only auth.
$LOGIN_INFORMATION = array(
  'username' => 'password'
);

// Set to false for password only auth.
define('USE_USERNAME', true);

// User will be redirected to this page after logout
define('LOGOUT_URL', 'http:example.com/');

// Time out after NN minutes of inactivity. Set to 0 to ignore timeout.
define('TIMEOUT_MINUTES', 30);

// If TIMEOUT_MINUTES is not zero:
// Set to true to record timeout time from last activity, false from login.
define('TIMEOUT_CHECK_ACTIVITY', true);

### FUNCTIONS

// Convert timeout to seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// Logout function
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// Display login form
function showLoginPasswordProtect($error_msg) {
?>
<html>
	<head>
  		<title>Welcome</title>
  		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  		<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
  		<link rel="shortcut icon" href="assets/note.png">
	</head>
	<body>
  		<style>
    	input { border: 1px solid black; }
  		</style>
  		<div style="width:500px; margin-left:auto; margin-right:auto; text-align:center">
  			<form method="post">
    			<h2>Welcome to Noted!</h2>
    			<img style="display:block;margin:0 auto;" src="assets/256x256_noted.png">
    			<h5>Please log in to continue</h5>
    			<font color="red"><?php echo $error_msg; ?></font><br />
				<?php if (USE_USERNAME) echo 'Login:<br /><input type="input" name="access_login" /><br />Password:<br />'; ?>
   			 	<input type="password" name="access_password" /><p></p><input type="submit" name="Submit" value="Submit" />
  			</form>
  			<br />
  			<a style="font-size:9px; color: #B0B0B0; font-family: Verdana, Arial;" href="https://unfettered.net" title="Unfettered">Unfettered</a>
  		</div>
	</body>
</html>

<?php
  // Stop at this point
  die();
}
}

// If user provides credentials
if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect("Credential Error.");
  }
  else {
    // If password validates, set cookie
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
    
    // Clear variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }
}

else {
  // check if password cookie:
  	// is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }
  	// is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // Adjust timeout if TIMEOUT_CHECK_ACTIVITY is set to true
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }
}
?>
