<?php
if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"])) {

if (isset($_POST["dbname"]) && isset($_POST["dbserver"]) && isset($_POST["dbuser"]) && isset($_POST["dbpass"]) && isset($_POST["submit"]) )
{
    
    
    $dbname=$_POST["dbname"];
    $dbservername=$_POST["dbserver"];
    $dbusername=$_POST["dbuser"];
    $dbpassword=$_POST["dbpass"];
    $modulPlyn=$_POST["plyn"];
    $modulEE=$_POST["ee"];
    $modulVoda=$_POST["voda"];
    $modulVodaTepla=$_POST["vodaTepla"];
    $secret=$_POST["secret"];
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
    if ($modulPlyn==true) $txt.="true;";else $txt.="false;";
    $txt .= "\$modulEE=";
    if ($modulEE==true) $txt.="true;";else $txt.="false;";
    $txt .= "\$modulVoda=";
    if ($modulVoda==true) $txt.="true;";else $txt.="false;";
    $txt .= "\$modulVodaTepla=";
    if ($modulVodaTepla==true) $txt.="true;";else $txt.="false;";
    
$txt .="

?>            "; 

   fwrite($myfile, $txt);
    fclose($myfile);
    

        echo '<script type="text/javascript">
           window.location.href = "/"
      </script>';
    
}
else if (file_exists("config.php")) {
    require_once("config.php");
    echo "<div align=\"center\">
        <fieldset style=\"width:30%\"><legend>Install</legend>
<form method=\"POST\" action=\"editConfig.php\">
Database name <br><input type=\"text\" name=\"dbname\" size=\"40\" placeholder=\"Database name\" value=\"".$dbname."\"><br>
Database server <br><input type=\"text\" name=\"dbserver\" size=\"40\" placeholder=\"Database server\" value=\"".$dbservername."\"><br>
Database user <br><input type=\"text\" name=\"dbuser\" size=\"40\" placeholder=\"Database user\" value=\"".$dbusername."\"><br>
Database password <br><input type=\"password\" name=\"dbpass\" size=\"40\" value=\"".$dbpassword."\"><br><br>
Ktoré moduly zapnúť?
<input type=\"checkbox\" name=\"plyn\" value=\"true\"";if($modulPlyn==true) echo "checked=\"checked\""; echo"> Plyn<br>
<input type=\"checkbox\" name=\"ee\" value=\"true\" ";if($modulEE==true) echo "checked=\"checked\""; echo"> Elektrika<br>
<input type=\"checkbox\" name=\"voda\" value=\"true\" ";if($modulVoda==true) echo "checked=\"checked\""; echo"> Voda<br>
<input type=\"checkbox\" name=\"vodaTepla\" value=\"true\" ";if($modulVodaTepla==true) echo "checked=\"checked\""; echo"> Teplá voda<br>
<br><input type=\"hidden\" name=\"secret\" size=\"40\" value=\"$secret\"><br>

<input id=\"button\" type=\"submit\" name=\"submit\" value=\"Zmeň\">
</form>
</fieldset>
</div>
";
} else {
    echo '<script type="text/javascript">
           window.location.href = "/"
      </script>';
    
}}
else {
    echo '<script type="text/javascript">
           window.location.href = "/"
</script>';}
?>