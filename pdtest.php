<?php
    session_start();
    if(isset($_GET["text"])){
        echo $_GET["text"];
    }
?>

<script src="./jquery-3.5.1.min.js"></script>
<script>

    function refresh(){}

    $(document).ready(function(){
        $.ajax({
            url:"https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-77A32A88-7BED-4DC6-805A-E3A2CBEBA227&format=JSON",
            type:"get",
            success:function(jsonurl){         
                var location=jsonurl["records"]["locations"][0]["location"]; 
                console.log(location);       
                for(let i=0;i<location.length;i++){
                    $('#locationName').append(
                        $('<option></option>').html(location[i]["locationName"]).val(location[i]["locationName"])
                    );
                }
                var locationName=$('#locationName').val();
                var selectime=$('#selectime').val();
                var img = $("#show");
                img.removeClass("hide");
				img.attr("src", "./img/"+locationName+".jpeg");
                var countime;
                if(selectime=="當前天氣預報"){
                    countime=1;
                }
                else if(selectime=="未來兩天天氣預報"){
                    countime=4;
                }
                else if(selectime=="未來一週天氣預報"){
                    countime=14;
                }
                for(let i=0;i<location.length;i++){
                    if(locationName==location[i]["locationName"]){     // 找出當前option的值(locationName)
                        var weatherElement=location[i]["weatherElement"];     // 當前locationName的天氣元素  
                        $(".remove_tr").remove();
                        //console.log(weatherElement);
                        $('#showtable').append(
                            $('<tr class="location_tr"></tr>').append(
                                $("<td class='location_td' align='center'></td>").html(location[i]["locationName"]+"天氣").attr("colspan",weatherElement.length+1)
                            ),
                            $("<tr class='weatherElement'></tr>").append(
                                $("<th></th>").append(
                                    $("<div class='out'><div>").append(
                                        $("<b></b>").html("Weather"),
                                        $("<em></em>").html("Time")
                                    )
                                )
                            )
                        );
                       
                        //$('#showtable').append("<tr class='weatherElement'></tr>");
                        for(let j=0;j<weatherElement.length;j++){
                            $('.weatherElement').append(
                                $('<td></td>').html(weatherElement[j]['description'])
                            );
                        }
                        //weatherElement=location[i]["weatherElement"]
                        var time0=weatherElement[0]["time"];
                        var UVI=weatherElement[9];
                        var uvi_i=0;
                        var countday=0;
                        for(let t=0;t<time0.length;t++){
                            if(countday==countime){
                                break;
                            }
                            $('#showtable').append(
                                $("<tr class='remove_tr' id='tr"+t+"'></tr>").append(
                                    $("<td align='center'></td>").html(time0[t]["startTime"]+" ~<br>"+time0[t]["endTime"])
                                )
                            );
                            uvi_length=weatherElement[9]["time"].length;
                            for(let j=0;j<weatherElement.length;j++){ 
                                if(weatherElement[j]["elementName"]=="UVI"){
                                    if(uvi_i<uvi_length){
                                        var a=UVI["time"][uvi_i]["startTime"];
                                        var b=time0[t]["startTime"];
                                        if(a.indexOf(b)!=-1){
                                            $("#tr"+t).append(
                                                $("<td align='center'></td>").html(weatherElement[j]["time"][uvi_i]["elementValue"][0]["value"])
                                            )
                                            uvi_i++;
                                        }
                                        else{
                                            $("#tr"+t).append(
                                                $("<td align='center'></td>").html("夜晚沒有紫外線")
                                            )
                                        }
                                    }
                                    else{
                                            $("#tr"+t).append(
                                            $("<td align='center'></td>").html("夜晚沒有紫外線")
                                        )
                                    } 
                                }
                                else{
                                    $("#tr"+t).append(
                                        $("<td align='center'></td>").html(weatherElement[j]["time"][t]["elementValue"][0]["value"])
                                    )
                                }
                                
                            }
                            countday++;
                        }                       
                    }
                }
                
                $( "#selectime" ).change(function() {
                    var location=jsonurl["records"]["locations"][0]["location"]; 
                    var locationName=$('#locationName').val();
                    var selectime=$('#selectime').val();
                    var countime;
                    var countday=0;
                    if(selectime=="當前天氣預報"){
                        countime=1;
                    }
                    else if(selectime=="未來兩天天氣預報"){
                        countime=4;
                    }
                    else if(selectime=="未來一週天氣預報"){
                        countime=14;
                    }
                    var weatherElement=location[0]["weatherElement"]; 
                    $(".location_td").text(locationName+"天氣");
                    //alert(locationName);
                    var location=jsonurl["records"]["locations"][0]["location"]; 
                    for(let i=0;i<location.length;i++){
                        if(locationName==location[i]["locationName"]){     // 找出當前option的值(locationName)
                            var weatherElement=location[i]["weatherElement"];     // 當前locationName的天氣元素  
                            $(".remove_tr").remove();
                            //weatherElement=location[i]["weatherElement"]
                            var time0=weatherElement[0]["time"];
                            var UVI=weatherElement[9];
                            var uvi_i=0;
                            for(let t=0;t<time0.length;t++){
                                if(countday==countime){
                                    break;
                                }
                                $('#showtable').append(
                                    $("<tr class='remove_tr' id='tr"+t+"'></tr>").append(
                                        $("<td align='center'></td>").html(time0[t]["startTime"]+" ~<br>"+time0[t]["endTime"])
                                    )
                                );
                                uvi_length=weatherElement[9]["time"].length;
                                for(let j=0;j<weatherElement.length;j++){
                                    if(weatherElement[j]["elementName"]=="UVI"){
                                        if(uvi_i<uvi_length){
                                            var a=UVI["time"][uvi_i]["startTime"];
                                            var b=time0[t]["startTime"];
                                            if(a.indexOf(b)!=-1){
                                                $("#tr"+t).append(
                                                    $("<td align='center'></td>").html(weatherElement[j]["time"][uvi_i]["elementValue"][0]["value"])
                                                )
                                                uvi_i++;
                                            }
                                            else{
                                                $("#tr"+t).append(
                                                    $("<td align='center'></td>").html("夜晚沒有紫外線")
                                                )
                                            }
                                        }
                                        else{
                                                $("#tr"+t).append(
                                                $("<td align='center'></td>").html("夜晚沒有紫外線")
                                            )
                                        } 
                                    }
                                    else{
                                        $("#tr"+t).append(
                                            $("<td align='center'></td>").html(weatherElement[j]["time"][t]["elementValue"][0]["value"])
                                        )
                                    }
                                }
                                countday++;
                            }                       
                        }
                    }
                });


                //========================select is change
                $( "#locationName" ).change(function() {
                    var location=jsonurl["records"]["locations"][0]["location"]; 
                    var locationName=$('#locationName').val();
                    var selectime=$('#selectime').val();
                    var img = $("#show");
                    img.removeClass("hide");
                    img.attr("src", "./img/"+locationName+".jpeg");
                    var countime;
                    var countday=0;
                    if(selectime=="當前天氣預報"){
                        countime=1;
                    }
                    else if(selectime=="未來兩天天氣預報"){
                        countime=4;
                    }
                    else if(selectime=="未來一週天氣預報"){
                        countime=14;
                    }
                    var weatherElement=location[0]["weatherElement"]; 
                    $(".location_td").text(locationName+"天氣");
                    //alert(locationName);
                    var location=jsonurl["records"]["locations"][0]["location"]; 
                    for(let i=0;i<location.length;i++){
                        if(locationName==location[i]["locationName"]){     // 找出當前option的值(locationName)
                            var weatherElement=location[i]["weatherElement"];     // 當前locationName的天氣元素  
                            $(".remove_tr").remove();
                            //weatherElement=location[i]["weatherElement"]
                            var time0=weatherElement[0]["time"];
                            var UVI=weatherElement[9];
                            var uvi_i=0;
                            for(let t=0;t<time0.length;t++){
                                if(countday==countime){
                                    break;
                                }
                                $('#showtable').append(
                                    $("<tr class='remove_tr' id='tr"+t+"'></tr>").append(
                                        $("<td align='center'></td>").html(time0[t]["startTime"]+" ~<br>"+time0[t]["endTime"])
                                    )
                                );
                                uvi_length=weatherElement[9]["time"].length;
                                for(let j=0;j<weatherElement.length;j++){
                                    if(weatherElement[j]["elementName"]=="UVI"){
                                        if(uvi_i<uvi_length){
                                            var a=UVI["time"][uvi_i]["startTime"];
                                            var b=time0[t]["startTime"];
                                            if(a.indexOf(b)!=-1){
                                                $("#tr"+t).append(
                                                    $("<td align='center'></td>").html(weatherElement[j]["time"][uvi_i]["elementValue"][0]["value"])
                                                )
                                                uvi_i++;
                                            }
                                            else{
                                                $("#tr"+t).append(
                                                    $("<td align='center'></td>").html("夜晚沒有紫外線")
                                                )
                                            }
                                        }
                                        else{
                                                $("#tr"+t).append(
                                                $("<td align='center'></td>").html("夜晚沒有紫外線")
                                            )
                                        } 
                                    }
                                    else{
                                        $("#tr"+t).append(
                                            $("<td align='center'></td>").html(weatherElement[j]["time"][t]["elementValue"][0]["value"])
                                        )
                                    }
                                }
                                countday++;
                            }                       
                        }
                    }
                });
                $.ajax({
                    url:"https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-77A32A88-7BED-4DC6-805A-E3A2CBEBA227&format=JSON",
                    type:"GET",
                    success:function(jsonurl_rain){
                        var location=jsonurl_rain["records"]["location"];
                        var weather_test=location[0]["weatherElement"];
                        var parameter_test=location[0]["parameter"];
                        var locationName=$('#locationName').val();
                        //console.log(location[0]["parameter"][0]["parameterValue"]);
                        var location_obs=new Array();
                        var arr_i=0;
                        for(let i=0;i<location.length;i++){
                            if(location[i]["parameter"][0]["parameterValue"]==locationName){
                                $("#Observatory").append(
                                    $('<option></option>').html(location[i]["locationName"]).val(location[i]["locationName"])
                                );
                                location_obs[arr_i++]=location[i];
                            }
                        }
                        
                        //console.log(location_obs);
                        for(let i=0;i<location_obs.length;i++){
                            $("#obstable").append(
                                $('<tr class="remove_obs"></tr>').append(
                                    $("<td align='center'></td>").html(location_obs[i]["locationName"]),
                                    $("<td align='center'></td>").html(location_obs[i]["weatherElement"][7]["elementValue"]),
                                    $("<td align='center'></td>").html(location_obs[i]["weatherElement"][6]["elementValue"])
                                )
                            );
                        }
                        $( "#locationName" ).change(function() {
                            var location=jsonurl_rain["records"]["location"];
                            var locationName=$('#locationName').val();
                            //alert(locationName);
                            var img = $("#show");
                            img.removeClass("hide");
                            img.attr("src", "./img/"+locationName+".jpeg");
                            var location_obs=new Array();
                            var arr_i=0;
                            //$("#Observatory").empty();
                            $(".remove_obs").remove();
                            for(let i=0;i<location.length;i++){
                                if(location[i]["parameter"][0]["parameterValue"]==locationName){
                                    // $("#Observatory").append(
                                    //     $('<option></option>').html(location[i]["locationName"]).val(location[i]["locationName"])
                                    // );
                                    location_obs[arr_i++]=location[i];
                                }   
                            }
                            for(let i=0;i<location_obs.length;i++){
                                $("#obstable").append(
                                    $('<tr class="remove_obs"></tr>').append(
                                        $("<td align='center'></td>").html(location_obs[i]["locationName"]),
                                        $("<td align='center'></td>").html(location_obs[i]["weatherElement"][7]["elementValue"]),
                                        $("<td align='center'></td>").html(location_obs[i]["weatherElement"][6]["elementValue"])
                                    )
                                );
                            }
                        });
                    },
                    error:function(){
                        alert("Error");
                    }    
                })
            },
            error:function(){
                alert("Error");
            }      
        });
    });
    
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        /* *{padding:0;margin:0;}
        caption{font-size:14px;font-weight:bold;} */ 
        /* table{ border-collapse:collapse;border:1px #525152 solid;width:50%;margin:0 auto;margin-top:100px;}
        th,td{border:1px #525152 solid;text-align:center;font-size:6px;line-height:20px; padding: 1px;border-bottom:1pt;  } */
        /*模擬對角線*/
        .out{
            border-top:40px #D6D3D6 solid;/*上邊框寬度等於表格第一行行高*/
            width:25px;/*讓容器寬度為0*/
            height:0px;/*讓容器高度為0*/
            border-left:80px #BDBABD solid;/*左邊框寬度等於表格第一行第一格寬度*/
            position:relative;/*讓裡面的兩個子容器絕對定位*/
        }
        b{font-style:normal;display:block;position:absolute;top:-40px;left:-40px;width:35px;}
        em{font-style:normal;display:block;position:absolute;top:-25px;left:-70px;width:55px;}


    </style>
</head>

<body>
    <div name="title" id="title"></div>
    <select name="locationName" id="locationName">    
    </select >
    <select name="selectime" id="selectime"> 
        <option name="now" id="now">當前天氣預報</option> 
        <option name="two_day" id="two_day">未來兩天天氣預報</option>
        <option name="weekly" id="weekly">未來一週天氣預報</option>    
    </select >
    <!-- <select name="Observatory" id="Observatory">    
    </select > -->
    <br>
    <img src="" id="show" width="200" class="hide">
    <h3>各縣市天氣狀況</h3>
        <table name="showtable" id="showtable" border="1px" style="width: 50%; ">
                    
        </table>
        <br><br><br><h3>各縣市觀測站累積雨量數據</h3>
        <table name="obstable" id="obstable" border="1px" style="width: 50%; ">
            <tr>
                <th>觀測地點</th>
                <th>過去1小時累積雨量</th>
                <th>過去24小時累積雨量</th>
            </tr>            
        </table>
                
</body>
</html>