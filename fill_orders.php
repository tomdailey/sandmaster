<?php //kitchen.php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) mysqli_ferror($conn);

// GET Kitchen STATS
$query = "SELECT * FROM menuniv WHERE stock = 1";
$result = $conn->query($query);
	if (!$result) echo "Couldn't get today's stock: " . $conn->error;
$num_items = $result->num_rows;

$query = "SELECT * FROM orders WHERE DATE(time) = CURDATE() AND status=FALSE";
$open_ord = $conn->query($query);
	if (!$open_ord) echo "Couldn't get today's orders: " . $conn->error;
$num_orders = $open_ord->num_rows;

// UPDATE SUBMITTED ORDERS
if(isset($_POST['submit'])) {
	$filled_oid = $_POST['id'];
	$query = "UPDATE orders SET status=TRUE WHERE oid='$filled_oid'";
	$result = $conn->query($query);
	if (!$result) echo "Coundn't default the menu: " . $conn->error;

}

// BEGIN PAGE
echo "<html>";
echo file_get_contents("kitchen_head.html");
echo "<body>";
echo file_get_contents("kitchen_header.html");
echo file_get_contents("nav.html");	
$thing = pid_to_item($conn, '4');
var_dump($thing);

echo <<<_END
<main>
<h2>Fill Orders</h2>
<p>There's currently <b>$num_orders</b> unfilled orders</p>
<form action="fill_orders.php" method="post">
_END;

for($j = 0; $j < $num_orders; $j++) {
	$open_ord->data_seek($j);
	$ord = $open_ord->fetch_array(MYSQLI_ASSOC);
	print_ord($conn, $ord);
	
	echo "<input type='hidden' 
		name='id' value='$ord[id]'>";
	echo "<input type='submit' 
		name='submit' value='Order Filled'>";
}

echo "</main>";

echo file_get_contents("footer.html");

$result->close();
$conn->close();

echo "</html>";

?>
