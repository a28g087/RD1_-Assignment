<?php
    require_once("connMysql.php");
    session_start();
    if(isset($_POST["refresh"])){
        unset($_SESSION["jsonurl_rain"]);
        exit();
    }
    
    if(isset($_POST['jsonurl_rain'])){
        $_SESSION["jsonurl_rain"]=$_POST['jsonurl_rain'];
    }

    if(isset($_SESSION["jsonurl_rain"])){
        $json=$_SESSION["jsonurl_rain"];
    }

    

?>
<form action="" method="post">
<input type="submit" name="refresh" id="refresh" value="REFRESH"/>
</form>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if($json!=""){   
        
            echo "<pre>";
            echo $json["locationName"]."<br>";
            echo $json["parameter"][0]["parameterValue"]."<br>";
            echo $json["weatherElement"][7]["elementValue"]."<br>";
            echo $json["weatherElement"][6]["elementValue"]."<br>";
            //var_dump($json);
            echo "<table border='1px'>";
            echo "<tr><td>觀測縣市</td><td>觀測地點</td><td>過去1小時累積雨量</td><td>過去24小時累積雨量</td></tr>";
            echo "<tr><td>{$json["parameter"][0]["parameterValue"]}</td>
                        <td>{$json["locationName"]}</td>
                        <td>{$json["weatherElement"][7]["elementValue"]}</td>
                        <td>{$json["weatherElement"][6]["elementValue"]}</td></tr>";
            $sql=sprintf("SELECT * FROM Rain 
                            WHERE city='%s' AND locationName='%s'"
                            ,$json["parameter"][0]["parameterValue"],$json["locationName"]);
            //echo $sql;
            $result=$db_link->query($sql);
            if($result->num_rows>0){
                $sql=sprintf("UPDATE Rain SET 
                                HOUR_1='%s',HOUR_24='%s' 
                                WHERE city='%s' AND locationName='%s'",
                                $json["weatherElement"][7]["elementValue"],$json["weatherElement"][6]["elementValue"],
                                $json["parameter"][0]["parameterValue"],$json["locationName"]);
                //echo $sql."<br>";
                $db_link->query($sql);
            }
            elseif($result->num_rows==0){
                $sql=sprintf("INSERT INTO Rain VALUES(
                                '%s','%s','%s','%s')",
                                $json["parameter"][0]["parameterValue"],$json["locationName"],
                                $json["weatherElement"][7]["elementValue"],$json["weatherElement"][6]["elementValue"]
                                );
                            //echo $sql."<br>";
                $db_link->query($sql);
            }
            echo "</pre>";
            echo "</table>";
            
        }
    ?>
</body>
</html>