<?php
    require_once("connMysql.php");
    session_start();
    if(isset($_POST["refresh"])){
        unset($_SESSION["json"]);
        exit();
    }
    
    if(isset($_POST['json'])){
        $_SESSION["json"]=$_POST['json'];
    }

    if(isset($_SESSION["json"])){
        $json=$_SESSION["json"];
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
            $locationName=$json["locationName"];
            $UVI=($json["weatherElement"][9]);
            $uvi_length=count($UVI["time"]);
            //echo $uvi_length;
            $uvi=0;
            echo "<table border='1px'>";
            echo "<tr><td align='center' colspan='".(count($json["weatherElement"])+2)."'>{$json["locationName"]}</td></tr>";
            echo "<tr>";
            echo "<td>startTime</td><td>endTime</td>";
            for($i=0;$i<count($json["weatherElement"]);$i++){
                echo "<td>{$json["weatherElement"][$i]["elementName"]}</td>";
            }
            echo "</tr>";
            for($i=0;$i<count($json["weatherElement"][0]["time"]);$i++){ //i=0...13
                echo "<tr>";
                echo "<td>".$json["weatherElement"][0]["time"][$i]["startTime"]."</td>";
                echo "<td>".$json["weatherElement"][0]["time"][$i]["endTime"]."</td>"; 
                $startTime=$json["weatherElement"][0]["time"][$i]["startTime"];
                $endTime=$json["weatherElement"][0]["time"][$i]["endTime"];              
                for($j=0;$j<count($json["weatherElement"]);$j++){ //j=0...14
                    if($json["weatherElement"][$j]["elementName"]=="UVI"){
                        if($uvi<$uvi_length){
                            if($json["weatherElement"][0]["time"][$i]["startTime"]==$UVI["time"][$uvi]["startTime"]){
                                echo "<td>".$json["weatherElement"][$j]["time"][$uvi]["elementValue"][0]["value"]."</td>";
                                $uvi++;
                                $weatherElement[$json["weatherElement"][$j]["elementName"]]=
                                    $json["weatherElement"][$j]["time"][$uvi-1]["elementValue"][0]["value"];
                                //echo $weatherElement[$json["weatherElement"][$i]["elementName"]];
                            }
                            else{
                                echo "<td>夜晚沒有紫外線</td>";
                                $weatherElement[$json["weatherElement"][$j]["elementName"]]="夜晚沒有紫外線";
                                //echo $weatherElement[$json["weatherElement"][$i]["elementName"]];
                            }
                        }
                        else{
                            echo "<td>夜晚沒有紫外線</td>";
                            $weatherElement[$json["weatherElement"][$j]["elementName"]]="夜晚沒有紫外線";
                            //echo $weatherElement[$json["weatherElement"][$i]["elementName"]];
                        }
                    }
                    else{
                        echo "<td>".$json["weatherElement"][$j]["time"][$i]["elementValue"][0]["value"]."</td>";
                        $weatherElement[$json["weatherElement"][$j]["elementName"]]=$json["weatherElement"][$j]["time"][$i]["elementValue"][0]["value"];
                        //$weatherElement[$json["weatherElement"][$i]["elementName"]]=$json["weatherElement"][$j]["time"][$i]["elementValue"][0]["value"];
                        //echo $weatherElement[$json["weatherElement"][$i]["elementName"]];
                    }
                    
                }
                // echo $weatherElement["PoP12h"]," ",$weatherElement["T"]," ",$weatherElement["MaxT"]
                // ," ",$weatherElement["MinT"]," ",$weatherElement["MaxAT"]," ",$weatherElement["MinAT"]
                // ," ",$weatherElement["Td"]," ",$weatherElement["UVI"]
                // ," ",$weatherElement["RH"]," ",$weatherElement["MaxCI"]," ",$weatherElement["MinCI"]
                // ," ",$weatherElement["WS"]," ",$weatherElement["Wx"]," ",$weatherElement["WeatherDescription"]
                // ," ",$weatherElement["WD"],"<br>";
                //var_dump($weatherElement);
                $sql=sprintf("SELECT * FROM Weather 
                                WHERE locationName='%s' AND startTime='%s'",$locationName,$startTime);
                $result=query($sql);
                if(mysqli_num_rows($result)>0){
                    $sql=sprintf("UPDATE Weather SET 
                        locationName='%s',startTime='%s',endTime='%s',PoP12h='%s',
                        T='%s',MaxT='%s',MinT='%s',MaxAT='%s',MinAT='%s',Td='%s',UVI='%s',RH='%s',MaxCI='%s',
                        MinCI='%s',WS='%s',Wx='%s',WeatherDescription='%s',WD='%s'",
                        $locationName,$startTime,$endTime,$weatherElement["PoP12h"],
                        $weatherElement["T"],$weatherElement["MaxT"],$weatherElement["MinT"],
                        $weatherElement["MaxAT"],$weatherElement["MinAT"],$weatherElement["Td"],
                        $weatherElement["UVI"],$weatherElement["RH"],$weatherElement["MaxCI"],
                        $weatherElement["MinCI"],$weatherElement["WS"],$weatherElement["Wx"],
                        $weatherElement["WeatherDescription"],$weatherElement["WD"]);
                    //echo $sql."<br>";
                    $db_link->query($sql);
                }
                elseif(mysqli_num_rows($result)==0){
                    $sql=sprintf("INSERT INTO Weather VALUES(
                        '%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s')",
                        $locationName,$startTime,$endTime,$weatherElement["PoP12h"],
                        $weatherElement["T"],$weatherElement["MaxT"],$weatherElement["MinT"],
                        $weatherElement["MaxAT"],$weatherElement["MinAT"],$weatherElement["Td"],
                        $weatherElement["UVI"],$weatherElement["RH"],$weatherElement["MaxCI"],
                        $weatherElement["MinCI"],$weatherElement["WS"],$weatherElement["Wx"],
                        $weatherElement["WeatherDescription"],$weatherElement["WD"]);
                    //echo $sql."<br>";
                    $db_link->query($sql);
                }
                echo "</tr>";
            }
            echo "</pre>";
            echo "</table>";
            
        }
    ?>
</body>
</html>