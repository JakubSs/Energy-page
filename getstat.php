<?php

require_once("config.php");
if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"])) {

    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

    $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $q);
    }
    while ($row = $result->fetch_assoc()) {
        if ($row['lastSession'] == $_COOKIE["PHPSESSID"]) {
            
            
            $sql2 = "SELECT * FROM tempStat";
            
            if (mysqli_connect_errno($con)) {
                echo "failed connection!";
            } else {
                $result2 = mysqli_query($con, $sql2);
                while ($row2 = mysqli_fetch_array($result2)) {
                if ($modulPlyn==true)
                {$plyn = $row2['stavPlyn'];
                $dniPlyn = $row2['sumDniPlyn'];
                $dennyPriemerPlyn = $plyn / $dniPlyn;
                }
                if ($modulEE==true)
                {$ee = $row2['stavEE'];
                $dniEE = $row2['sumDniEE'];
                $dennyPriemerEE = $ee / $dniEE;
                }
                if ($modulVoda==true)
                {$voda = $row2['stavVoda'];
                $dniVoda = $row2['sumDniVoda'];
                $dennyPriemerVoda = $voda / $dniVoda;
                }

                
            }}

        }
    }
} else {
    echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
}
?>
                    