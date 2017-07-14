<?php
if (!file_exists("config.php"))
{
    echo "<script type=\"text/javascript\">
            window.location = \"../install.php\"
            </script>";
}
else if (file_exists("config.php") && file_exists("install.php"))
{
   
    $file = "install.php";
    unlink($file);
}
else require_once("config.php");
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Energy page</title>
    </head>
    <body><div align="center">
            <h1>Energy page database <?php echo $dbname;?></h1>
            <?php
            echo "<img src='$logo' height='50px'><br>";
            
            if (!isset($_COOKIE["PHPSESSID"]) || !isset($_COOKIE["meno"])) {
                
                
                
                require_once("login.php");
            } else {
                echo "<a href='logout.php'>Odhlasit používateľa $_COOKIE[meno]</a><br>";
                echo "<a href='pridat.php'>Pridat zaznam</a><br>";
                echo "<a href='stat.php'>Statistika</a><br>";
                echo "<a href='register.php'>Pridaj používateľa</a><br>";
                echo "<a href='passChange.php'>Zmeň si heslo</a><br>";
                echo "<a href='editConfig.php'>Uprav nastavenia</a><br>";
                

                if ($_COOKIE["changed"] == True) {
                    echo "<p color=\"red\"> Your password was changed succesfuly.</p>";
                    setcookie("changed", True, time() - (300), "/");
                }
                setcookie("wrong", True, time() - (300), "/");
                setcookie("meno2", $user, time() - (300), "/");


                ob_start();
                include("stat.php");
                ob_end_clean();
                $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

                $q = "SELECT * FROM tempStat Where id='1'";
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result = mysqli_query($con, $q);
                }
                ?>
                <fieldset><legend>Štatistika</legend>
                    <table border="1">
                        <tr>
                            <th>Minuté celkom</th>
                            <th>Denný celkový priemer</th>
                            <th>Ročný predpoklad</th>

                        </tr>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $stavPlyn = $row['stavPlyn'];
                            $sumDniPlyn = $row['sumDniPlyn'];
                            $stavEE = $row['stavEE'];
                            $sumDniEE = $row['sumDniEE'];
                            $stavVoda = $row['stavVoda'];
                            $sumDniVoda = $row['sumDniVoda'];
                            $stavVodaTepla = $row['stavVodaTepla'];
                            $sumDniVodaTepla = $row['sumDniVodaTepla'];

                            if($modulPlyn==true){echo "<tr><td>" . $stavPlyn . " m<sup>3</sup></td><td>" . ($stavPlyn / $sumDniPlyn) . " m<sup>3</sup></td><td>" . (($stavPlyn / $sumDniPlyn) * 365) . " m<sup>3</sup></td>
                            </tr>";}
                            if($modulEE==true){echo "<tr><td>" . $stavEE . " kWh</td><td>" . ($stavEE / $sumDniEE) . " kWh</td><td>" . (($stavEE / $sumDniEE) * 365) . " kWh</td>
                            </tr>";}
                            if($modulVoda==true){echo "<tr><td>" . $stavVoda . " m<sup>3</sup></td><td>" . ($stavVoda / $sumDniVoda) . " m<sup>3</sup></td><td>" . (($stavVoda / $sumDniVoda) * 365) . " m<sup>3</sup></td>
                            </tr>";}
                            if($modulVodaTepla==true){echo "<tr><td>" . $stavVodaTepla . " m<sup>3</sup></td><td>" . ($stavVodaTepla / $sumDniVodaTepla) . " m<sup>3</sup></td><td>" . (($stavVodaTepla / $sumDniVodaTepla) * 365) . " m<sup>3</sup></td>
                            </tr>";}
                        }echo "</table> </fieldset>";
                    
                    if($modulPlyn==true){
                    echo "<br><br>Graf spotreby plynu";include("graphPlyn.php");
                    echo "<br><br>Graf spotreby plynu priemer";include("graphPlynPriemer.php");}
                    if($modulEE==true){
                    echo "<br><br>Graf spotreby EE";include("graphEE.php");
                    echo "<br><br>Graf spotreby EE priemer";include("graphEEPriemer.php");}
                    if($modulVoda==true){
                    echo "<br><br>Graf spotreby voda";include("graphVoda.php");
                    echo "<br><br>Graf spotreby voda  priemer";include("graphVodaPriemer.php");}
                    if($modulVodaTepla==true){
                    echo "<br><br>Graf spotreby voda TEPLÁ";include("graphVodaTepla.php");
                    echo "<br><br>Graf spotreby voda TEPLÁ priemer";include("graphVodaTeplaPriemer.php");}
                        
                        
                        
                    
                    
                    echo "<br><br> Údaje pre platby:";
                    $sql2 = "SELECT * FROM udajePlatieb";
               
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result2 = mysqli_query($con, $sql2);
                        echo "
                         <fieldset><legend>Štatistika</legend><table border='1'>
                            
                        <tr>
                            <th>Druh</th>
                            <th>Zák číslo</th>
                            <th>Platba</th>
                            <th>Tarifa</th>
                            <th>Účty</th>
                            <th>VS</th>
                            <th>KS</th>
                            <th>EIC</th>
                            <th>Odberné miesto</th>
                            <th>Číslo miesta spotreby</th>
                            <th>Uprav</th>

                        </tr>";
                        while ($row2 = mysqli_fetch_array($result2)) {
                        echo "<tr><td>$row2[druh]</td>"
                                . "<td>$row2[zakCislo]</td>"
                                . "<td>$row2[platba]</td>"
                                . "<td>$row2[tarifa]</td>"
                                . "<td>$row2[ucty]</td>"
                                . "<td>$row2[VS]</td>"
                                . "<td>$row2[KS]</td>"
                                . "<td>$row2[EIC]</td>"
                                . "<td>$row2[odberMiesto]</td>"
                                . "<td>$row2[miestoSpotreby]</td>"
                                . "<td><form method=\"POST\" action=\"upravUdajePlatieb.php\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"$row2[id]\">
</form></td></tr>";
                        }
                        echo "</table> <br><br> <a href='upravUdajePlatieb.php?id=0'>Pridaj udaj o platbe</a> </fieldset><br><br><br><br><br><br>"
                ;}
                    }

                    
                    ?>
                    </div>
<?php echo "This is energy page version $version made by $Author. My page is <a href='$link'>$link</a>";
?>
                </body>
                    </html>
