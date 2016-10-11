<?php
require("../../config.php");
require("functions.php");
//muutujad
$firstname = "";
$lastname = "";
$address = "";
$city = "";
$zip = "";
$email = "";
$password = "";
$gender = "";

//veateated
$loginError = "";

$firstnameError = "";
$lastnameError = "";
$emailError = "";
$addressError = "";
$zipError = "";
$cityError = "";

$passwordError = "";
$passwordAgainError = "";

if(isset($_POST["gender"])){
	if(!empty($_POST["gender"])){            
		$gender = $_POST["gender"];
	}
}

if( isset($_POST["firstname"]) ){
	//TRUE pärast nupule vajutamist
	if( empty($_POST["firstname"]) ){
		$firstnameError = "Kohustuslik väli";
	} else {
		$firstname = cleanInput($_POST["firstname"]);
	}
	
	if( empty($_POST["lastname"]) ){
		$lastnameError = "Kohustuslik väli";
	} else {
		$lastname = cleanInput($_POST["lastname"]);
	}
	
	if( empty($_POST["address"]) ){
		$addressError = "Kohustuslik väli";
	} else {
		$address = cleanInput($_POST["address"]);
	}
	if( empty($_POST["city"]) ){
		$cityError = "Kohustuslik väli";
	} else {
		$city = cleanInput($_POST["city"]);
	}
	
	if( empty($_POST["zip"]) ){
		$zipError = "Kohustuslik väli";
	} else {
		$zip = cleanInput($_POST["zip"]);
	}
	
	if( empty($_POST["email"]) ){
		$emailError = "Kohustuslik väli";
	} else {
		$email = cleanInput($_POST["email"]);
	}
	
	if( empty($_POST["password"]) ){
		$passwordError = "Kohustuslik väli";
	}else{
		if(strlen($_POST["password"]) < 8){
			$passwordError = "Salasõna peab olema vähemalt 8 tähemärki";
		} else {
			if($_POST["password"] == $_POST["passwordAgain"]){
				$password = cleanInput($_POST["password"]);
				$password = hash("sha512", $password);
			}
		}
	}
	
	if( empty($_POST["passwordAgain"]) ){
		$passwordAgainError = "Kohustuslik väli";
	}else{
		if($_POST["password"] != $_POST["passwordAgain"]){
		$passwordAgainError = "Salasõnad ei kattu";	
		}
	}
} 

// ühtegi errorit
	
if( isset($_POST["firstname"]) &&
	empty($firstnameError) &&
	empty($lastnameError) &&
	empty($passwordError) &&
	empty($passwordAgainError) &&
	empty($emailError) ) {
		
// AJUTINE
	echo "Salvestan... <br>";
	echo "Nimi: ". $firstname . " " . $lastname . "<br>";	
	echo "E-post: ". $email . "<br>";
	echo "Aadress: ". $address . " " . $city . "," . $zip . "<br>";	
	$password = hash("sha512", $_POST["password"]);	
	//echo "password hashed: ". $password . "<br>";
	
	signUp($firstname, $lastname, $email, $password, $address, $city, $zip);
}

/*
******************
         LOGIN   *
******************
*/

if(isset($_POST["loginEmail"]) && isset($_POST["loginPassword"]) &&
!empty($_POST["loginEmail"]) && !empty($_POST["loginPassword"])) {
		
		$loginEmail = cleanInput($_POST["loginEmail"]);
		$loginPassword = cleanInput($_POST["loginPassword"]);
		$loginError = login($loginEmail, $loginPassword);   //kutsun funktsiooni
}
 
 //Kuude massiiv
$m = array("jaanuar","veebruar","märts","aprill","mai","juuni","juuli","august","september","oktoober","november","detsember"); 

//enne 20.kuupäeva tellimus alates järgmisest kuust, muul juhul alates ülejärgmisest kuust
if (date("d") > 20
 ){
	$fromMonth = $untilMonth = date('n', strtotime("+2 Months"));
	$fromYear = $untilYear = date('Y', strtotime("+2 Months"));
}else {
	$fromMonth = $untilMonth = date('n', strtotime("+1 Months"));
	$fromYear = $untilYear = date('Y', strtotime("+1 Months"));
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Ajakirja tellimine</title>
</head>
<body>
<form method="post">
<p style="color:red;"><?php echo $loginError; ?></p>
<input name="loginEmail" type="text" placeholder="E-post">  
<br>
<input name="loginPassword" type="password" placeholder="Salasõna">
<br>
<br>
<input name="login" type="submit" value="Logi sisse">
</form>
<form method="post">
<br>
<br>

<!--Tellimuse periood -->
Alates:
<select name="from">
<?php 
for($i=0;$i<6;$i++) { ?>
	<option value="<?=$fromYear ."-".$fromMonth ."-1";?>"><?php echo $m[$fromMonth - 1]." ".$fromYear;?></option>
<?php	
 if($fromMonth == 12) { 
		$fromMonth = 1; 
        $fromYear++; 
     } else { 
        $fromMonth++; 
     } 
} ?>
</select>
 

<!-- kuni -->
Kuni(k.a):
<select name="until">
<?php 
for($i=0;$i<18;$i++) { ?>
	<option value="<?=$untilYear ."-".$untilMonth ."-30";?>"><?php echo $m[$untilMonth - 1]." ".$untilYear;?></option>
<?php 
	if($untilMonth == 12) { 
		$untilMonth = 1; 
        $untilYear++; 
     } else { 
        $untilMonth++; 
     } 
} ?>
<br>
</select>

<br>
<br>
<!--Kontaktandmed -->
<h3>Tellija andmed</h3>
<form method="post">
<input name="firstname" type="text" placeholder="Eesnimi"> <?php echo $firstnameError; ?> <br>
<input name="lastname" type="text" placeholder="Perekonnanimi"> <?php echo $lastnameError; ?> <br>
<input name="address" type="text" placeholder="Tänav maja nr/ krt"> <?php echo $addressError; ?> <br>
<input name="city" type="text" placeholder="Linn/asula"> <?php echo $cityError; ?> <br>
<input name="zip" type="text" placeholder="Sihtnumber"> <?php echo $zipError; ?> <br>
<input name="email" type="text" placeholder="E-post"> <?php echo $emailError; ?> <br> 
<input name="password" type="password" placeholder="Salasõna"> <?php echo $passwordError; ?> <br>
<input name="passwordAgain" type="password" placeholder="Salasõna uuesti"> <?php echo $passwordAgainError; ?> <br>
<br>
<br>

				   
<!--RADIO -->
<?php if($gender == "female"){ ?>
Naine<input name="gender" type="radio" value="female" checked >

<?php } else { ?>
Naine<input name="gender" type="radio" value="female">

<?php } ?>
<?php if($gender == "male"){ ?>
Mees<input name="gender" type="radio" value="male" checked >

<?php } else { ?>
Mees<input name="gender" type="radio" value="male">

<?php } ?>

<?php if($gender == "" || $gender == "none"){ ?>
Määramata<input name="gender" type="radio" value="none" checked>
<?php } else {?>
Määramata<input name="gender" type="radio" value="none">
<?php } ?>



<br>
<br>
<input type="submit" value="Telli">
</form>
</body>
</html>

