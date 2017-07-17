<?php
if (!file_exists("config.php")) {
    echo "<script type=\"text/javascript\">
            window.location = \"install.php\"
            </script>";
} else if (file_exists("config.php") && file_exists("install-remove.php")) {

    $file = "install-remove.php";
    unlink($file);
} else {
    require_once("config.php");
}

require_once("./functions.php");
if (isset($_COOKIE["needReload"])) {
    setcookie("needReload", True, time() - 330, "/");
    echo "<script type=\"text/javascript\">
            setTimeout(function () {
        location.reload()
    }, 500);
            </script>";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Resources page <?php echo $version; ?></title>
    </head>
    <body><div align="center">
            <h1>Energy page database <?php echo $dbname; ?></h1>
<?php
//echo $_COOKIE[name]." - ".$_COOKIE[name2]." - ".$_COOKIE[PHPSESSID]." - ".$_COOKIE[wrong]. " ->".$_COOKIE[user_pass]. "<-";
echo "<a href='/'><img src='$logo' height='50px'></a><br>";

if (verificate() == false) {

    require_once("login.php");
} else if (verificate() == true) {
    echo "
                <fieldset style=\"margin: 20 30% 50 30%;\"><legend>Menu</legend>
                    <table border=\"1\">
                        <tr>
                            <th>System:</th>

                        ";
    echo "<td><a href='index.php?logout=true'>Logout $_COOKIE[name]</a></td>";
    if ($_GET["logout"] == true) {
        logout();
    }
    
    echo "<td><a href='index.php?passchange=true'>Change password</a></td>";
    echo "<td><a href='index.php?editConfig=true'>Edit configuration</a></td>";
    echo "<td><a href='index.php?adduser=true'>Add user</a></td></tr>";
    
    
    echo "<tr><th>Functions:</th>";
    echo "<td><a href='index.php?addRecord=true'>Add record</a></td>";
    echo "<td><a href='index.php?showPaymentRecords=true'>Show payment records</a></td>";
    echo "<td><a href='index.php?addPaymentRecord=true'>Add payment records</a></td></tr>";
    
    
    if (count($modules) > 0) {
        echo "<tr><th>Statistics:</th>";
        foreach ($modules as &$modul) {
            echo "<td><a href='index.php?stat=" . $modul . "'>Statistics for " . $modul . "</a></td>";
        }
        echo "</tr>";
    }
    if (count($modules) > 0) {
        echo "<tr><th>Graphs:</th>";
        foreach ($modules as &$modul) {
            echo "<td><a href='index.php?graph=" . $modul . "'>Graph for " . $modul . "</a></td>";
        }
        echo "</tr>";
    }
    if (count($modules) > 0) {
        echo "<tr><th>Average graphs:</th>";
        foreach ($modules as &$modul) {
            echo "<td><a href='index.php?averageGraph=" . $modul . "'>Averge graph for " . $modul . "</a></td>";
        }
        echo "</tr>";
    }
        
    
    
echo "</table> </fieldset>";

    if ($_GET["editConfig"] == true) {
        editConfigShow();
    }
    else if ($_GET["passchange"] == true) {
        passWordChange();
    }
    else if ($_GET["changedPass"] == true) {
        passWordChangeSave($_POST["oldPass"], $_POST["newPass"], $_POST["repeatPass"]);
    }
    else if ($_GET["newConfig"] == true) {
        editConfigSave($_POST["dbname"], $_POST["dbserver"], $_POST["dbuser"], $_POST["dbpass"], $_POST["gas"], $_POST["ee"], $_POST["water"], $_POST["hotWater"], $_POST["secret"], $_POST["lastStatistics"]);
    }
    
    else if ($_GET["addRecord"] == true) {
        addRecordShow();
    }
    else if ($_GET["addedRecord"] == true) {
        addRecordSave($_POST["date"], $_POST["energy"], $_POST["score"], $_POST["inicial"], $_POST["note"]);
    }
    
    
    else if ($_GET["stat"]) {
        statistics($_GET["stat"]);
    }

    else if ($_GET["graph"]) {
        drawGraph($_GET["graph"]);
    }
    else if ($_GET["averageGraph"]) {
        drawAverageGraph($_GET["averageGraph"]);
    }

    else if ($_GET["adduser"] == true) {
        addUser();
    }
    else if ($_GET["register"] == true) {
        registerUser($_POST[username], $_POST["password"], $_POST[email], $_POST[group], $_POST[note]);
    }

    else if ($_GET["showPaymentRecords"] == true) {
        showPaymentRecords();
    }
    else if ($_GET["editPaymentRecord"] == true) {
        editPaymentRecord($_POST["submit"]);
    }
    else if ($_GET["editPaymentRecordSave"] == true) {
        editPaymentRecordSave($_POST["kind"], $_POST["customerNumber"], $_POST["payment"], $_POST["tariff"], $_POST["bankAccounts"], $_POST["Variable"], $_POST["Constant"], $_POST["EIC"], $_POST["deliveryPoint"], $_POST["consumptionPoint"], $_POST["id"]);
    }
    else if ($_GET["addPaymentRecord"] == true) {
        addPaymentRecord();
    }
    else if ($_GET["addPaymentRecordSave"] == true) {
        addPaymentRecordSave($_POST["kind"], $_POST["customerNumber"], $_POST["payment"], $_POST["tariff"], $_POST["bankAccounts"], $_POST["Variable"], $_POST["Constant"], $_POST["EIC"], $_POST["deliveryPoint"], $_POST["consumptionPoint"]);
    }
    else {
        if (count($modules) > 0)
            {showStat();}
        }
            

    
    if ($_COOKIE["changed"] == True) {
        echo "<p color=\"red\"> Your password was changed succesfuly.</p>";
        setcookie("changed", True, time() - (300), "/");
    }
}
?>

            <?php 
            $date = date_create($releaseDate);
            echo "<div align=\"center\" style=\"bottom:0;
    position:fixed;
    z-index:150;
    _position:absolute;
    _top:expression(eval(document.documentElement.scrollTop+
        (document.documentElement.clientHeight-this.offsetHeight)));
    height:35px;\"><p>This is energy page version $version made by $Author with release date ". date_format($date, 'l jS F Y') .". My page is <a href='$link'>$link</a></p></div>";
            ?>
    </body>
</html>