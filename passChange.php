<div align="center"><?php
    require_once("config.php");
    $user = $_COOKIE["meno"];
    echo "<h1>Zmeniť heslo:</h1>";
    if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && !isset($_POST["newPass"])) {

        echo "
                <div align=\"center\"><fieldset style=\"width:30%\"><legend>Zmeniť heslo používateľovi <mark> $_COOKIE[meno]</mark></legend>
        <form method=\"POST\" action=\"passChange.php\">
        Staré heslo <br><input type=\"password\" name=\"oldPass\" size=\"40\" placeholder=\"Zadaj staré heslo\"><br><br>
        Nové heslo <br><input type=\"password\" name=\"newPass\" size=\"40\" placeholder=\"Zadaj nové heslo\"><br><br>
        Zopakovať nové heslo <br><input type=\"password\" name=\"RepeatPass\" size=\"40\" placeholder=\"Zopakuj nové heslo\"><br><br>
        <div align=\"center\"><input id=\"button\" type=\"submit\" name=\"submit\" value=\"Zmeň heslo\"><div>
        </form>
        </fieldset><div>
";
        if ($_COOKIE["noSame"] == True) {
            echo "<p color=\"red\"> New passwords does not match. Try again.</p>";
            setcookie("noSame", True, time() - (300), "/");
        }

        if ($_COOKIE["noOld"] == True) {
            echo "<p color=\"red\"> Old password does not match. Try again.</p>";
            setcookie("noOld", True, time() - (300), "/");
        }

        echo "<a href='./'>Spat</a><br>";
    } else if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"]) && isset($_POST["newPass"]) && isset($_POST["oldPass"])) {


        $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

        $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
        if (mysqli_connect_errno($con)) {
            echo "failed connection!";
        } else {
            $result = mysqli_query($con, $q);
        }
        while ($row = $result->fetch_assoc()) {
            if ($row['lastSession'] == $_COOKIE["PHPSESSID"]) {
                $oldPass = $_POST["oldPass"];
               
                $newPass = $_POST["newPass"];
               
                $RepeatPass = $_POST["RepeatPass"];
               
                $oldPass.=$secret;
                $newPass.=$secret;
                $RepeatPass.=$secret;
               
               
               
                $tmpold = md5($oldPass);
                $tmpnew = md5($newPass);
                $tmpRepeat = md5($RepeatPass);
               
               
               
               

                $q2 = "SELECT * FROM pouzivatelia WHERE meno='$user'";
                if (mysqli_connect_errno($con)) {
                    echo "failed connection!";
                } else {
                    $result2 = mysqli_query($con, $q2);
                }
                while ($row2 = $result2->fetch_assoc()) {

                    $user_pass = $row2['heslo'];
                    if ($user_pass != $tmpold) {
                        setcookie("noOld", True, 0, "/");
                        echo "<script type=\"text/javascript\">window.location.href = \"/passChange.php\"</script>";
                    } else if ($tmpnew != $tmpRepeat) {
                        setcookie("noSame", True, 0, "/");
                        echo "<script type=\"text/javascript\">window.location.href = \"/passChange.php\"</script>";
                    } else {

                        $sql = "UPDATE pouzivatelia SET heslo='$tmpnew' WHERE meno='$user'";
                       
                        if (!mysqli_query($con, $sql)) {
                            die('Error: ' . mysqli_error($con));
                        }
                        setcookie("changed", True, 0, "/");
                        echo "<script type=\"text/javascript\">window.location.href = \"../\"</script>";
                       
                    }
                }

   
            }
        }
    } else {
       
    }
    ?>
</div>