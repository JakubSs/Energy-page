<?php

function saveActualTemperatureStandAlone(){
include("./config.php");
include("./functions.php");
include("./$languagefile");
if (getActualTemperature()!=false){
    if (!$inicial) {
        $inicial = 0;
    }
    $date=date("Y-m-d H:i");
    $year = substr($date, 0, 4);
    $score=getActualTemperature();
    $sql = "INSERT INTO temperature(date, year, score, inicial, note) VALUES('$date', '$year', '$score', '$inicial', '$note')";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    echo "$recordAddedLang <br>";
    
    }

}
saveActualTemperatureStandAlone();
?>