<?php
require("../../config.php");
session_start();
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

function login($email, $password){
	
	$error = "";
	
	$database = "if16_karin";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("SELECT id, Eesnimi, Email, Password FROM users_katse WHERE Email = ?");
	
	echo $mysqli->error;
		
	$stmt->bind_param("s", $email);            //küsimärk asendatakse kasutaja sisestatud emailiga
		
	//määran väärtused muutujatesse
	$stmt->bind_result($id, $nameFromDb, $emailFromDb, $passwordFromDb);
	$stmt->execute();
	
	if($stmt->fetch()){
		
		//oli sellise meiliga kasutaja
		//password millega kasutaja tahab sisse logida
		$hash = hash("sha512", $password);
		if ($hash == $passwordFromDb) {
			echo "Kasutaja logis sisse ".$id;
				
			//määran sessiooni muutujad, millele saan ligi teistelt lehtedelt
			$_SESSION["userId"] = $id;
			$_SESSION["userEmail"] = $emailFromDb;
			$_SESSION["userName"] = $nameFromDb;
			header("Location: data.php");                     
			exit();
		}else {
			$error = "vale parool";
		}
			
			
	} else {
			
		// ei leidnud kasutajat selle meiliga
		$error = "Ei ole sellist emaili";
	}
		
	return $error;
}

//TELLIMUS ANDMEBAASI
function placeOrder($orderFrom, $orderTo){
	$database = "if16_karin";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
	$stmt = $mysqli->prepare("INSERT INTO orders_katse (Alates, Kuni, User_id) VALUES (?, ?, ?)");
	echo $mysqli -> error;   
		
	$stmt -> bind_param("sss", $orderFrom, $orderTo, $_SESSION["userId"] ); 

	if ($stmt->execute()) {
		$note = "<br> Tellimus vastu võetud <br> <br>";
	} else {
		$note = "ERROR ".$stmt->error;
	}
	return $note;	
		
	$stmt->close();
	$mysqli->close();
	
}

//VAATA TELLIMUST
function getData($user_id) {
		
	$database = "if16_karin";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
	$stmt = $mysqli->prepare("SELECT Order_id, Alates, Kuni FROM orders_katse WHERE User_id=?");
	echo $mysqli->error;
	$stmt->bind_param('i' , $user_id);
	$stmt->bind_result($order_idDB, $alatesDB, $kuniDB);
	$stmt->execute();
	
	//tekitan massiivi
	$allUserOrders = array();
	
		while($stmt->fetch()){
			//$alates = date_create($alatesDB)  ... see on  object(DateTime)
			//$alates->format("m/Y")    ....m/Y formaati: 
			
			$alates = date_create($alatesDB)->format("m/Y") ;    
			$kuni = date_create($kuniDB)->format("m/Y");
			
			$order = new StdClass();
			$order->Tellimuse_nr = $order_idDB;
			$order->Alates = $alates;
			$order->Kuni = $kuni;
			array_push($allUserOrders, $order);	
		}
	
	return $allUserOrders;
			
	$stmt->close();
	$mysqli->close();
		
		
}	
?>