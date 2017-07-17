<?php

function returnAvailableModules() {
    return $availableModules = array("gas", "ee", "water", "hotWater");
}

function returnAvailableModulesNames() {
    return $availableModulesNames = array("Gas", "Electricity", "Water", "Hot water");
}

if (isset($_POST["dbname"]) && isset($_POST["dbserver"]) && isset($_POST["dbuser"]) && isset($_POST["dbpass"]) && isset($_POST["user"]) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["secret"])) {
    $availableModules = returnAvailableModules();
    $availableModulesNames = returnAvailableModulesNames();
    $countOfModules = count($availableModules);
    $countOfModulesNames = count($availableModulesNames);
    $i = 0;
    $availableModulesPrint = "\$availableModules=array(";
    $countOfModulesNamesPrint = "\$availableModulesNames=array(";
    for ($i = 0; $i < $countOfModules; $i++) {
        
            $availableModulesPrint.= "\"$availableModules[$i]\"";
            $countOfModulesNamesPrint.="\"$availableModulesNames[$i]\"";
            if ($i < ($countOfModules - 1)) {
                $availableModulesPrint .= ",";
                $countOfModulesNamesPrint .= ",";
            }
        
        echo "<input type=\"checkbox\" name=\"$availableModules[$i]\" value=\"true\"> $availableModulesNames[$i]<br>";
    }
    $availableModulesPrint.= ");";
    $countOfModulesNamesPrint.=");";

    $dbname = $_POST["dbname"];
    $dbservername = $_POST["dbserver"];
    $dbusername = $_POST["dbuser"];
    $dbpassword = $_POST["dbpass"];
    $user = $_POST["user"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $secret = $_POST["secret"];
    $lastStatistics = $_POST["lastStatistics"];
    $moduleGas = $_POST["gas"];
    $moduleEE = $_POST["ee"];
    $moduleWater = $_POST["water"];
    $moduleHotWater = $_POST["hotWater"];
    $myfile = fopen("config.php", "w") or die("Unable to open file!");
    $txt = "
        <?php

\$dbservername = \"$dbservername\";
\$dbusername = \"$dbusername\";
\$dbpassword = \"$dbpassword\";
\$dbname = \"$dbname\";
\$con = mysqli_connect(\$dbservername, \$dbusername, \$dbpassword, \$dbname);

\$lastStatistics = $lastStatistics;

\$secret = \"$secret\";
\$releaseDate = \"2017-07-18\";
\$version = \"2.1\";
\$Author = \"Jakub Sedinar - Sedinar.EU\";
\$link = \"https://sedinar.eu\";
\$logo = \"https://sedinar.eu/logo.png\";
$availableModulesPrint
$countOfModulesNamesPrint
\$modules=array();";
    if ($moduleGas == true) {
        $txt.="array_push(\$modules, \"gas\");";
    }
    if ($moduleEE == true) {
        $txt.="array_push(\$modules, \"ee\");";
    }
    if ($moduleWater == true) {
        $txt.="array_push(\$modules, \"water\");";
    }
    if ($moduleHotWater == true) {
        $txt.="array_push(\$modules, \"hotWater\");";
    }


    $txt.= "\$moduleGas=";
    if ($moduleGas == true) {
        $txt.="true;";
    } else {
        $txt.="false;";
    }
    $txt .= "\$moduleEE=";
    if ($moduleEE == true) {
        $txt.="true;";
    } else {
        $txt.="false;";
    }
    $txt .= "\$moduleWater=";
    if ($moduleWater == true) {
        $txt.="true;";
    } else {
        $txt.="false;";
    }
    $txt .= "\$moduleHotWater=";
    if ($moduleHotWater == true) {
        $txt.="true;";
    } else {
        $txt.="false;";
    }

    $txt .="

?>            ";
    fwrite($myfile, $txt);
    fclose($myfile);
    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);



    $tmp = $pass;
    $tmp.=$secret;


    $password = md5($tmp);

    $availableModules = returnAvailableModules();
    $availableModulesNames = returnAvailableModulesNames();
    $countOfModules = count($availableModules);
    $countOfModulesNames = count($availableModulesNames);



    for ($i = 0; $i < $countOfModules; $i++) {
        $localModul = $availableModules[$i];
        $sql = "CREATE TABLE `$localModul` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `year` year(4) NOT NULL,
  `score` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `note` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

        if (!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        }

        $sql = "ALTER TABLE `$localModul`
  ADD PRIMARY KEY (`id`);";

        if (!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        }

        $sql = "ALTER TABLE `$localModul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

        if (!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        }
    }

    $sql = "CREATE TABLE `tempStat` (
  `id` int(11) NOT NULL,
  `user` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
";

    for ($i = 0; $i < $countOfModules; $i++) {
        $localModul = ucfirst($availableModules[$i]);
        $sql .=" `Score$localModul` int(11) NOT NULL,
`SumScore$localModul` int(11) NOT NULL
";
        if ($i < ($countOfModules - 1)) {
            $sql .= ", 
";
        }
    }

    $sql .="
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";
    echo $sql;

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    $sql = "ALTER TABLE `tempStat`
  ADD UNIQUE KEY `id` (`id`);";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }



    $sql = "CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text COLLATE utf8_slovak_ci NOT NULL,
  `password` text COLLATE utf8_slovak_ci NOT NULL,
  `email` text COLLATE utf8_slovak_ci NOT NULL,
  `groups` tinyint(4) NOT NULL,
  `note` varchar(300) COLLATE utf8_slovak_ci NOT NULL,
  `lastSession` text COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    $sql = "ALTER TABLE `users`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    $sql = "ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    

    $sql = "CREATE TABLE `paymentRecords` (
  `id` int(11) NOT NULL,
  `kind` text COLLATE utf8_slovak_ci NOT NULL,
  `customerNumber` bigint(12) NOT NULL,
  `payment` text COLLATE utf8_slovak_ci NOT NULL,
  `tariff` text COLLATE utf8_slovak_ci NOT NULL,
  `bankAccounts` text COLLATE utf8_slovak_ci NOT NULL,
  `Variable` bigint(12) NOT NULL,
  `Constant` int(11) NOT NULL,
  `EIC` text COLLATE utf8_slovak_ci NOT NULL,
  `deliveryPoint` bigint(12) NOT NULL,
  `consumptionPoint` bigint(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    $sql = "ALTER TABLE `paymentRecords`
  ADD PRIMARY KEY (`id`);";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }

    $sql = "ALTER TABLE `paymentRecords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    $tmp = $pass;
    $tmp.=$secret;
    $password = md5($tmp);
    $sql = "INSERT INTO users(username, password, email, groups, note, lastSession) VALUES('$user', '$password', '$email', '1', '', '')";
    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }
    $sql = "INSERT INTO tempStat(id, user, ScoreGas, SumScoreGas, ScoreEe, SumScoreEe, ScoreWater, SumScoreWater, ScoreHotWater, SumScoreHotWater) VALUES('1', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
    if (!mysqli_query($con, $sql)) {
        die('Error: ' . mysqli_error($con));
    }



    echo '<script type="text/javascript">
           window.location.href = "/"
      </script>';
} else if (file_exists("config.php")) {
    echo '<script type="text/javascript">
           window.location.href = "/"
      </script>';
} else {
    $availableModules = returnAvailableModules();
    $availableModulesNames = returnAvailableModulesNames();
    $secret = md5(microtime() . rand());
    if (!isset($_POST["lastStatistics"])) {
        $tmp12 = 10;
    }
    echo "<div align=\"center\">
        <fieldset style=\"width:30%\"><legend>Install</legend>
<form method=\"POST\" action=\"install.php\">
Database name <br><input type=\"text\" name=\"dbname\" size=\"40\" placeholder=\"Database name\" value=\"" . $_POST["dbname"] . "\"><br>
Database server <br><input type=\"text\" name=\"dbserver\" size=\"40\" placeholder=\"Database server\" value=\"" . $_POST["dbserver"] . "\"><br>
Database user <br><input type=\"text\" name=\"dbuser\" size=\"40\" placeholder=\"Database user\" value=\"" . $_POST["dbuser"] . "\"><br>
Database password <br><input type=\"password\" name=\"dbpass\" size=\"40\"><br><br>
Login user <br><input type=\"text\" name=\"user\" size=\"40\" placeholder=\"Login user\" value=\"" . $_POST["user"] . "\"><br>
email <br><input type=\"text\" name=\"email\" size=\"60\" placeholder=\"example@sedinar.eu\"><br><br>
Login Password <br><input type=\"password\" name=\"pass\" size=\"40\"><br><br>
<br><input type=\"hidden\" name=\"secret\" size=\"40\" value=\"$secret\"><br>
Statistics pagination <br><input type=\"number\" name=\"lastStatistics\" size=\"40\" value=\"" . $tmp12 . $_POST["lastStatistics"] . "\"><br><br>
            Which modules you want to use?";

    $countOfModules = count($availableModules);
    $countOfModulesNames = count($availableModulesNames);
    $i = 0;
    for ($i = 0; $i < $countOfModules; $i++) {
        echo "<input type=\"checkbox\" name=\"$availableModules[$i]\" value=\"true\"> $availableModulesNames[$i]<br>";
    }



    echo "
<input id=\"button\" type=\"submit\" name=\"submit\" value=\"Install\">
</form>
</fieldset>
</div>
";
}
?>