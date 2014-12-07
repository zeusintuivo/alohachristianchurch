<?php

//load available produced years from the files in the server
$years = getYears();
//Provide static information
$months = array('january','february','march','april','may','june','july','august','september','october','november','december');
$months_numeric = array('1','2','3','4','5','6','7','8','9','10','11','12');
$months_zero_numeric = array('01','02','03','04','05','06','07','08','09','10','11','12');
//Make a new array that joins the these top two arrays.
$month_translation =  array_combine($months_zero_numeric, $months);
//DEBUG echo " month_translation".print_r($month_translation).'<br />'.PHP_EOL;

