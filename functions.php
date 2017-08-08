<?php

function verificate() {
    include("config.php");
    if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["name"])) {
        $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);
        $qVerification = "SELECT lastSession FROM users WHERE username='" . $_COOKIE["name"] . "' ";
        if (mysqli_connect_errno($con)) {
            echo "failed connection to: ";
            echo $dbservername;
        } else {
            $resultVerification = mysqli_query($con, $qVerification);
        }
        while ($rowVerification = $resultVerification->fetch_assoc()) {
            if ($rowVerification['lastSession'] == $_COOKIE["PHPSESSID"]) {
                setcookie("wrong", True, time() - (300), "/");
                setcookie("name2", $user, time() - (300), "/");
                setcookie("user_pass", $user, time() - (300), "/");
                //echo "ok";
                return true;
            } else
            //echo "NO ok 1";
                return false;
        }
    } else {
        //echo "NO ok 2";
        return false;
    }
}

function returnAvailableModules() {
    return $availableModules = array("gas", "ee", "water", "hotWater");
}

function returnAvailableModulesNames() {
    return $availableModulesNames = array("$gasLang", "$eeLang", "$waterLang", "$hotWaterLang");
}

function logout() {
    include("config.php");
    $user = $_COOKIE["name"];
    $sql = "UPDATE users SET lastSession='' WHERE username='$user'";
    setcookie("PHPSESSID", "", time() - (300), "/");
    setcookie("name", "", time() - (300), "/");

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    echo "<script type=\"text/javascript\">
            window.location.href = \"/\"
            </script>";
}

function showStat() {
    include("config.php");
    include("$languagefile");
    $google = "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type=\"text/javascript\">
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '$kindLang');
        data.addColumn('number', '$spentLang');
        data.addColumn('number', '$dailyAverageLang');
        data.addColumn('number', '$yearlyAssumptionLang');
        data.addRows([";

    if (count($modules) > 0) {
        foreach ($modules as &$modul) {

            if ($modul == "ee") {
                $unit = "kWh";
            } else
                $unit = "m<sup>3</sup>";
            $score = round(getSpent($modul), 2);
            $average = round(getstat($modul), 2);
            $modul = ucfirst($modul);
            $google.="['$modul',  {v: $score, f: '$score $unit'},  {v: $average, f: '$average $unit'},  {v: " . ($average * 365) . ", f: '" . ($average * 365) . " $unit'}],";
        }
        $google.="]);

        var table = new google.visualization.Table(document.getElementById('table_statistics'));

        table.draw(data, {showRowNumber: true, allowHtml: true});
      }
    </script>
    
<div id=\"table_statistics\" ></div>";
    }
    echo "<fieldset style=\"margin: 20 30% 50 30%;\"><legend>$statisticsLang</legend> $google </fieldset>";
    //echo $google;
}

function getSpent($model) {
    include("config.php");

    $sql2 = "SELECT * FROM tempStat";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    }
    $result2 = mysqli_query($con, $sql2);
    while ($row2 = mysqli_fetch_array($result2)) {
        $model = ucfirst($model);
        $score = "Score" . $model;
        $sumScore = "SumScore" . $model;
        $unit = $row2[$score];
        $days = $row2[$sumScore];
        $dailyAverage = $unit / $days;
        return $unit;
    }
}

function getstat($model) {
    include("config.php");

    $sql2 = "SELECT * FROM tempStat";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    }
    $result2 = mysqli_query($con, $sql2);
    while ($row2 = mysqli_fetch_array($result2)) {
        $model = ucfirst($model);
        $score = "Score" . $model;
        $sumScore = "SumScore" . $model;
        $unit = $row2[$score];
        $days = $row2[$sumScore];
        $dailyAverage = $unit / $days;
        return $dailyAverage;
    }
}

function editConfigSave($dbname, $dbservername, $dbusername, $dbpassword, $moduleGas, $moduleEE, $moduleWater, $moduleHotWater, $secret, $lastStatistics, $language) {
    if (($dbname!="") && ($dbservername!="") && ($dbusername!="") && ($dbpassword!="") && ($secret!="") && ($lastStatistics!=""))
    {
    $availableModules = unserialize($_COOKIE['available']);
    $availableModulesNames = unserialize($_COOKIE['availableNames']);
    $myfile = fopen("config.php", "w") or die("Unable to open file!");
    $txt = "
        <?php

\$dbservername = \"$dbservername\";
\$dbusername = \"$dbusername\";
\$dbpassword = \"$dbpassword\";
\$dbname = \"$dbname\";
\$con = mysqli_connect(\$dbservername, \$dbusername, \$dbpassword, \$dbname);


\$language=\"$language\";
\$languagefile=\$language . \".php\";
require_once(\"\$languagefile\");

\$lastStatistics = $lastStatistics;

\$secret = \"$secret\";
\$releaseDate = \"2017-08-08\";
\$version = \"2.2\";
\$Author = \"Jakub Sedinar - Sedinar.EU\";
\$link = \"https://sedinar.eu\";
\$logo = \"https://sedinar.eu/logo.png\";
\$availableModules=array(";
    $countOfModules = count($availableModules);
    $i = 0;
    foreach ($availableModules as &$availableModul) {
        $txt .= "\"$availableModul\"";
        if ($i < ($countOfModules - 1)) {
            $txt .= ",";
            $i++;
        }
    }



    $txt .= ");
        
\$availableModulesNames=array(";
    $countOfModulesNames = count($availableModulesNames);
    $i = 0;
    foreach ($availableModulesNames as &$availableModuleName) {
        $txt .= "\"$availableModuleName\"";
        if ($i < ($countOfModulesNames - 1)) {
            $txt .= ",";
            $i++;
        }
    }



    $txt .= ");
\$modules=array();
";
    if ($moduleGas == true) {
        $txt.="array_push(\$modules, \"gas\");
";
    }
    if ($moduleEE == true) {
        $txt.="array_push(\$modules, \"ee\");
";
    }
    if ($moduleWater == true) {
        $txt.="array_push(\$modules, \"water\");
";
    }
    if ($moduleHotWater == true) {
        $txt.="array_push(\$modules, \"hotWater\");
";
    }


    $txt.= "\$moduleGas=";
    if ($moduleGas == true) {
        $txt.="true;
";
    } else {
        $txt.="false;
";
    }
    $txt .= "\$moduleEE=";
    if ($moduleEE == true) {
        $txt.="true;
";
    } else {
        $txt.="false
;";
    }
    $txt .= "\$moduleWater=";
    if ($moduleWater == true) {
        $txt.="true;
";
    } else {
        $txt.="false;
";
    }
    $txt .= "\$moduleHotWater=";
    if ($moduleHotWater == true) {
        $txt.="true;
";
    } else {
        $txt.="false;
";
    }

    $txt .="

?>            ";

    fwrite($myfile, $txt);
    fclose($myfile);
    setcookie("needReload", True, 0, "/");
    echo 'Configuration succesfully saved';}
    else    {
        echo "Something is missing!";
        editConfigShow ();
    }
}

function editConfigShow() {

    include("config.php");
    include("$languagefile");
    
    echo "<div align=\"center\">
        <fieldset style=\"width:30%\"><legend>$editConfigurationLang</legend>
<form method=\"POST\" action=\"index.php?newConfig=true\">
$dbNameLang *<br><input type=\"text\" name=\"dbname\" size=\"40\" placeholder=\"Database name\" value=\"" . $dbname . "\"><br>
$dbServerLang *<br><input type=\"text\" name=\"dbserver\" size=\"40\" placeholder=\"Database server\" value=\"" . $dbservername . "\"><br>
$dbUserLang *<br><input type=\"text\" name=\"dbuser\" size=\"40\" placeholder=\"Database user\" value=\"" . $dbusername . "\"><br>
$dbPasswordLang *<br><input type=\"password\" name=\"dbpass\" size=\"40\" value=\"" . $dbpassword . "\"><br><br>
$statistcsPaginationLang *<br><input type=\"number\" name=\"lastStatistics\" size=\"40\" value=\"" . $lastStatistics . "\"><br><br>
<select name=\"language\">
  <option value=\"sk_SK\""; if ($languageCode == "sk_SK")echo " selected";echo">Slovensky</option>
  <option value=\"en_EN\""; if ($languageCode == "en_EN")echo " selected";echo">English</option>
</select>
<br>
$enabledModulesLang:<br>
<input type=\"checkbox\" name=\"gas\" value=\"true\"";
    if ($moduleGas == true)
        echo "checked=\"checked\"";
    echo"> $gasLang<br>
<input type=\"checkbox\" name=\"ee\" value=\"true\" ";
    if ($moduleEE == true)
        echo "checked=\"checked\"";
    echo"> Elektrina<br>
<input type=\"checkbox\" name=\"water\" value=\"true\" ";
    if ($moduleWater == true)
        echo "checked=\"checked\"";
    echo"> $waterLang<br>
<input type=\"checkbox\" name=\"hotWater\" value=\"true\" ";
    if ($moduleHotWater == true)
        echo "checked=\"checked\"";
    echo"> $hotWaterLang<br>
<br><input type=\"hidden\" name=\"secret\" size=\"40\" value=\"$secret\"><br>
    

<input id=\"button\" type=\"submit\" name=\"submit\" value=\"$changeLang\">
</form>
</fieldset>
</div>
";
    setcookie("available", serialize($availableModules), 0, "/");
    setcookie("availableNames", serialize($availableModulesNames), 0, "/");
}

function drawGraph($module) {

    include("config.php");
    include("$languagefile");
    echo "
    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {";

    echo "var data = new google.visualization.DataTable();
            data.addColumn('date', '$dateLang');
            data.addColumn('number', '$spentLang');
            data.addRows([";
    $sql2 = "SELECT * FROM $module";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result2 = mysqli_query($con, $sql2);
        while ($row2 = mysqli_fetch_array($result2)) {
            $year = substr($row2[date], 0, 4);
            $month = (substr($row2[date], -5, 2)) - 1;
            $day = substr($row2[date], -2, 2);
            echo "

          [new Date($year, $month, $day),  $row2[score]],
          ";
        }
        echo " ]);

                var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div$module'));

        var options = {

          displayAnnotations: true

        };

        chart.draw(data, options);
      }
    </script>
  ";
        echo "    <div id='chart_div$module' style='width: 900px; height: 300px;'></div>";
    }
}

function drawAverageGraph($module) {

    include("config.php");
    include("$languagefile");
    echo "
    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {";

    echo "var data = new google.visualization.DataTable();
            data.addColumn('date', '$dateLang');
            data.addColumn('number', '$spentLang');
            data.addRows([";
    $sql2 = "SELECT * FROM $module";
    $tempDays = 0;
    $lastdate = 0;
    $last = 0;
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result2 = mysqli_query($con, $sql2);
        while ($row2 = mysqli_fetch_array($result2)) {
            $tempDays = $row2[date];
            $days_between = $tempDays - $lastdate;


            $start = strtotime($lastdate);
            $end = strtotime($tempDays);

            $days_between = ceil(abs($end - $start) / 86400);
            $year = substr($row2[date], 0, 4);
            $month = (substr($row2[date], -5, 2)) - 1;
            $day = substr($row2[date], -2, 2);
            $average = (($row2[score] - $last) / $days_between);

            echo "

          [new Date($year, $month, $day),  $average],
          ";
            $lastdate = $row2['date'];
            $last = $row2['score'];
        }
        echo " ]);

                var chart = new google.visualization.AnnotationChart(document.getElementById('chart_divAverage" . $module . "'));

        var options = {

          displayAnnotations: true

        };

        chart.draw(data, options);
      }
    </script>
  ";
        echo "    <div id='chart_divAverage" . $module . "' style='width: 900px; height: 200px;'></div>";
    }
}

function passWordChange() {
    include("config.php");
    include("$languagefile");
    echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>$changePasswordLang <mark> $_COOKIE[name]</mark></legend>
        <form method=\"POST\" action=\"index.php?changedPass=true\">
        $oldPasswordLang *<br><input type=\"password\" name=\"oldPass\" size=\"40\" placeholder=\"$oldPasswordPlaceholderLang\"><br><br>
        $newasswordLang *<br><input type=\"password\" name=\"newPass\" size=\"40\" placeholder=\"$newPasswordPlaceholderLang\"><br><br>
        $newRepeatPasswordLang *<br><input type=\"password\" name=\"repeatPass\" size=\"40\" placeholder=\"$newRepeatPasswordPlaceholderLang\"><br><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"$changePasswordLang\"><div>
        </form>
        </fieldset><div>
";
    if ($_COOKIE["noSame"] == True) {
        echo "<p color=\"red\"> $newasswordMatchLang</p>";
        setcookie("noSame", True, time() - (300), "/");
    }

    if ($_COOKIE["noOld"] == True) {
        echo "<p color=\"red\"> $oldPasswordMatchLang</p>";
        setcookie("noOld", True, time() - (300), "/");
    }
}

function passWordChangeSave($oldPass, $newPass, $RepeatPass) {
    include("config.php");

    $oldPass.=$secret;
    $newPass.=$secret;
    $RepeatPass.=$secret;



    $tmpold = md5($oldPass);
    $tmpnew = md5($newPass);
    $tmpRepeat = md5($RepeatPass);
    $user = $_COOKIE["name"];

    $q2 = "SELECT * FROM users WHERE username='$user'";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result2 = mysqli_query($con, $q2);
    }
    while ($row2 = $result2->fetch_assoc()) {

        $user_pass = $row2['password'];
        if ($user_pass != $tmpold) {
            setcookie("noOld", True, 0, "/");
            echo "<script type=\"text/javascript\">window.location.href = \"/index.php?passchange=true\"</script>";
        } else if ($tmpnew != $tmpRepeat) {
            setcookie("noSame", True, 0, "/");
            echo "<script type=\"text/javascript\">window.location.href = \"/index.php?passchange=true\"</script>";
        } else {

            $sql = "UPDATE users SET password='$tmpnew' WHERE username='$user'";

            if (!mysqli_query($con, $sql)) {
                die('Error: ' . mysqli_error($con));
            }
            setcookie("changed", True, 0, "/");
            echo "<script type=\"text/javascript\">window.location.href = \"/\"</script>";
        }
    }
}

function addRecordShow() {
    include("config.php");
    include("$languagefile");
    echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>$addRecordLang</legend>
        <form method=\"POST\" action=\"index.php?addedRecord=true\">
        $dateLang* <br><input type=\"date\" id=\"today\" name=\"date\" min=\"" . date("Y-m-d") . "\" max=\"2100-12-31\" value=\"" . date("Y-m-d") . "\"><br>";
    if ($moduleGas == true) {
        echo"<input type=\"radio\" name=\"energy\" value=\"gas\"> $gasLang*<br>";
    }
    if ($moduleEE == true) {
        echo"<input type=\"radio\" name=\"energy\" value=\"ee\"> $eeLang*<br>";
    }
    if ($moduleWater == true) {
        echo"<input type=\"radio\" name=\"energy\" value=\"water\"> $waterLang*<br>";
    }
    if ($moduleHotWater == true) {
        echo"<input type=\"radio\" name=\"energy\" value=\"hotWater\">$hotWaterLang*<br><br>";
    }
    echo "$scoreFormLang*<br><input type=\"number\" name=\"score\" size=\"10\"  placeholder=\"$scoreFormPlaceholderLang\" style=\"width: 20em;\" step=0.001><br>
        $inicialLang <input type=\"checkbox\" name=\"inicial\" value=\"0\"><br>
        $noteLang <br><input type=\"text\" name=\"note\" size=\"60\" placeholder=\"$noteLang\"><br><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"$addRecordLang\" align=\"right\"><div>
        </form>
        </fieldset><div>
";
}

function addRecordSave($date, $energy, $score, $inicial, $note) {
    include("config.php");
    include("$languagefile");
if (($date >= getLastDate($energy)) && (($date!="") && ($energy!="") && ($score!=""))){
    if (!$inicial) {
        $inicial = 0;
    }
    $year = substr($date, 0, 4);
    $sql = "INSERT INTO $energy(date, year, score, inicial, note) VALUES('$date', '$year', '$score', '$inicial', '$note')";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    echo "$recordAddedLang <br>";}
else    {
    echo "$missingLang";
    addRecordShow ();
}}

function addUser() {
    include("config.php");
    include("$languagefile");
    echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>$addUser:</legend>
        <form method=\"POST\" action=\"index.php?register=true\">
        $usernameLang *<br><input type=\"text\" name=\"username\" size=\"40\" placeholder=\"Enter username\"><br>
        $passwordLang *<br><input type=\"password\" name=\"password\" size=\"40\" placeholder=\"Enter Password\"><br><br>
        $emailLang <br><input type=\"text\" name=\"email\" size=\"60\" placeholder=\"example@sedinar.eu\"><br><br>
        $noteLang <br><input type=\"text\" name=\"note\" size=\"60\" placeholder=\"Note\"><br><br>
        $groupLang <br><input type=\"number\" name=\"group\" size=\"40\" placeholder=\"$groupPlaceholderLang\"><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"$registerLang\"><div>
        </form>
        </fieldset><div>
";
}

function registerUser($username, $password, $email, $group, $note) {
    include("config.php");
    include("$languagefile");
    $password.=$secret;
    $password = md5($password);
    $noted = "";
    $noted.=$note;
    if ($group == "")
        $group = 1;
    $sql = "INSERT INTO users(username, password, email, groups, note, lastSession) VALUES('$username', '$password', '$email', '$group', '$noted', '')";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    echo "$userAdded1Lang $username $userAdded2Lang";
}

function getHighestid($table) {
    include("config.php");
    $sql = "SELECT * FROM $table ORDER BY id DESC LIMIT 1";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($result)) {
            return $row['id'];
        }
    }
}

function statistics($energy) {
    include("config.php");
    include("$languagefile");
    $lastOne = getHighestid($energy);
    $firstOne = $lastOne - $lastStatistics;
    //$sql = "SELECT * FROM $energy ORDER BY date ASC limit 10 offset $firstOne";
    $sql = "SELECT * FROM $energy";
    //echo $sql;

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $sql);
        $google = "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type=\"text/javascript\">
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '$dateLang');    
        data.addColumn('number', '$daysLang');
        data.addColumn('number', '$scoreStatLang');
        data.addColumn('number', '$usedLang');
        data.addColumn('number', '$dailyAverageFromLastLang');
        data.addColumn('boolean', '$inicialLang');
        data.addColumn('string', '$noteLang');
        data.addRows([
        ";


        $sum = 0;
        $sumDays = 0;
        $temp = 0;
        $last = 0;
        $lastdate = 0;
        $tempDays = 0;
        while ($row = mysqli_fetch_array($result)) {


            $id = $row['id'];


//$inicial="";

            $temp = $row['score'] - $last;
            if ($row['inicial'] == 1) {
                $temp = 0;
            }
            $sum+=$temp;
            $tempDays = $row['date'];
            $days_between = $tempDays - $lastdate;


            $start = strtotime($lastdate);
            $end = strtotime($tempDays);

            $days_between = ceil(abs($end - $start) / 86400);

            if ($row['inicial'] == 1) {
                $sumDays = 0;
                $days_between = 0;
            } else {
                $sumDays+=$days_between;
            }
            if (($temp / $days_between) > (getstat($energy))) {
                $a = "style=\"color: red;\"";
            } else {
                $a = "style=\"color: blue;\"";
            }

            if ($energy == "ee") {
                $unit = "kWh";
            } else
                $unit = "m<sup>3</sup>";
            if (($row['id'] >= $firstOne) && ($row['id'] <= $lastOne)) {
                $year = substr($row[date], 0, 4);
                $month = (substr($row[date], -5, 2)) - 1;
                $day = substr($row[date], -2, 2);
                if ($row[inicial] == 1) {
                    $tmpInicial = "true";
                } else
                    $tmpInicial = "false";

                $google.="[new Date($year, $month, $day),  {v: $days_between, f: '$days_between'},{v: $row[score] , "
                        . "f: '".round($row[score], 2)." $unit'},{v: $temp, f: '$temp $unit'},{v: " . ($temp / $days_between) . ", "
                        . "f: '" . ($temp / $days_between) . $unit . "'}, $tmpInicial, '$row[note]'],
";
            }
            $last = $row['score'];
            $lastdate = $row['date'];
        }
        $google.="
            ]);

        var table = new google.visualization.Table(document.getElementById('table_statistic$energy'));
            
var formatter = new google.visualization.ColorFormat();
formatter.addRange(" . getstat($energy) . ", 1000, 'white', 'red');
formatter.addRange(null, " . getstat($energy) . ", 'black', '#33ff33');
formatter.format(data, 4); // Apply formatter to second column

        table.draw(data, {showRowNumber: true, allowHtml: true});
      }
    </script>
    
<div id=\"table_statistic$energy\" ></div>";
    }
    $energyTemp=$energy;
    $energy = ucfirst($energy);
    $sql = "UPDATE tempStat SET 	user='" . $_COOKIE[name] . "', score$energy=$sum, sumScore$energy=$sumDays WHERE id='1'";
    
    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    echo "<fieldset style=\"margin: 20 30% 50 30%;\"><legend>$statisticsForLang ${$energyTemp."Lang"}</legend> $google ";
    echo $sum. $unit;
    echo" $statistics1Lang ";

    echo $sumDays . $statistics2Lang . getstat($energy) . $unit . "</fieldset>";
}

function showPaymentRecords() {
    include("config.php");
    include("$languagefile");
    $sql2 = "SELECT * FROM paymentRecords ORDER BY id ASC ";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result2 = mysqli_query($con, $sql2);
        $google = "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type=\"text/javascript\">
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '$kindLang');
        data.addColumn('string', '$customerNumberLang');
        data.addColumn('string', '$paymentLang');
        data.addColumn('string', '$tarrifLang');
        data.addColumn('string', '$accountsLang');
        data.addColumn('string', '$vsLang');
        data.addColumn('string', '$csLang');
        data.addColumn('string', '$eicLang');
        data.addColumn('string', '$deliveryPointLang');
        data.addColumn('string', '$consumptionPoingLang');
        data.addColumn('string', '$editLang');
        data.addRows([
";

        while ($row2 = mysqli_fetch_array($result2)) {

            $google.="['$row2[kind]', "
                    . "'$row2[customerNumber]', "
                    . "'$row2[payment] €',"
                    . "'$row2[tariff]', "
                    . "'$row2[bankAccounts]', "
                    . "'$row2[Variable]', "
                    . "'$row2[Constant]', "
                    . "'$row2[EIC]', "
                    . "'$row2[deliveryPoint]', "
                    . "'$row2[consumptionPoint]', "
                    . "'<form method=\"POST\" action=\"index.php?editPaymentRecord=true\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"$row2[id]\"></form>'],
";
        }

        $google.="]);

        var table = new google.visualization.Table(document.getElementById('table_payment_records'));

        table.draw(data, {showRowNumber: true, allowHtml: true});
      }
    </script>
    
<div id=\"table_payment_records\" ></div>";
    }
    echo "<fieldset style=\"margin: 20 20% 50 20%;\"><legend>$showPaymentRecordLang</legend> $google <br> <a href='index.php?addPaymentRecord=true'>$addPaymentRecordLang</a></fieldset>";
}

function addPaymentRecord() {
    include("config.php");
    include("$languagefile");
    echo "<form method=\"POST\" action=\"index.php?addPaymentRecordSave=true\">
$kindLang<br><input type=\"text\" name=\"kind\" size=\"40\" placeholder=\"\" value=\"" . $kind . "\"><br>
$customerNumberLang number<br><input type=\"number\" name=\"customerNumber\" size=\"40\" placeholder=\"\" value=\"" . $customerNumber . "\"><br>
$paymentLang<br><input type=\"text\" name=\"payment\" size=\"40\" placeholder=\"\" value=\"" . $payment . "\"><br>
$tarrifLang<br><input type=\"text\" name=\"tariff\" size=\"40\" placeholder=\"\" value=\"" . $tariff . "\"><br>
$accountsLang<br><input type=\"text\" name=\"bankAccounts\" size=\"40\" placeholder=\"\" value=\"" . $bankAccounts . "\"><br>
$vsLang<br><input type=\"number\" name=\"Variable\" size=\"40\" placeholder=\"\" value=\"" . $Variable . "\"><br>
$csLang<br><input type=\"number\" name=\"Constant\" size=\"40\" placeholder=\"\" value=\"" . $Constant . "\"><br>
$eicLang<br><input type=\"text\" name=\"EIC\" size=\"40\" placeholder=\"\" value=\"" . $EIC . "\"><br>
$deliveryPointLang<br><input type=\"number\" name=\"deliveryPoint\" size=\"40\" placeholder=\"\" value=\"" . $deliveryPoint . "\"><br>
$consumptionPoingLang<br><input type=\"number\" name=\"consumptionPoint\" size=\"40\" placeholder=\"\" value=\"" . $consumptionPoint . "\"><br>

<input id=\"button\" type=\"submit\" name=\"submit\" value=\"$addLang\">
</form>   ";
}

function addPaymentRecordSave($kind, $customerNumber, $payment, $tariff, $bankAccounts, $Variable, $Constant, $EIC, $deliveryPoint, $ConsumptionPoint) {
    include("config.php");
    include("$languagefile");
    if ((isset($kind) && ($kind != '')) && (isset($customerNumber) && ($customerNumber != '')) && (isset($payment) && ($payment != '') ) && (isset($tariff) && ($tariff != '')) && (isset($bankAccounts) && ($bankAccounts != '')) && (isset($Variable) && ($Variable != '')) && (isset($Constant) && ($Constant != '')) && (isset($EIC) && ($EIC != '')) && (isset($deliveryPoint) && ($deliveryPoint != '')) && (isset($ConsumptionPoint) && ($ConsumptionPoint != ''))) {

        $sql = "INSERT INTO paymentRecords(kind, customerNumber, payment, tariff, bankAccounts, Variable, Constant, EIC, deliveryPoint, ConsumptionPoint) VALUES('$kind','$customerNumber','$payment','$tariff','$bankAccounts','$Variable','$Constant','$EIC','$deliveryPoint','$ConsumptionPoint')";


        if (!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        } else
            echo $recordSucessfullyAddedLang;
    }
    else {
        echo "<h1 style='color:red'>$fillAllFieldsLang</h1><br>";
        addPaymentRecord($kind, $customerNumber, $payment, $tariff, $bankAccounts, $Variable, $Constant, $EIC, $deliveryPoint, $ConsumptionPoint);
    }
}

function editPaymentRecord($id) {
    include("config.php");
    include("$languagefile");
    $sql2 = "SELECT * FROM paymentRecords WHERE id=$id";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result2 = mysqli_query($con, $sql2);
        while ($row2 = mysqli_fetch_array($result2)) {
            echo "<form method=\"POST\" action=\"index.php?editPaymentRecordSave=true\">
$kindLang<br><input type=\"text\" name=\"kind\" size=\"40\" placeholder=\"\" value=\"" . $row2["kind"] . "\"><br>
$customerNumberLang<br><input type=\"number\" name=\"customerNumber\" size=\"40\" placeholder=\"\" value=\"" . $row2["customerNumber"] . "\"><br>
$paymentLang<br><input type=\"text\" name=\"payment\" size=\"40\" placeholder=\"\" value=\"" . $row2["payment"] . "\"><br>
$tarrifLang<br><input type=\"text\" name=\"tariff\" size=\"40\" placeholder=\"\" value=\"" . $row2["tariff"] . "\"><br>
$accountsLang<br><input type=\"text\" name=\"bankAccounts\" size=\"40\" placeholder=\"\" value=\"" . $row2["bankAccounts"] . "\"><br>
$vsLang<br><input type=\"number\" name=\"Variable\" size=\"40\" placeholder=\"\" value=\"" . $row2["Variable"] . "\"><br>
$csLang<br><input type=\"number\" name=\"Constant\" size=\"40\" placeholder=\"\" value=\"" . $row2["Constant"] . "\"><br>
$eicLang<br><input type=\"text\" name=\"EIC\" size=\"40\" placeholder=\"\" value=\"" . $row2["EIC"] . "\"><br>
$deliveryPointLang<br><input type=\"number\" name=\"deliveryPoint\" size=\"40\" placeholder=\"\" value=\"" . $row2["deliveryPoint"] . "\"><br>
$consumptionPoingLang<br><input type=\"number\" name=\"consumptionPoint\" size=\"40\" placeholder=\"\" value=\"" . $row2["consumptionPoint"] . "\"><br>
<br><input type=\"hidden\" name=\"id\" size=\"40\" value=\"$row2[id]\"><br>

<input id=\"button\" type=\"submit\" name=\"submit\" value=\"$editLang\">
</form>   ";
        }
    }
}

function editPaymentRecordSave($kind, $customerNumber, $payment, $tariff, $bankAccounts, $Variable, $Constant, $EIC, $deliveryPoint, $ConsumptionPoint, $id) {
    include("config.php");
    include("$languagefile");
    if ((isset($kind) && ($kind != '')) && (isset($customerNumber) && ($customerNumber != '')) && (isset($payment) && ($payment != '') ) && (isset($tariff) && ($tariff != '')) && (isset($bankAccounts) && ($bankAccounts != '')) && (isset($Variable) && ($Variable != '')) && (isset($Constant) && ($Constant != '')) && (isset($EIC) && ($EIC != '')) && (isset($deliveryPoint) && ($deliveryPoint != '')) && (isset($ConsumptionPoint) && ($ConsumptionPoint != ''))) {

        $sql = "UPDATE paymentRecords SET kind='$kind', customerNumber='$customerNumber', payment='$payment', tariff='$tariff',"
                . " bankAccounts='$bankAccounts', Variable='$Variable', Constant=$Constant, EIC='$EIC', deliveryPoint='$deliveryPoint', ConsumptionPoint='$ConsumptionPoint' WHERE id='$id'";

        if (!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        } else
            echo $recordSucessfullyAlteredLang;
    }
    else {
        echo "<h1 style='color:red'>$fillAllFieldsLang</h1><br>";
        editPaymentRecord($id);
    }
}

function getLastDate($energy){
    include("config.php");
    $sql2 = "SELECT * FROM $energy ORDER BY id DESC limit 1";

    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else $result2 = mysqli_query($con, $sql2);
    while ($row2 = mysqli_fetch_array($result2)){
        return $row2[date];
        
    }
    
}
