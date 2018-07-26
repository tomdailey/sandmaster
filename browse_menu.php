<?php //kitchen.php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) mysqli_ferror($conn);
// Get number of menu items
$query = "SELECT * FROM menuniv";
$result = $conn->query($query);
if (!$result) echo "Couldn't get the menu: " . $conn->error;
$num_items = $result->num_rows;

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
<h2>Browse the Menu</h2>
<p>We have <b>$num_items</b> menu items!</p>
_END;

echo "<table> <tr>";
// MAKE TABLE HEADING
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
		echo "<td>$row[name]</td>";
	}
	echo "</tr>";
}
echo "</table>";

echo "</main>";
echo file_get_contents("footer.html"); 
$result->close();
$conn->close();

echo "</html>";

?>
