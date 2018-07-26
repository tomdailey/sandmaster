<?php

function msqli_ferror($conn) 
{
	$msg = mysqli_error($conn);

	echo <<<_END
Unfortunatly somehing has gone wrong with the requested task.
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
return $is;
}

function type_list() 
{
$output = "";
$prop_types = array("substrate", "protein", "dessert", "side", "snack", "fruit", "drink", "vegetable", "condiment", "cheese");
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
