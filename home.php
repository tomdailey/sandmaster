<?php
require_once 'library.php';
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) mysqli_ferror($conn);

$result = $conn->query("SELECT * FROM people");
if(!$result) $err_msg= "Coundn't get to people" . $conn->error;

$num_users = $result->num_rows;

// by convention, files include their outermost tags

echo "<html>";
echo file_get_contents("header.html");
echo "<body>";
echo file_get_contents("head.html");
echo file_get_contents("nav.html");
echo <<<_END
<main>
<i id='numusers'>$num_users users!</i>
<h3>Welcome!</h3>
<p>Sand Masters Ltd. is the ultimate solution to all your sandwich logistics needs.</p>
<p>"<i>Sandwich Logistics?!</i>" you scoff incredulously. Well let me tell you, it's more complicated than you think.</p>
<p>Our mission is to remove the hassle on those mornings you just want to get to the beach, and to learn some seriously dope web developement in the process. So hold onto your strapless bikinis, because nothing will stop us at Sand Masters! (if you have a problem with the phrasing there, you can <a href='legal.html'>take it up with legal</a>)</p>
<h3>Get Started!</h3>
<p>It's easy to use! First, <a href="signup.php">make an account</a>, then head over to <a href="window.php">the window</a> to look at the menu, make, and track your orders.</p>
<p>If you'd like to fill an order, update the stock, or otherwise look behind the scenes, head over to <a href="kitchen.php">the kitchen</a>.</p>

</main>
_END;

echo file_get_contents("footer.html");

echo "</html>"
?>
