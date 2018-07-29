<?php //kitchen.php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if (isset($_COOKIE['user'])) {
	$name = $_COOKIE['user'];
	$user_msg = "Hello, $name";
} else {
	$user_msg = "You must be signed in to order";
}

if ($conn->connect_error) mysqli_ferror($conn);

// Get server stats
$query = "SELECT * FROM orders WHERE DATE(time) = CURDATE()";
$result = $conn->query($query);
	if (!$result) echo "Couldn't get today's orders: " . $conn->error;
$num_orders = $result->num_rows;
$query = "SELECT * FROM menuniv WHERE stock = 1";
$result = $conn->query($query);
	if (!$result) echo "Couldn't get today's stock: " . $conn->error;
$num_items = $result->num_rows;

// Start writing page
echo "<html>";
echo file_get_contents("kitchen_head.html");
echo "<body>";
echo file_get_contents("kitchen_header.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
<h2>The Window</h2>
<h4 id='msg'>$user_msg</h4>
<p>From here you can place orders, browse the menu, and track your order!</p>
<ul id='window_links'>
	<li><a href='make_order.php'>Place an Order</a></li>
	<li><a href='browse_menu.php'>Browse the Menu</a></li>
	<li><a href='feedback.php'>Leave Feedback</a></li>
	<li><a href='items_props.php'>Become a Cook!</a></li>
</ul>
<p>There's currently <b>$num_orders</b> orders for the day!</p>
<p>There's currently <b>$num_items</b> items in stock!</p>
</main>

_END;

echo file_get_contents("footer.html");

$result->close();
$conn->close();

echo "</html>";

?>
