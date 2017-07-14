<?php

require_once("config.php");
require_once("./functions.php");
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
    if (isset($_COOKIE["name2"])) {
        $tmp = $_COOKIE["name2"];
        setcookie("name2", $_COOKIE["name2"], time() - (300), "/");
    }
    echo "
        <div align=\"center\">
        <fieldset style=\"width:30%\"><legend>Log in</legend>
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


    $q = "SELECT * FROM users WHERE username='$user' ";
    if (mysqli_connect_errno($con)) {
        echo "failed connection!";
    } else {
        $result = mysqli_query($con, $q);
    }
    while ($row = $result->fetch_assoc()) {
        $pass .= $secret;
        $user_pass = $row['password'];
        
        if (md5($pass) == $user_pass) {

            session_start();
           setcookie("user_pass", $user_pass, 0, "/");
            $sesID = session_id();
            $sql = "UPDATE users SET lastSession='" . $sesID . "' WHERE username='$user' ";
            setcookie("name", $user, 0, "/");
            if (!mysqli_query($con, $sql)) {
                die('Error: ' . mysqli_error($con));
                
            }
            echo "<script type=\"text/javascript\">
            window.location.href = \"/2.0\"
            </script>";
        }
         
    }
    if ((md5($pass) != $user_pass) || ($user_pass=="")){
            setcookie("wrong", True, 0, "/");
    setcookie("name2", $user, 0, "/");}
    echo '<script type="text/javascript">
           window.location = "/2.0/"
      </script>';
} else if (verificate()==true) {
    echo "logged";
    echo '<script type="text/javascript">
           window.location.href = "/2.0"
      </script>';
} else {

    logout();
}
?>