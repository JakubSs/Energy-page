<div align="center">
<?php

require_once("config.php");
echo "<h1>Registrácia</h1>";
if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && !isset($_POST["heslo"])) {
    echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>Pridaj používateľa:</legend>
        <form method=\"POST\" action=\"register.php\">
        meno <br><input type=\"text\" name=\"meno\" size=\"40\" placeholder=\"Zadaj meno nového\"><br>
        heslo <br><input type=\"password\" name=\"heslo\" size=\"40\" placeholder=\"Zadaj heslo nového\"><br><br>
        email <br><input type=\"text\" name=\"email\" size=\"60\" placeholder=\"example@sedinar.eu\"><br><br>
        Poznamka <br><input type=\"text\" name=\"poznamka\" size=\"60\" placeholder=\"Poznámka\"><br><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"Registruj\"><div>
        </form>
        </fieldset><div>
";
    echo "<a href='./'>Spat</a><br>";
} else if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && isset($_POST["meno"])) {


    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

    $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $q);
    }
    while ($row = $result->fetch_assoc()) {
        if ($row['lastSession'] == $_COOKIE["PHPSESSID"]) {
            $tmp = $_POST["heslo"];
            $tmp.=$secret;
           

            $heslo = md5($tmp);
           
            $sql = "INSERT INTO pouzivatelia(meno, heslo, email, skupina, poznamka, lastSession) VALUES('$_POST[meno]', '$heslo', '$_POST[email]', '1', '$_POST[poznamka]', '')";
           
            if (!mysqli_query($con, $sql)) {
                die('Error: ' . mysqli_error($con));
            }
            
        }
    }
} else {
    echo "<script type=\"text/javascript\">
            window.location.href = \"../\"
            </script>";
}

?>
</div>