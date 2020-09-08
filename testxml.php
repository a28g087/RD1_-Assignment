<?php
    require_once("connMysql.php");
    session_start();

    if(isset($_POST['json'])){
        $_SESSION["json"]=$_POST['json'];
    }

    if(isset($_SESSION["json"])){
        $json=$_SESSION["json"];
    }

    if($json!=""){   
        
        echo "<pre>";
        var_dump($json);
        // echo ($json["locationName"]);
        // echo ($json["geocode"]);
        // echo ($json["lat"]);
        // echo ($json["lon"]);
        $sql=sprintf("INSERT INTO location VALUES('%s','%s','%s','%s')",$json["locationName"],$json["geocode"],$json["lat"],$json["lon"]);
        // $db_link->query($sql);
        // $db_link->close();
        echo "</pre>";
    }

?>
