<?php

try{	
	$connection = connectDB();
	if (isset($_POST['seatingID']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email'])) {
		insertDB();
	} else if (isset($_GET['showSeats'])){
		echo showSeats();
	} else{
		setErrorResponse("oops there is some error");
    }
	mysqli_close($connection);
} catch(Exception $e){
	setErrorResponse("Sometheing went wrong");
}

function setErrorResponse($msg){
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	echo $msg;
	die();
}

function connectDB(){
	$connection = mysqli_connect("localhost", "root", "", "mt_cinema");
	if (mysqli_connect_errno()){
		setErrorResponse('Connection error');
	}
	return $connection;
}

function insertDB(){
	global $connection;
	$firstName = $connection->real_escape_string($_POST['firstName']);
	$lastName = $connection->real_escape_string($_POST['lastName']);
	$email = $connection->real_escape_string($_POST['email']);
	$seatingID = $connection->real_escape_string($_POST['seatingID']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		setErrorResponse("Invalid email");
	}
	$query = mysqli_query($connection, "INSERT INTO mt_userdata(firstname, lastname, email, seatingID) VALUES ('$firstName', '$lastName', '$email', '$seatingID')");
	if(!$query){
		setErrorResponse("Insertion error");
	}
	echo "Form submitted sucsessfuly";
	return $query;
}

function showSeats(){
	global $connection;
	$query = mysqli_query($connection, "SELECT seatingID FROM mt_userdata");
		$out = array();
		if ($query->num_rows > 0){
			while($row = $query->fetch_assoc()) {
				array_push($out, $row['seatingID']);
			}
		}
		return json_encode($out);
}

?>