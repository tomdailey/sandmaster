<?php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) mysqli_ferror($conn);

//create new user
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

// delete user
if (ISSET($_POST['delete']) && ISSET($_POST['del_id']) 
							&& ISSET($_POST['del_name'])) {
	$del_id = get_post($conn, 'del_id');
	$del_name = get_post($conn, 'del_name');
	$query = "DELETE FROM people WHERE id='$del_id'";
	$result = $conn->query($query);
	if(!$result){
		$del_msg = "Coundn't Delete entry. Sorry";
	} else {
		$del_msg = "Deleted user $del_id, $del_name. May god rest his or her soul.";
	}
} else {
	$del_msg = "";
}

echo "<html>";

echo file_get_contents("admin_head.html");

echo "<body>";
echo file_get_contents("head.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
	<h2>Member List</h2>
	<p>$del_msg</p>
_END;

$result = $conn->query("SELECT * FROM people");
if (!$result) echo "Couldn't get data. Sorry";

$rows = $result->num_rows;
for($j = 0 ; $j < $rows; ++$j)
{
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	echo <<<_END
	<pre>
Name: $row[name]
Age: $row[age]
Restrictions: $row[restrictions]
Email: $row[email]
ID: $row[id]
	</pre>
	<form action="admin.php" method="post">
		<input type="hidden" name="delete" value="yes">
		<input type="hidden" name="del_id" value="$row[id]">
		<input type="hidden" name="del_name" value="$row[name]">
		<input type="submit" value="DELETE USER">
	</form>
_END;
}
	echo<<<_END
	<h2>New Member Signup</h2>
	<form action="admin.php" method="post">
	<ul>
		<li>Name: <br><input type="text" name="name"></li>
		<li>Age:<br> <input type="text" name="age"></li>
		<li>Dietary Restrictions:<br> <input type="text" name="restrictions" value="None"></li>
		<li>Email:<br> <input type="text" name="email"></li>
	</ul>
		<p>$msg</p>
		<input type="submit" name="try" value="Sign up">
	</form>
</main>
_END;

echo file_get_contents("footer.html");

$result->close();
$conn->close();

echo "</html>";

?>
