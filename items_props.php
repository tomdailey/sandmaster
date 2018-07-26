<?php //kitchen.php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) mysqli_ferror($conn);

//create new prop
if (!empty($_POST['name']) && 
	!empty($_POST['type']) && 
	is_prop_type($_POST['type']))
{
	$name = 		get_post($conn, 'name');
	$type =			get_post($conn, 'type');
	$query = "INSERT INTO menuniv(name, type) VALUES('$name', '$type')";
	$result = $conn->query($query);
	if (!$result) {
		$msg = "Query failed: $query <br> $conn->error <br>";
	} else {
		$msg = "Added $name to the $type menu.";
	}
} elseif( isset($_POST['try']) && !is_prop_type($_POST['type'] )) {
	$msg = "That's not a valid type!";
} elseif( isset($_POST['try'])) {
	$msg = "Invalid form";
} 

// delete property from the menu
if (ISSET($_POST['delete']) && ISSET($_POST['del_id']) 
							&& ISSET($_POST['del_name'])) {
	$del_id = get_post($conn, 'del_id');
	$del_name = get_post($conn, 'del_name');
	$query = "DELETE FROM menuniv WHERE id='$del_id'";
	$result = $conn->query($query);
	if(!$result){
		$del_msg = "Coundn't delete item. Sorry";
	} else {
		$del_msg = "Deleted $del_id, $del_name.";
	}
} else {
	$del_msg = "";
}

echo "<html>";

echo file_get_contents("kitchen_head.html");

echo "<body>";
echo file_get_contents("kitchen_header.html");
echo file_get_contents("nav.html");
// ADD ITEMS TO MENUNIV
// get a list of the possible types from library.php
$types = type_list();
	echo<<<_END
<main>
	<h3>New Ingredients/Properties</h3>
	<form action="items_props.php" method="post">
	<ul>
		<li>Name of item/property: <br><input type="text" name="name"></li>
		<li>Type:<br> <input type="text" name="type"><br></li>
		<p>The types are:  $types</p>
	</ul>
		<p>$msg</p>
		<input type="submit" name="try" value="Add">
	</form>
_END;
echo <<<_END
	<h2>Item/Property List</h2>
	<p>$del_msg</p>
_END;

$result = $conn->query("SELECT * FROM menuniv");
if (!$result) echo "Couldn't get the menu";

$rows = $result->num_rows;
for($j = 0 ; $j < $rows; ++$j)
{
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	echo <<<_END
	<pre>
Name: $row[name]
Type: $row[type]
Prop ID: $row[pid]
	</pre>
	<form action="items_props.php" method="post">
		<input type="hidden" name="delete" value="yes">
		<input type="hidden" name="del_id" value="$row[pid]">
		<input type="hidden" name="del_name" value="$row[name]">
		<input type="submit" value="DELETE">
	</form>
_END;
}
echo "</main>";
echo file_get_contents("footer.html");

$result->close();
$conn->close();

echo "</html>";

?>
