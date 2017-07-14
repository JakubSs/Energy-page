<?php

require_once("config.php");
if (!isset($_POST["user"])) {
    ($user = $_GET["user"]);
} else {
    $user = ($_POST["user"]);
}

$pass = ($_POST["pass"]);

$cookie_name = "logged";
//$cookie_value=1;
//$logged=$_COOKIE[logged];
if (!isset($_POST["user"]) && !isset($_COOKIE["PHPSESSID"])) {
    if (isset($_COOKIE["meno2"])) {
        $tmp = $_COOKIE["meno2"];
        setcookie("meno2", $_COOKIE["meno2"], time() - (300), "/");
    }
    echo "
        <div align=\"center\">
        <fieldset style=\"width:30%\"><legend>Prihlás sa</legend>
<form method=\"POST\" action=\"login.php\">
User <br><input type=\"text\" name=\"user\" size=\"40\" value=\"$tmp\"><br>
Password <br><input type=\"password\" name=\"pass\" size=\"40\"><br><br>
";
    if ($_COOKIE["wrong"] == True) {
        echo "<p color=\"red\"> Wrong username or password. Try again.</p>";
    }
    echo "
<input id=\"button\" type=\"submit\" name=\"submit\" value=\"Log-In\">
</form>
</fieldset>
</div>
    ";
    setcookie("wrong", True, time() - (300), "/");
} else if (isset($_POST["user"]) && !isset($_COOKIE["wrong"])) {

    $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

    $q = "SELECT * FROM pouzivatelia WHERE meno='$user' ";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $q);
    }
    while ($row = $result->fetch_assoc()) {
        $pass .= $secret;
        $user_pass = $row['heslo'];
        if (md5($pass) == $user_pass) {

            session_start();
           
            $sesID = session_id();
            $sql = "UPDATE pouzivatelia SET lastSession='" . $sesID . "' WHERE meno='$user' ";
            setcookie("meno", $user, 0, "/");
            if (!mysqli_query($con, $sql)) {
                die('Error: ' . mysqli_error($con));
            }
            echo "<script type=\"text/javascript\">
            window.location.href = \"/\"
            </script>";
        }
    }
    setcookie("wrong", True, 0, "/");
    setcookie("meno2", $user, 0, "/");
    echo '<script type="text/javascript">
           window.location = "../"
      </script>';
} else if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"])) {
    echo "logged";
    echo '<script type="text/javascript">
           window.location.href = "/"
      </script>';
} else {

   

    echo '<script type="text/javascript">
           window.location.href = "/energie/logout.php"
      </script>';
}
?>