<?php
function print_ord($conn, $ord) {
	$query = "SELECT * FROM menspec WHERE oid='$ord[oid]'";
	$result = $conn->query($query);
		if (!$result) err($query);
	echo<<<_END
	<li>$ord[person]</li>
	<li>id $ord[oid] for $ord[whn]</li>
	<li>with: $result->num_rows ingredients!
_END;
	for ($j = 0; $j < $result->num_rows; $j++) {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQL_ASSOC);
		echo pid_to_item($row[pid]);
	}	
	echo "</li>";
}

function pid_to_item($conn, $pid) {
	$query = "SELECT * FROM menuniv WHERE pid='$pid'";
	$result = $conn->query($query);
		if(!$result) mysql_ferror($query);
	var_dump($result->data_seek('0'));
	$row = $result->fetch_array(MYSQL_ASSOC);
	return $row[name];
}

function is_user($conn, $user) {
	$query = "SELECT * FROM people WHERE name='$user'";
	$result = $conn->query($query);
	if ($result->num_rows > 0){
		return TRUE;
	} else {
		return FALSE;
	}
}
function is_admin($name) {
	return in_array("Tommy", "Pat");
}
function msqli_ferror($conn) 
{
	$msg = mysqli_error($conn);

	echo <<<_END
Unfortunatly something has gone wrong with the requested task.
The error was:
	<p>$msg</p>
Feel free to try again, or email our <a href="mailto:support@iterated.me">support staff</a>. Have a nice day!

_END;
}


function get_post($conn, $var)
{
	return $conn->real_escape_string($_POST[$var]);
}

function is_email($string)
{
return true;
}

function is_prop_type($string)
{
$is = false;
$prop_types = array("substrate", "protein", "dessert", "side", "snack", "fruit", "drink", "vegetable", "condiment", "cheese");
foreach($prop_types as $prop) {
	if($string == $prop) $is = true;	
}
}

function type_list() 
{
$output = "";
$prop_types = get_types();
foreach($prop_types as $type){
	$output = $output . $type . " ";	
}
	return $output;
}

function get_types() 
{
	return array("substrate", "protein", "dessert", "side", "snack", "fruit", "drink", "vegetable", "condiment", "cheese");
}
?>
