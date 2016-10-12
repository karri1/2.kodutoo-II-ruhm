<?php
require("../../config.php");

function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
function signUp($firstname, $lastname,  $gender, $address, $city, $zip, $email, $password){
	$database = "if16_karin";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
	$stmt = $mysqli->prepare("INSERT INTO users_katse (Eesnimi, Perekonnanimi, Sugu, Aadress, Asula, Sihtnumber, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	echo $mysqli -> error;   
		
	$stmt -> bind_param("ssssssss", $firstname, $lastname, $gender, $address, $city, $zip, $email, $password ); 

	if ($stmt->execute()) {
		echo "<br> Kasutaja loodud<br> <br>";
		echo "Nimi: ". $firstname . " " . $lastname . "<br>";	
		echo "E-post: ". $email . "<br>";
		echo "Aadress: ". $address . " " . $city . ", " . $zip . "<br>";
	} else {
		echo "ERROR ".$stmt->error;
	}
			
	$stmt->close();
	$mysqli->close();
}

function login($loginEmail, $loginPassword){
	$loginError = "";
//ÜHENDUS
		
}


	
?>