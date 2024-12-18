<?php

$ODvalue = $OD->getTotal();
$zdValue = "";
if($ODvalue < 200){
	$zdValue = "_200";
}else if($ODvalue < 500){
	$zdValue = "200_to_500";
}else if($ODvalue < 1000){
	$zdValue = "500_to_1000";
}else if($ODvalue < 5000){
	$zdValue = "1000_to_5000";
}else{
	$zdValue = "5000_";
}

$zdAgeing = "";
if($Ageing >0 && $Ageing<=10){
	$zdAgeing = $Ageing;
}else if( $Ageing<15){
	$zdAgeing = "10_";
}else if( $Ageing<20){
	$zdAgeing = "15_";
}else if( $Ageing>=20){
	$zdAgeing = "20_";
}
?>