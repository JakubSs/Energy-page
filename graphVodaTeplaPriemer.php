<div align="center"><?php
    require_once("config.php");
    if (isset($_COOKIE["PHPSESSID"]) && isset($_COOKIE["meno"])) {
        require_once("getstat.php");

        $con = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

        $q = "SELECT lastSession FROM pouzivatelia WHERE meno='" . $_COOKIE["meno"] . "' ";
        if (mysqli_connect_errno($con)) {
            echo "failed connection!";
        } else {
            $result = mysqli_query($con, $q);
        }
        while ($row = $result->fetch_assoc()) {
            echo "  
    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {";
       
        echo "var data = new google.visualization.DataTable();
            data.addColumn('date', 'Dátum');
            data.addColumn('number', 'Stav');
            data.addRows([";
            $sql2 = "SELECT * FROM vodaTepla";
            $tempDni = 0;
            $lastdate = 0;
            if (mysqli_connect_errno($con)) {
                echo "failed connection!";
            } else {
                $result2 = mysqli_query($con, $sql2);
                while ($row2 = mysqli_fetch_array($result2)) {
                    $tempDni = $row2['datum'];
                    $days_betwvodan = $tempDni - $lastdate;
                   

                    $start = strtotime($lastdate);
                    $end = strtotime($tempDni);

                    $days_betwvodan = ceil(abs($end - $start) / 86400);
                    $rok = substr($row2[datum], 0, 4);
                    $mesiac = (substr($row2[datum], -5, 2))-1;
                    $den = substr($row2[datum], -2, 2);
                    $priemer=(($row2[stav]-$lastVodaTepla)/$days_betwvodan);
                   
                    echo "  
          
          [new Date($rok, $mesiac, $den),  $priemer],
          ";
                $lastdateTepla = $row2['datum'];
                $lastVodaTepla = $row2['stav'];
                }
                echo " ]);

                var chart = new google.visualization.AnnotationChart(document.getElementById('chart_divVodaTeplaPriemer'));

        var options = {

          displayAnnotations: true
          
        };

        chart.draw(data, options);
      }
    </script>
  ";
    echo "    <div id='chart_divVodaTeplaPriemer' style='width: 900px; height: 200px;'></div>";
            
                
                }
        }
    } else {
        echo "<script type=\"text/javascript\">
            window.location = \"../\"
            </script>";
    }
    ?>