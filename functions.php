<?php
function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
function signUp($firstname, $lastname, $email, $password, $gender, $newsletter){
	
//ÜHENDUS

}

function login($loginEmail, $loginPassword){
	$loginError = "";
//ÜHENDUS
		
}


	
?>