
        <?php

$dbservername = "localhost";
$dbusername = "energie";
$dbpassword = "someDBPassword";
$dbname = "resources";
$con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

$secret = "someGeneratedSecret";
$releaseDate = "2017-07-02";
$version = "2.0";
$Author = "Jakub Sedinar - Sedinar.EU";
$link = "https://sedinar.eu";
$logo = "https://sedinar.eu/logo.png";

$modules=array();array_push($modules, "gas");array_push($modules, "ee");array_push($modules, "water");$moduleGas=true;$moduleEE=true;$moduleWater=true;$moduleHotWater=false;

?>            