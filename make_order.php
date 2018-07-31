<?php //kitchen.php
require_once 'library.php';
require_once 'ordering.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) mysqli_ferror($conn);

if( isset($_COOKIE['user'])) $name = $_COOKIE['user'];

// Get number of menu items
$query = "SELECT * FROM menuniv WHERE stock=TRUE";
$result = $conn->query($query);
if (!$result) echo "Couldn't get the menu: " . $conn->error;
$num_items = $result->num_rows;

// PROCESS ORDERS from POST
if (isset($_POST['submit'])) {
if (!empty($_POST['contents']) ) {
	$active = new Order($name, date('Y-m-d', 
			time() + 60*60*24*$_POST['when'] ) );
	$active->submit($conn, $_POST['contents']);
	$ord_feed = "Submitted order " . $active->id();
} else {
	$ord_feed = "Did you order anything?";
}
} else {
	$ord_feed = "";
}

//POPULATE THE MENU BY TYPE
$types = get_types();
foreach($types as $type)
{
	$query = "SELECT * FROM menuniv WHERE type='$type' AND stock=TRUE";
	$menu[$type] = $conn->query($query);
	if (!$menu[$type]) echo "Couldn't get the menu for $type: " . $conn->error;
}

// FIND MAX LENGTH OF TYPE COLUMNS
$longest = 0;
foreach($types as $type)
{
	$length = $menu[$type]->num_rows;
	if($length > $longest) $longest = $length;
}


echo "<html>";
echo file_get_contents("kitchen_head.html");
echo "<body>";
echo file_get_contents("kitchen_header.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
<h2>Make Your Order!</h2>
<p>We have <b>$num_items</b> menu items!</p>
<h4 id='msg'>$ord_feed</h4>

<form action='make_order.php' method='post'> 
<table> 
<tr>
_END;

// MAKE COLUMN TITLES
foreach($types as $type) {
	echo "<th>$type"."s</th>";
}
echo "</tr>";

// MAKE TABLE ROWS
for($j = 0 ; $j < $longest; ++$j) {
	echo "<tr>";
	foreach($types as $type) {
		$menu[$type]->data_seek($j);
		$row = $menu[$type]->fetch_array(MYSQLI_ASSOC);
		echo "<td>$row[name]";
		if (!empty($row['name'])) echo "<input type='checkbox' name='contents[]' value='$row[pid]'>";
		echo "</td>";
	}
	echo "</tr>";
}

// Date input
echo<<<_END
	<tr>
		<td><input type='checkbox' name='when' 
				value='0' label='today'>today</td>
		<td><input type='checkbox' name='when' 
				value='1' label='tomorrow'>tomorrow</td>
	</tr>
</table>
_END;

// Display submit only when logged in
if (isset($_COOKIE['user'])) {
	echo "<input type='submit' name='submit' value='Place Order'></form>";
} else {
	echo "</p><a href='signin.php'>Sign in</a> to order</p>";
}


echo "</main>";
echo file_get_contents("footer.html"); 
echo "</html>";

$result->close();
$conn->close();

?>
