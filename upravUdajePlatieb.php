<div align="center"><?php
    require_once("config.php");
    if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"])) {
        require_once("getstat.php");

        $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

        $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
        if (mysqli_connect_errno($con)) {
            echo "failed connection!";
        } else {
            $result = mysqli_query($con, $q);
        }
        while ($row = $result->fetch_assoc()) {
            if ($row['lastSession'] == $_COOKIE["PHPSESSID"]) {
               
               
               


                if ((isset($_POST["druh"]) && ($_POST["druh"]!='')) && (isset($_POST["zakCislo"]) && ($_POST["zakCislo"]!='')) 
                        && (isset($_POST["platba"]) && ($_POST["platba"] != '') ) && (isset($_POST["tarifa"]) && ($_POST["tarifa"]) != '') 
                        && (isset($_POST["ucty"]) && ($_POST["ucty"]!='')) && (isset($_POST["VS"]) && ($_POST["VS"]!='')) 
                        && (isset($_POST["KS"]) && ($_POST["KS"]!='')) && (isset($_POST["EIC"])&&($_POST["EIC"]!='')) 
                        && (isset($_POST["odberMiesto"])&&($_POST["odberMiesto"]!='')) 
                        && (isset($_POST["miestoSpotreby"])&&($_POST["odberMiesto"]!='')) && ($_POST["submit"]=="Pridaj")) {

                    $sql = "INSERT INTO udajePlatieb(druh, zakCislo, platba, tarifa, ucty, VS, KS, EIC, odberMiesto, miestoSpotreby) VALUES('$_POST[druh]','$_POST[zakCislo]','$_POST[platba]','$_POST[tarifa]','$_POST[ucty]','$_POST[VS]','$_POST[KS]','$_POST[EIC]','$_POST[odberMiesto]','$_POST[miestoSpotreby]')";
                   
                   
                    
                    if (!mysqli_query($con, $sql)) {
                        die('Error: ' . mysqli_error($con));
                    }
                            echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
                }
                else if ((isset($_POST["druh"]) && ($_POST["druh"]!='')) && (isset($_POST["zakCislo"]) && ($_POST["zakCislo"]!='')) 
                        && (isset($_POST["platba"]) && ($_POST["platba"] != '') ) && (isset($_POST["tarifa"]) && ($_POST["tarifa"]) != '') 
                        && (isset($_POST["ucty"]) && ($_POST["ucty"]!='')) && (isset($_POST["VS"]) && ($_POST["VS"]!='')) 
                        && (isset($_POST["KS"]) && ($_POST["KS"]!='')) && (isset($_POST["EIC"])&&($_POST["EIC"]!='')) 
                        && (isset($_POST["odberMiesto"])&&($_POST["odberMiesto"]!='')) 
                        && (isset($_POST["miestoSpotreby"])&&($_POST["miestoSpotreby"]!='')) && ($_POST["submit2"]=="Zmen")) {


                    $sql = "UPDATE udajePlatieb SET druh='$_POST[druh]', zakCislo='$_POST[zakCislo]', platba='$_POST[platba]', tarifa='$_POST[tarifa]',"
                            . " ucty='$_POST[ucty]', VS='$_POST[VS]', KS=$_POST[KS], EIC='$_POST[EIC]', odberMiesto='$_POST[odberMiesto]', miestoSpotreby='$_POST[miestoSpotreby]' WHERE id='$_POST[id]'";
                   
                    
                   
                    if (!mysqli_query($con, $sql)) {
                        die('Error: ' . mysqli_error($con));
                    }
                            echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
                }


                else if ((isset($_GET["id"]) && !isset($_POST["id"]) || ($_POST["submit"]=="Pridaj"))) {
                    
                    echo "<form method=\"POST\" action=\"upravUdajePlatieb.php\">    
Druh<br><input type=\"text\" name=\"druh\" size=\"40\" placeholder=\"\" value=\"" . $_POST["druh"] . "\"><br>
Zak cislo<br><input type=\"number\" name=\"zakCislo\" size=\"40\" placeholder=\"\" value=\"" . $_POST["zakCislo"] . "\"><br>
Platba<br><input type=\"text\" name=\"platba\" size=\"40\" placeholder=\"\" value=\"" . $_POST["platba"] . "\"><br>
Tarifa<br><input type=\"text\" name=\"tarifa\" size=\"40\" placeholder=\"\" value=\"" . $_POST["tarifa"] . "\"><br>
Ucty<br><input type=\"text\" name=\"ucty\" size=\"40\" placeholder=\"\" value=\"" . $_POST["ucty"] . "\"><br>
VS<br><input type=\"number\" name=\"VS\" size=\"40\" placeholder=\"\" value=\"" . $_POST["VS"] . "\"><br>
KS<br><input type=\"number\" name=\"KS\" size=\"40\" placeholder=\"\" value=\"" . $_POST["KS"] . "\"><br>
EIC<br><input type=\"text\" name=\"EIC\" size=\"40\" placeholder=\"\" value=\"" . $_POST["EIC"] . "\"><br>
Odberne miesto<br><input type=\"number\" name=\"odberMiesto\" size=\"40\" placeholder=\"\" value=\"" . $_POST["odberMiesto"] . "\"><br>
cislo miesta spotreby<br><input type=\"number\" name=\"miestoSpotreby\" size=\"40\" placeholder=\"\" value=\"" . $_POST["miestoSpotreby"] . "\"><br> 

<input id=\"button\" type=\"submit\" name=\"submit\" value=\"Pridaj\">
</form>   ";
                    if (isset($_POST["submit"])) {
                    echo "<h1 style='color:red'>Vypl vsetky policka! </h1><br>";
                    }
                } else if ((!isset($_GET["id"]) && isset($_POST["submit"])) || (isset($_POST["id"]) && ($_POST["submit2"]=="Zmen") )) {
                    if (isset($_POST["id"])) {$id = $_POST["id"];
                    echo "<h1 style='color:red'>Vypl vsetky policka! </h1><br>";
                    }
                    else $id = $_POST["submit"];
                    $sql2 = "SELECT * FROM udajePlatieb WHERE id=$id";
                   
                    if (mysqli_connect_errno($con)) {
                        echo "failed connection!";
                    } else {
                        $result2 = mysqli_query($con, $sql2);
                        while ($row2 = mysqli_fetch_array($result2)) {
                            echo "<form method=\"POST\" action=\"upravUdajePlatieb.php\">    
Druh<br><input type=\"text\" name=\"druh\" size=\"40\" placeholder=\"\" value=\"" . $row2["druh"] . "\"><br>
Zak cislo<br><input type=\"number\" name=\"zakCislo\" size=\"40\" placeholder=\"\" value=\"" . $row2["zakCislo"] . "\"><br>
Platba<br><input type=\"text\" name=\"platba\" size=\"40\" placeholder=\"\" value=\"" . $row2["platba"] . "\"><br>
Tarifa<br><input type=\"text\" name=\"tarifa\" size=\"40\" placeholder=\"\" value=\"" . $row2["tarifa"] . "\"><br>
Ucty<br><input type=\"text\" name=\"ucty\" size=\"40\" placeholder=\"\" value=\"" . $row2["ucty"] . "\"><br>
VS<br><input type=\"number\" name=\"VS\" size=\"40\" placeholder=\"\" value=\"" . $row2["VS"] . "\"><br>
KS<br><input type=\"number\" name=\"KS\" size=\"40\" placeholder=\"\" value=\"" . $row2["KS"] . "\"><br>
EIC<br><input type=\"text\" name=\"EIC\" size=\"40\" placeholder=\"\" value=\"" . $row2["EIC"] . "\"><br>
Odberne miesto<br><input type=\"number\" name=\"odberMiesto\" size=\"40\" placeholder=\"\" value=\"" . $row2["odberMiesto"] . "\"><br>
cislo miesta spotreby<br><input type=\"number\" name=\"miestoSpotreby\" size=\"40\" placeholder=\"\" value=\"" . $row2["miestoSpotreby"] . "\"><br>
<br><input type=\"hidden\" name=\"id\" size=\"40\" value=\"$row2[id]\"><br>    

<input id=\"button\" type=\"submit\" name=\"submit2\" value=\"Zmen\">
</form>   ";
                        }
                    }
                }
                else {
                    echo "<script type=\"text/javascript\">
                    window.location = \"../\"
                    </script>";
                    
                }
                
            }
        }

        echo "<a href='logout.php'>Odhlasit</a><br>";
        echo "<a href='pridat.php'>Pridat zaznam</a><br>";
        echo "<a href='index.php'>Späť</a><br>";
        
        
    } else {
        echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
    }
    ?>
    <div align="center">