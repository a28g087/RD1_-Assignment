<?php
        $user = $_POST["username"];
        $pas = $_POST["password"];
        $json = array("username"=>$user, "password"=>$pas);   //組合成json陣列
        $date = json_encode($json);  //編譯陣列轉化為json資料
        echo $date;  //將json資料傳回網頁
?>