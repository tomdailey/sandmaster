<?php
class Order {
	public $status, $when, $cook, $person, $oid;

function Order($pd, $wn)  {
	$this->person = $pd;
	$this->when = $wn;
}
function whos($oid) {
	$query = "SELECT FROM orders WHERE oid='$this->oid'";
	$result = $conn->query($query);
	if($result === TRUE) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		return $row[name];
	} else {
		err($query);
		return NULL;
	}
}
function id() {
	return $this->oid;
}
function submit($conn, $contents) {

	//ADD ORDER TO DATABASE
	$query = "INSERT INTO orders(person, whn) 
				VALUES('$this->person', '$this->when')";
	$result = $conn->query($query);
	if($result === TRUE) {
		$this->oid = $conn->insert_id;
		//ASSOCIATE ORDER WITH CONTENTS
		foreach($contents as $item){
			$query = "INSERT INTO menspec(oid, pid)". 
						" VALUES('$this->oid', '$item')";
			if(!($result = $conn->query($query)) ) err($query);
		}
		
	} else {
		err($query);
		echo "   Order NOT CREATED!";
	}
	
}
}

function err($string) {
	echo "Error processing: $string";
}

?>
