<?php //kitchen.php
require_once 'library.php';
require_once 'ordering.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) mysqli_ferror($conn);

if( isset($_COOKIE['user'])) $name = $_COOKIE['user'];

// Get number of menu items
$query = "SELECT * FROM menuniv";
$result = $conn->query($query);
if (!$result) echo "Couldn't get the menu: " . $conn->error;
$num_items_total = $result->num_rows;
$query = "SELECT * FROM menuniv WHERE stock=TRUE";
$result = $conn->query($query);
if (!$result) echo "Couldn't get the menu: " . $conn->error;
$num_items = $result->num_rows;

// PROCESS UPDATES from POST
if (isset($_POST['submit'])) {
	$ord_feed = "Updated Stock";
	for($j = 0; $j < $num_items_total; $j++) {
		$query = "UPDATE menuniv SET stock=FALSE WHERE stock = 1 OR 2";
		$result = $conn->query($query);
		if (!$result) echo "Coundn't default the menu: " . $conn->error;
	}
	foreach ($_POST['in_stock'] as $item) {
		$query = "UPDATE menuniv SET stock=TRUE WHERE pid=$item";
		$result = $conn->query($query);
	}
} else {
	$ord_feed = "";
}

//POPULATE THE MENU BY TYPE and STOCK
$types = get_types();
foreach($types as $type)
{
	$query = "SELECT * FROM menuniv WHERE type='$type'";
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
<h2>What's in stock</h2>
<p>We have <b>$num_items</b> items in stock!</p>
<h4 id='msg'>$ord_feed</h4>
<p>Checked items are stocked. Check or uncheck them accordingly. To add an item to the universal menu, go to <a href='items_props.php'>Update the Menu</a>.

<form action='update_stock.php' method='post'> 
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
		if (!empty($row[name]) && isset($_COOKIE['user'])) {
			if( $row[stock] ) {
				echo "<input type='checkbox' 
					name='in_stock[]' value='$row[pid]' checked>";
			} else {
				echo "<input type='checkbox' 
					name='in_stock[]' value='$row[pid]'>";
			}
		}
		echo "</td>";
	}
	echo "</tr>";
}

echo "</table>";

// Display submit only when logged in
if (isset($_COOKIE['user'])) {
	echo "<input type='submit' name='submit' value='Update Menu'></form>";
} else {
	echo "</p><a href='signin.php'>Sign in</a> to make changes to the menu</p>";
}


echo "</main>";
echo file_get_contents("footer.html"); 
echo "</html>";

$result->close();
$conn->close();

?>
