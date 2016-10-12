<?php

require("functions.php");
//muutujad


//veateated
$orderError = "";

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
			//echo $orderFrom ."...".$orderTo;
			$diff = date_diff($orderFrom, $orderTo);
			//$diff= $diff->format("%m") + 1;
			$diff = (($diff->format('%y') * 12) + $diff->format('%m'));
			echo "Tellimuse periood kuudes: ";
			echo $diff + 1 . ", hind: " . 5* ($diff +1) . "€";
			
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

<p>Soovin tellida ajakirja:</p>
<!--Tellimuse periood -->
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
&nbsp kuni(k.a):       <!-- õppisin tühiku panemist :P -->
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


</body>
</html>

