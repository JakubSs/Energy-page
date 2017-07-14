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
               
               if ($modulPlyn == true){
                $sql = "SELECT * FROM plyn";
               
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result = mysqli_query($con, $sql);
///////
                    ?>

                    <fieldset><legend>Stav Plyn</legend>
                        <table border="1">
                            <tr>
                                <th>ID</th>
                                <th>Dátum</th>
                                <th>Dní</th>
                                <th>Stav</th>
                                <th>Spotreba</th>
                                <th>Denný priemer od posledného merania</th>
                                <th>Inicial</th>
                                <th>Poznámka</th>      
                            </tr><?php
                $sumPlyn = 0;
                $sumDniPlyn = 0;
                $tempPlyn = 0;
                $lastPlyn = 0;
                $lastDate = 0;
                $tempDni = 0;
                while ($row = mysqli_fetch_array($result)) {


                    $id = $row['id'];


//$inicial="";
                    if ($row['inicial'] == 0) {
                        $inicial = "nie";
                    } else {
                        $inicial = "ano";
                    }

                    $tempPlyn = $row['stav'] - $lastplyn;
                    if ($row['inicial'] == 1) {
                        $tempPlyn = 0;
                    }
                    $sumPlyn+=$tempPlyn;
                    $tempDni = $row['datum'];
                    $days_between = $tempDni - $lastdate;
                   

                    $start = strtotime($lastdate);
                    $end = strtotime($tempDni);

                    $days_between = ceil(abs($end - $start) / 86400);
                   
                    if ($row['inicial'] == 1) {
                        $sumDniPlyn = 0;
                        $days_between = 0;
                    } else {
                        $sumDniPlyn+=$days_between;
                    }
                   
                    if (($tempPlyn/$days_between)>$dennyPriemerPlyn){$a="style=\"color: red;\"";}else{$a="style=\"color: blue;\"";}
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['datum'] . "</td><td>" . $days_between . "</td><td>" . $row['stav'] . "m<sup>3</sup></td><td>" . $tempPlyn
                    . "m<sup>3</sup></td><td ".$a.">".($tempPlyn/$days_between)." m<sup>3</sup></td><td>" . $inicial . "</td><td>" . $row['poznamka'] . "</td>
</tr>";
                    $lastplyn = $row['stav'];
                    $lastdate = $row['datum'];
                }
                echo "</fieldset></table>";
                echo $sumPlyn."m<sup>3</sup> za ";
                echo $sumDniPlyn." dní, s denným priemerom: ".$dennyPriemerPlyn." m<sup>3</sup>";

                echo "<br><br><br>";}

///////
            }

            if ($modulEE==true){
            $sql = "SELECT * FROM ee";
           
            if (mysqli_connect_errno($con)) {
                echo "failed connection!";
            } else {
                $result = mysqli_query($con, $sql);
///////
                    ?>

                            <fieldset><legend>Stav EE</legend>
                                <table border="1">
                                    <tr>
                                        <th>ID</th>
                                        <th>Dátum</th>
                                        <th>Dní</th>
                                        <th>Stav</th>
                                        <th>Spotreba</th>
                                        <th>Denný priemer od posledného merania</th>
                                        <th>Inicial</th>
                                        <th>Poznámka</th>     
                                    </tr><?php
                $sumEE = 0;
                $sumDniEE = 0;
                $tempEE = 0;
                $lastEE = 0;
                $lastDate = 0;
                $tempDni = 0;
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['inicial'] == 0) {
                        $inicial = "nie";
                    } else {
                        $inicial = "ano";
                    }
                    $tempEE = $row['stav'] - $lastEE;
                    if ($row['inicial'] == 1) {
                        $tempEE = 0;
                    }
                    $sumEE+=$tempEE;
                    $tempDni = $row['datum'];
                    $days_between = $tempDni - $lastdate;
                   

                    $start = strtotime($lastdate);
                    $end = strtotime($tempDni);

                    $days_between = ceil(abs($end - $start) / 86400);
                   
                    if ($row['inicial'] == 1) {
                        $sumDniEE = 0;
                        $days_between = 0;
                    } else {
                        $sumDniEE+=$days_between;
                    }
                    if (($tempEE/$days_between)>$dennyPriemerEE){$a="style=\"color: red;\"";}else{$a="style=\"color: blue;\"";}
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['datum'] . "</td><td>" . $days_between . "</td><td>" . $row['stav'] . "kWh</td><td>" . $tempEE
                    . "kWh</td><td ".$a.">".($tempEE/$days_between)." kWh</td><td>" . $inicial . "</td><td>" . $row['poznamka'] . "</td>
</tr>";
                    $lastEE = $row['stav'];
                    $lastdate = $row['datum'];
                }
                echo "</fieldset></table>";
                echo $sumEE."kWh za ";
                
                echo $sumDniEE." dní, s denným priemerom: ".$dennyPriemerEE." kWh";
            echo "<br><br><br>";}
                if($modulVoda==true){
                $sql = "SELECT * FROM voda";
               
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result = mysqli_query($con, $sql);
///////
                    ?>

                    <fieldset><legend>Stav Voda</legend>
                        <table border="1">
                            <tr>
                                <th>ID</th>
                                <th>Dátum</th>
                                <th>Dní</th>
                                <th>Stav</th>
                                <th>Spotreba</th>
                                <th>Denný priemer od posledného merania</th>
                                <th>Inicial</th>
                                <th>Poznámka</th>      
                            </tr><?php
                $sumVoda = 0;
                $sumDniVoda = 0;
                $tempVoda = 0;
                $lastVoda = 0;
                $lastDate = 0;
                $tempDni = 0;
                while ($row = mysqli_fetch_array($result)) {


                    $id = $row['id'];


//$inicial="";
                    if ($row['inicial'] == 0) {
                        $inicial = "nie";
                    } else {
                        $inicial = "ano";
                    }

                    $tempVoda = $row['stav'] - $lastvoda;
                    if ($row['inicial'] == 1) {
                        $tempVoda = 0;
                    }
                    $sumVoda+=$tempVoda;
                    $tempDni = $row['datum'];
                    $days_between = $tempDni - $lastdate;
                   

                    $start = strtotime($lastdate);
                    $end = strtotime($tempDni);

                    $days_between = ceil(abs($end - $start) / 86400);
                   
                    if ($row['inicial'] == 1) {
                        $sumDniVoda = 0;
                        $days_between = 0;
                    } else {
                        $sumDniVoda+=$days_between;
                    }
                   
                    if (($tempVoda/$days_between)>$dennyPriemerVoda){$a="style=\"color: red;\"";}else{$a="style=\"color: blue;\"";}
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['datum'] . "</td><td>" . $days_between . "</td><td>" . $row['stav'] . "m<sup>3</sup></td><td>" . $tempVoda
                    . "m<sup>3</sup></td><td ".$a.">".($tempVoda/$days_between)." m<sup>3</sup></td><td>" . $inicial . "</td><td>" . $row['poznamka'] . "</td>
</tr>";
                    $lastvoda = $row['stav'];
                    $lastdate = $row['datum'];
                }
                echo "</fieldset></table>";
                echo $sumVoda."m<sup>3</sup> za ";
                echo $sumDniVoda." dní, s denným priemerom: ".$dennyPriemerVoda." m<sup>3</sup>";

                echo "<br><br><br>";}

///////
if($modulVodaTepla==true){
                $sql = "SELECT * FROM vodaTepla";
               
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result = mysqli_query($con, $sql);
///////
                    ?>

                    <fieldset><legend>Stav Teplá voda</legend>
                        <table border="1">
                            <tr>
                                <th>ID</th>
                                <th>Dátum</th>
                                <th>Dní</th>
                                <th>Stav</th>
                                <th>Spotreba</th>
                                <th>Denný priemer od posledného merania</th>
                                <th>Inicial</th>
                                <th>Poznámka</th>      
                            </tr><?php
                $sumVodaTepla = 0;
                $sumDniVodaTepla = 0;
                $tempVodaTepla = 0;
                $lastVodaTepla = 0;
                $lastDateTepla = 0;
                $tempDniTepla = 0;
                while ($row = mysqli_fetch_array($result)) {


                    $id = $row['id'];


//$inicial="";
                    if ($row['inicial'] == 0) {
                        $inicial = "nie";
                    } else {
                        $inicial = "ano";
                    }

                    $tempVodaTepla = $row['stav'] - $lastvodaTepla;
                    if ($row['inicial'] == 1) {
                        $tempVodaTepla = 0;
                    }
                    $sumVodaTepla+=$tempVodaTepla;
                    $tempDni = $row['datum'];
                    $days_between = $tempDni - $lastdate;
                   

                    $start = strtotime($lastdate);
                    $end = strtotime($tempDni);

                    $days_between = ceil(abs($end - $start) / 86400);
                   
                    if ($row['inicial'] == 1) {
                        $sumDniVodaTepla = 0;
                        $days_between = 0;
                    } else {
                        $sumDniVodaTepla+=$days_between;
                    }
                   
                    if (($tempVodaTepla/$days_between)>$dennyPriemerVodaTepla){$a="style=\"color: red;\"";}else{$a="style=\"color: blue;\"";}
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['datum'] . "</td><td>" . $days_between . "</td><td>" . $row['stav'] . "m<sup>3</sup></td><td>" . $tempVodaTepla
                    . "m<sup>3</sup></td><td ".$a.">".($tempVodaTepla/$days_between)." m<sup>3</sup></td><td>" . $inicial . "</td><td>" . $row['poznamka'] . "</td>
</tr>";
                    $lastvodaTepla = $row['stav'];
                    $lastdateTepla = $row['datum'];
                }
                echo "</fieldset></table>";
                echo $sumVodaTepla."m<sup>3</sup> za ";
                echo $sumDniVodaTepla." dní, s denným priemerom: ".$dennyPriemerVodaTepla." m<sup>3</sup>";

echo "<br><br><br>";}

///////
            }

/////
               
                $sql = "UPDATE tempStat SET 	user='" . $_COOKIE[meno] . "'";
                if ($modulPlyn==true) {$sql.=", stavPlyn=$sumPlyn, sumDniPlyn=$sumDniPlyn";}
                if ($modulEE==true) {$sql.=", stavEE=$sumEE, sumDniEE=$sumDniEE";}
                if ($modulVoda==true) {$sql.=", stavVoda=$sumVoda, sumDniVoda=$sumDniVoda";}
                if ($modulVodaTepla==true) {$sql.=", stavVodaTepla=$sumVodaTepla, sumDniVoda=$sumDniVodaTepla";}
                $sql .= " WHERE id='1'";
                if (!mysqli_query($con, $sql)) {
                    die('Error: ' . mysqli_error($con));
                }
            }
        }
    }
    echo "<a href='logout.php'>Odhlasit</a><br>";
    echo "<a href='pridat.php'>Pridat zaznam</a><br>";
    echo "<a href='index.php'>Späť</a><br>";
    }} else {
    echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
}
    ?>
                    <div align="center">