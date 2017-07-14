<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
setcookie("PHPSESSID", "", time() - (300), "/"); 
setcookie("meno", "", time() - (300), "/"); 
echo "<script type=\"text/javascript\">
            window.location.href = \"../\"
            </script>";
?>
