<?php
    $localhost = "localhost"; 
    $dbusername = "root"; 
    $dbpassword = "123456"; 
    $dbname = "school";

    $conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
    //要舊的refresh_token
    $result = $conn -> query("SELECT refresh_token FROM `token` WHERE id='1'");

    while ($row = $result->fetch_assoc()){
        $refresh_token = $row["refresh_token"];    
    } 

    $Url = "https://api.fitbit.com/oauth2/token?grant_type=refresh_token&refresh_token=$refresh_token";
    $sl_data=array(
        'grant_type'=>'refresh_token',
        'refresh_token'=>'$refresh_token',
    );
    $data_str = json_encode($sl_data);
    $header=array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization:Basic MjJCVjk5OjQzNmY1MzJhZmRmZTgwOWM1NGVlOWEyOTZjYjhmZmIw',
        'Content-Length: '.strlen($data_str),
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $data = curl_exec($ch);
    echo $data;

    $obj = json_decode($data, true);
    $access = $obj["access_token"];
    $refresh = $obj["refresh_token"];

    if(empty($access)||empty($refresh)){
        die('Error!');
    }else{
        $updatQuery = "UPDATE token SET `refresh_token`='$refresh',`access_token`='$access' WHERE id='1'";
        $result2 = mysqli_query ($conn,$updatQuery);
        if ( $result2 ) {
                echo("success!");
        } else {
                echo("try again");
                die;
        }
    }
?>