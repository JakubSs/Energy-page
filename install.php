<?php

if (isset($_POST["dbname"]) && isset($_POST["dbserver"]) && isset($_POST["dbuser"]) && isset($_POST["dbpass"]) && isset($_POST["user"]) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["secret"])) {


    $dbname = $_POST["dbname"];
    $dbservername = $_POST["dbserver"];
    $dbusername = $_POST["dbuser"];
    $dbpassword = $_POST["dbpass"];
    $user = $_POST["user"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $secret = $_POST["secret"];
    $modulPlyn = $_POST["plyn"];
    $modulEE = $_POST["ee"];
    $modulVoda = $_POST["voda"];
    $myfile = fopen("config.php", "w") or die("Unable to open file!");
    $txt = "
        <?php

\$dbservername = \"$dbservername\";
\$dbusername = \"$dbusername\";
\$dbpassword = \"$dbpassword\";
\$dbname = \"$dbname\";

\$secret = \"$secret\";
\$releaseDate = \"2017-07-02\";
\$version = \"1.1\";
\$Author = \"Jakub Sedinar - Sedinar.EU\";
\$link = \"https://sedinar.eu\";
\$logo = \"https://sedinar.eu/logo.png\";
\$modulPlyn=";
    if ($modulPlyn == true)
        $txt.="true;";
    else
        $txt.="false;";
    $txt .= "
        \$modulEE=";
    if ($modulEE == true)
        $txt.="true;";
    else
        $txt.="false;";
    $txt .= "
        \$modulVoda=";
    if ($modulVoda == true)
        $txt.="true;";
    else
        $txt.="false;";

    $txt .="

?>            ";
    fwrite($myfile, $txt);
    fclose($myfile);
    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);



    $tmp = $pass;
    $tmp.=$secret;


    $heslo = md5($tmp);

    $sql = "CREATE TABLE `ee` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `ee`
  ADD PRIMARY KEY (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `ee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "CREATE TABLE `plyn` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `plyn`
  ADD PRIMARY KEY (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `plyn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "CREATE TABLE `voda` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `voda`
  ADD PRIMARY KEY (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `voda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }
  
$sql = "CREATE TABLE `vodaTepla` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `rok` year(4) NOT NULL,
  `stav` int(11) NOT NULL,
  `inicial` tinyint(1) DEFAULT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `vodaTepla`
  ADD PRIMARY KEY (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `vodaTepla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "CREATE TABLE `pouzivatelia` (
  `id` int(11) NOT NULL,
  `meno` text COLLATE utf8_slovak_ci NOT NULL,
  `heslo` text COLLATE utf8_slovak_ci NOT NULL,
  `email` text COLLATE utf8_slovak_ci NOT NULL,
  `skupina` tinyint(4) NOT NULL,
  `poznamka` varchar(300) COLLATE utf8_slovak_ci NOT NULL,
  `lastSession` text COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `pouzivatelia`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `pouzivatelia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "CREATE TABLE `tempStat` (
  `id` int(11) NOT NULL,
  `user` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
  `stavPlyn` int(11) NOT NULL,
  `sumDniPlyn` int(11) NOT NULL,
  `stavEE` int(11) NOT NULL,
  `sumDniEE` int(11) NOT NULL,
  `stavVoda` int(11) NOT NULL,
  `sumDniVoda` int(11) NOT NULL,
  `stavVodaTepla` int(11) NOT NULL,
  `sumDniVodaTepla` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `tempStat`
  ADD UNIQUE KEY `id` (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "CREATE TABLE `udajePlatieb` (
  `id` int(11) NOT NULL,
  `druh` text COLLATE utf8_slovak_ci NOT NULL,
  `zakCislo` bigint(12) NOT NULL,
  `platba` text COLLATE utf8_slovak_ci NOT NULL,
  `tarifa` text COLLATE utf8_slovak_ci NOT NULL,
  `ucty` text COLLATE utf8_slovak_ci NOT NULL,
  `VS` bigint(12) NOT NULL,
  `KS` int(11) NOT NULL,
  `EIC` text COLLATE utf8_slovak_ci NOT NULL,
  `odberMiesto` bigint(12) NOT NULL,
  `miestoSpotreby` bigint(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `udajePlatieb`
  ADD PRIMARY KEY (`id`);";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }

$sql = "ALTER TABLE `udajePlatieb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
  }
  $tmp = $pass;
  $tmp.=$secret;
  $heslo = md5($tmp);
$sql = "INSERT INTO pouzivatelia(meno, heslo, email, skupina, poznamka, lastSession) VALUES('$user', '$heslo', '$email', '1', '', '')";
if (!mysqli_query($con, $sql)) {
   die('Error: ' . mysqli_error($con));
}
$sql = "INSERT INTO tempStat(id, user, stavPlyn, sumDniPlyn, stavEE, sumDniEE, stavVoda, sumDniVoda, stavVodaTepla, sumDniVodaTepla) VALUES('1', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
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
    $secret = md5(microtime() . rand());
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
            Ktoré moduly zapnúť?
<input type=\"checkbox\" name=\"plyn\" value=\"true\"";
    if ($modulPlyn == true)
        echo "checked=\"checked\"";
    echo"> Plyn<br>
<input type=\"checkbox\" name=\"ee\" value=\"true\" ";
    if ($modulEE == true)
        echo "checked=\"checked\"";
    echo"> Elektrika<br>
<input type=\"checkbox\" name=\"voda\" value=\"true\" ";
    if ($modulVoda == true)
        echo "checked=\"checked\"";
    echo"> Voda<br>
    <input type=\"checkbox\" name=\"voda\" value=\"true\" ";
    if ($modulVodaTepla == true)
        echo "checked=\"checked\"";
    echo">Teplá voda<br>; ";


    echo "
<input id=\"button\" type=\"submit\" name=\"submit\" value=\"Install\">
</form>
</fieldset>
</div>
";
}
?>