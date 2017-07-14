<?php

require_once("config.php");
if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && !isset($_POST["stav"])) {
    echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>Pridaj zaznam</legend>
        <form method=\"POST\" action=\"pridat.php\">
        Datum <br><input type=\"date\" name=\"datum\" min=\"2017-03-31\" max=\"2100-12-31\"><br>
        ";
        if ($modulPlyn==true){echo"<input type=\"radio\" name=\"energia\" value=\"plyn\"> Plyn<br>";}
        if ($modulEE==true){echo"<input type=\"radio\" name=\"energia\" value=\"ee\"> Elektrika<br>";}
        if ($modulVoda==true){echo"<input type=\"radio\" name=\"energia\" value=\"voda\"> Voda<br><br>";}
        echo "Stav v celých m<sup>3</sup> zaokr. nahor:<br><input type=\"number\" name=\"stav\" size=\"10\"  placeholder=\"Napr 254,325 sa zapíše ako 255\" style=\"width: 20em;\"><br>
        <div style=\"display: none !important;\">Inicial <input type=\"checkbox\" name=\"inicial\" value=\"1\"></div><br>
        Poznamka <br><input type=\"text\" name=\"poznamka\" size=\"60\" placeholder=\"Poznámka\"><br><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"Pridaj\" align=\"right\"><div>
        </form>
        </fieldset><div>
";
    echo "<a href='./'>Spat</a><br>";
} else if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && isset($_POST["stav"])) {


    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

    $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $q);
    }
    while ($row = $result->fetch_assoc()) {
        if ($row['lastSession'] == $_COOKIE["PHPSESSID"]) {
            $inicial = 0;
            $rok = substr($_POST["datum"], 0, 4);
            if (!isset($_POST["inicial"])) {
                $inicial = 0;
            } else {
                $inicial = 1;
            }
            $sql = "INSERT INTO $_POST[energia](datum, rok, stav, inicial, poznamka) VALUES('$_POST[datum]', '$rok', '$_POST[stav]', '$inicial', '$_POST[poznamka]')";
           
            if (!mysqli_query($con, $sql)) {
                die('Error: ' . mysqli_error($con));
            }
            echo "<script type=\"text/javascript\">
            window.location.href = \"../\"
            </script>";
//echo $_POST["datum"]."','".$rok."','".$_POST["stav"]."','".$_POST["inicial"]."','".$_POST["poznamka"];        
        }
    }
} else {
    echo "<script type=\"text/javascript\">
            window.location.href = \"../\"
            </script>";
}

?>