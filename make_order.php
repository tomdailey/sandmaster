<?php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) mysqli_ferror($conn);

if(isset($_POST['try'])) $msg = "Invalid form";
if (!empty($_POST['name']) && 
	!empty($_POST['age']) && 
	!empty($_POST['restrictions']) && is_email($_POST['email']) ) 
{
	$name = 		get_post($conn, 'name');
	$age = 			get_post($conn, 'age');
	$restrictions = get_post($conn, 'restrictions');
	$email = 		get_post($conn, 'email');
	$query = "INSERT INTO people(name, age, restrictions, email) VALUES" .
			"('$name', '$age', '$restrictions', '$email')";
	$result = $conn->query($query);
	if (!$result) {
		$msg = "Query failed: $query <br> $conn->error <br>";
	} else {
		$msg = "Welcome, $name!";
	}
} elseif( !is_email($_POST['email'] )) {
	$msg = "That's not a valid email";
}

// by convention, files include their outermost tags
echo "<html>";
echo file_get_contents("header.html");
echo "<body>";
echo file_get_contents("head.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
	<h2>Place an Order</h2>
	<form action="make_order.php" method="post">
	<ul>
		<li>Your Name:<br> <input type="text" name="name"></li>
		<li>:<br> <input type="text" name="age"></li>
		<li>Dietary Restrictions:<br> <input type="text" name="restrictions" value="None"></li>
		<li>Email:<br> <input type="text" name="email"></li>
	</ul>
		<p>$msg</p>
		<input type="submit" name="try" value="Sign up">
	</form>
</main>

_END;

echo file_get_contents("footer.html");

echo "</html>"
?>
