<?php

require("functions.php");
//muutujad
$note = "Telli ajakiri: ";

//veateated
$orderError = "";

//kui id'd ei ole, siis suunatakse sisselogimise lehele
if(!isset ($_SESSION["userId"])){
	header("Location: login.php");
	exit();                      
}

//kui on ?logout aadressireal, siis sessioon lõpetatakse ja suunatakse sisselogimise lehele
if (isset($_GET["logout"])) {
	session_destroy();
	header("Location: login.php");
	exit();
}
//dropdown kuude/aastate valiku tegemiseks lähtusin siit leitud õpetusest:
// http://forums.codewalkers.com/php-coding-7/dynamic-month-year-dropdown-box-997827.html
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

//kontrollin tellimuse perioodi
if(isset($_POST["from"])){
	if(isset($_POST["until"])){
		$orderFrom = date_create($_POST["from"]);
		$orderTo = date_create($_POST["until"]);
	
		if($orderFrom > $orderTo){
			echo "Tellimuse periood ei saa lõppeda varem, kui algab";
		}else{
			//tellimuse perioodi arvutamisel lähtusin sellest õpetusest:  
			// http://stackoverflow.com/questions/2681548/find-month-difference-in-php
			$diff = date_diff($orderFrom, $orderTo);
			//$diff= $diff->format("%m") + 1;
			$diff = (($diff->format('%y') * 12) + $diff->format('%m'));
			$note = "Tellimuse periood kuudes: ";
			$note .= $diff + 1 . ", hind: " . 5* ($diff +1) . "€<br>";  
			$orderFrom = $orderFrom->format("Y-m-d");
			$orderTo = $orderTo->format("Y-m-d");
			$note .= placeOrder($orderFrom, $orderTo);  //funktsioon tellimuse andmebaasi lisamiseks
			
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Sisse loginud</title>
</head>
<body>
<a href="?logout=1">Logi välja</a>

<p>Tere tulemast <?=$_SESSION["userName"];?>!</p><br>

<p><?=$note;?></p>
<!--Tellimuse periood... EI OSKA TEHA NII, ET VALIK PÄRAST SUBMIT VAJUTAMIST NÄHA JÄÄKS-->
<form method="post">
<!--alates -->
alates:
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
&nbsp kuni(k.a):      
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
<input type="submit" value="Telli">
</form>
<!--Olemasolevad tellimused-->
 <p>Sinu tellimused</p>
 <?php
 $userOrders = getData($_SESSION["userId"]);
 
 $html = "<table style='border: 1px solid black';>";
	$html .= "<tr>";
		$html .= "<th style='border: 1px solid black';>Tellimuse nr</th>";
		$html .= "<th style='border: 1px solid black';>Algus</th>";
		$html .= "<th style='border: 1px solid black';>Lõpp</th>";
	$html .= "</tr>";
	
	foreach($userOrders as $o){
		$html .= "<tr >";
		$html .= "<td style='border: 1px solid black';>" . $o->Tellimuse_nr . "</td>";
		$html .= "<td style='border: 1px solid black';>" . $o->Alates . "</td>";
		$html .= "<td style='border: 1px solid black'; >" . $o->Kuni . "</td>"; 
		$html .= "</tr>";
	}
 $html .= "</table>";
 echo $html;
 ?>


</body>
</html>

