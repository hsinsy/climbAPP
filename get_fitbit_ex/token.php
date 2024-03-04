<?php
    $Url = "https://api.fitbit.com/oauth2/token?code=d35d65f0f91c20a94fccef83e899ebaa2bff123f&grant_type=authorization_code&redirect_uri=http://localhost";//接口地址
    $sl_data=array(
        'code'=>'d35d65f0f91c20a94fccef83e899ebaa2bff123f',//每次都會換
        'grant_type'=>'authorization_code',
        'redirect_uri'=>'http://localhost'
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
        
    //將 JSON 格式資料轉換為 PHP 物件
    $obj = json_decode($data, true);
    $access = $obj["access_token"];
    $refresh = $obj["refresh_token"];
    $user_id = "tkuim@gmail.com";
    if(empty($access)||empty($refresh)){
        die('Error!');
    }

    //連接資料庫
    $db_link = mysqli_connect('localhost', 'root','123456');

    if (!$db_link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $select_db = mysqli_select_db($db_link, "school");
    if (!$select_db) {
        die("Select database failed: " . mysqli_error($db_link));
    }

    if(empty($access)||empty($refresh)){
        die('Error!');
    }else{
        $sql = "SELECT * FROM token WHERE `user_id`='$user_id'";
        $query = mysqli_query($db_link,$sql );
        if( mysqli_num_rows($query) ){
                $updatQuery = "UPDATE token SET `access_token`='$access',`refresh_token`='$refresh' WHERE `user_id`='$user_id'";
                $result = mysqli_query ($db_link,$updatQuery); 
        }else{
                $sql = "insert into token (`user_id`,`access_token`,`refresh_token`) values ('$user_id','$access','$refresh')";                       
                $result = mysqli_query ($db_link ,$sql);
                if ( $result ) {
                        echo("success!");
                } else {
                        echo("try again");
                        die;
                }
        }
    }
?>
