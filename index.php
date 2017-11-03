<?php
//ini_set('display_errors', 10);
//ini_set('display_startup_errors',10);
//error_reporting(E_ALL);
if (!file_exists("config.php")) {
    echo "<script type=\"text/javascript\">
            window.location = \"install.php\"
            </script>";
} else if (file_exists("config.php") && file_exists("install.php")) {

    $file = "install.php";
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
        <link href="./style.css" rel="stylesheet" type="text/css">
    </head>
    <body><div align="center">
            <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
              <div style="text-align: center;">
                  <span class="brand" style="float: none;"><?php echo $headingLang ?></span>
                  <?php echo "<a href='/'><img src='$logo' height='50px'></a><br>"; ?>
              </div>
          </div>
      </div>
            
<?php
//echo $_COOKIE[name]." - ".$_COOKIE[name2]." - ".$_COOKIE[PHPSESSID]." - ".$_COOKIE[wrong]. " ->".$_COOKIE[user_pass]. "<-";


if (verificate() == false) {

    require_once("login.php");
} else if (verificate() == true) {
    echo "
        
<div class=\"dropdown\">
  <button class=\"dropbtn\">$SystemLang</button>
  <div class=\"dropdown-content\">
  <a href='index.php'>$homeLang</a>
   <a href='index.php?logout=true'>$logoutLang $_COOKIE[name]</a>
   <a href='index.php?passchange=true'>$changePasswordLang</a>
   <a href='index.php?editConfig=true'>$editConfigurationLang</a>
   <a href='index.php?adduser=true'>$addUser</a></td></tr>
   <a href='index.php?aboutMe=true'>$aboutMeLang</a></td></tr>
  </div>
</div>";

    echo "<div class=\"dropdown\">
  <button class=\"dropbtn\">$FunctionsLang</button>
  <div class=\"dropdown-content\">
   <a href='index.php?addRecord=true'>$addRecordLang</a>
   <a href='index.php?showPaymentRecords=true'>$showPaymentRecordLang</a>
   <a href='index.php?addPaymentRecord=true'>$addPaymentRecordLang</a>
  </div>
</div>";
    if ($_GET["logout"] == true) {
        logout();
    }
    if (count($modules) > 0) {
        echo "<div class=\"dropdown\">
  <button class=\"dropbtn\">$statisticsLang</button>
  <div class=\"dropdown-content\">";
        echo "<a href='index.php?dateSetForm=true'>$statisticsForDateSetLang</a>";
        foreach ($modules as &$modul) {
            $energyTemp=$modul;
            echo "<a href='index.php?stat=" . $modul . "'>$statisticsForLang" . ${$energyTemp."Lang"} . "</a>";
        }
        echo "</div></div>";
    }
    if (count($modules) > 0) {
        echo "<div class=\"dropdown\">
  <button class=\"dropbtn\">$GraphLang</button>
  <div class=\"dropdown-content\">";
        
        foreach ($modules as &$modul) {
            $energyTemp=$modul;
            echo "<a href='index.php?graph=" . $modul . "'>$graphForLang " . ${$energyTemp."Lang"} . "</a>";
        }
        echo "</div></div>";
    }
    if (count($modules) > 0) {
        echo "<div class=\"dropdown\">
  <button class=\"dropbtn\">$AverageGraphLang</button>
  <div class=\"dropdown-content\">";
        foreach ($modules as &$modul) {
            $energyTemp=$modul;
            echo "<td><a href='index.php?averageGraph=" . $modul . "'>$averageGraphForLang " . ${$energyTemp."Lang"} . "</a></td>";
        }
        echo "</div></div>";
    }
  

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
        editConfigSave($_POST["dbname"], $_POST["dbserver"], $_POST["dbuser"], $_POST["dbpass"], $_POST["gas"], $_POST["ee"], $_POST["water"], $_POST["hotWater"],
                $_POST["secret"], $_POST["lastStatistics"], $_POST["language"], $_POST["temperature"], $_POST["countPrice"],
                $_POST["priceForPlaceMonthlyGas"], $_POST["gasToKwh"], $_POST["priceForGasKwh"], $_POST["priceForPlaceMonthlyEE"],
                $_POST["priceForEEKwh"], $_POST["sourceOfTemperature"], 
                $_POST["priceForPlaceMonthlyWater"], $_POST["priceForM3Water"], $_POST["priceForPlaceMonthlyHotWater"], $_POST["priceForM3HotWater"]);
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
        if ($temperatureIS==true){drawTemperatureGraph();}
        echo "<br><br><br><br>";
    }
    else if ($_GET["averageGraph"]) {
        drawAverageGraph($_GET["averageGraph"]);
        if ($temperatureIS==true){drawTemperatureGraph();}
        echo "<br><br><br><br>";
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
    else if ($_GET["aboutMe"] == true) {
        aboutMe();
    }
    else if ($_GET["dateSetForm"] == true) {
        getDateForStatisticsDateSet();
    }
    else if ($_GET["dateSet"] == true) {
        getStatisticsFromDateSet($_POST["start"], $_POST["end"],$_POST['module']);
    }
    else {
        if (count($modules) > 0)
            {showStat();}
        }
            
        //saveActualTemperature();
    
    if ($_COOKIE["changed"] == True) {
        echo "<p color=\"red\">$changedPasswordLang</p>";
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
    height:35px;\"><p>$footerLang</p></div>";
            ?>
    </body>
</html>