<?php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) mysqli_ferror($conn);

// If already signed in update the variable
if (isset($_COOKIE['user'])) {
	$name = $_COOKIE['user'];
}

// Sign in, if name was submitted and is user
if (isset($_POST['name']) ) {
	if (is_user($conn, get_post($conn, 'name'))) {
		$name = get_post($conn, 'name');
		setcookie('user', $name, time() + 60 * 60 * 24 * 7, '/');
	} else {
		$msg = "Invalid account";
		$name = "";
	}
}
// Sign out, if signout submitted
if (isset($_POST['signout'])) {
	setcookie('user', $name, time() - 2592000, '/');
	$name = "";
}

// Start writing page
echo "<html>";
echo file_get_contents("header.html");
echo "<body>";
echo file_get_contents("head.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
	<h2>Sign In</h2>
	<form action="signin.php" method="post">
	<ul>
_END;
if( !empty($name)) {
	echo <<<_END
		</ul>
		<h4 id='msg'>You're signed in, $name!</h4>
		<input type="submit" name="signout" value="Sign out">
_END;
} else {
	echo <<<_END
		<li>Name:<br> <input type='text' name='name'></li>
		</ul>
		<p>$msg</p>
		<input type="submit" name="submit" value="Sign in">
		<h4>Haven't made an account?</h4>
		<p>Head over to the <a href='signup.php'>sign up page</a></p>
_END;
}
echo <<<_END
	</form>
</main>
_END;

echo file_get_contents("footer.html");

echo "</html>"
?>
