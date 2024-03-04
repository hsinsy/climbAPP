<?php 
    date_default_timezone_set('Asia/Taipei');
    $localhost = "localhost"; 
    $dbusername = "root"; 
    $dbpassword = "123456"; 
    $dbname = "school";
    $conn = mysqli_connect($localhost,$dbusername,$dbpassword,$dbname);
    $conn -> set_charset("UTF8");
    $email = $_REQUEST['email'];
    //$email = "tkuim@gmail.com";
    $result = $conn -> query("SELECT access_token FROM `token` WHERE `user_id` like '$email'");

    while ($row = $result->fetch_assoc()) // 當該指令執行有回傳
    {
        $access_token = $row["access_token"];    
    } 
    $day=date("Y-m-d");
    $time=date("H:i");

    //卡路里
    $url1 = "https://api.fitbit.com/1/user/-/activities/calories/date/$day/1d/15min/time/00:00/$time.json";
    $header = array(
      'authorization:Bearer '.$access_token.'\'',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $calroies = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }

    
    //日步數
    $url2 = "https://api.fitbit.com/1/user/-/activities/steps/date/$day/1d/15min/time/00:00/$time.json";
    $header = array(
      'authorization:Bearer '.$access_token.'\'',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url2);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $daystep = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }
    //echo($daystep); //測試用
    
    //距離
    $url4 = "https://api.fitbit.com/1/user/-/activities/distance/date/$day/1d/1min/time/00:00/$time.json";
    $header = array(
      'authorization:Bearer '.$access_token.'\'',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url4);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $distance = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }
    //心率
    $url5 = "https://api.fitbit.com/1/user/-/activities/heart/date/$day/1d/1min/time/00:00/$time.json";
    $header = array(
    'authorization:Bearer '.$access_token.'\'',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url5);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $heartrate = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }
    //月步數
    $month=date("m");
    $total=0;
    $result = $conn -> query("SELECT * FROM month_step WHERE `month`='$month'");
    while ($row = $result->fetch_assoc())
    {
       $total+=$row["step"];
    }
    //大於250步
    $start = explode('"dataset":[',$daystep);
    $zone = explode(',',$start[1]);
    $array=array();
    for($i=0;$i<count($zone)-1;$i++){
       if($i%2!=0){
          $step_hr = explode('"value":',$zone[$i]);
          $step_hr2 = explode("}",$step_hr[1]);
          $array[]+=$step_hr2[0];
       }
    }
    $total=0;
    $count=0;
    $array2=array_chunk($array,4);
    for($i=0;$i<count($array2);$i++){
       for($j=0;$j<count($array2[$i]);$j++){
           $total+=$array2[$i][$j];
       }
       if($total>=250){
          $count++;
       }
       $total=0;
    }
    
    $url6 = "https://api.fitbit.com/1/user/-/profile.json";
    $header = array(
    'authorization:Bearer '.$access_token.'\'',
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url6);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $age = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
    } else {
        curl_close($curl);
    }
    $age2 = explode('"age":',$age);
    $age3 = explode(',',$age2[1]);
    $gender = explode('"gender":"',$age);
    $gender2 = explode('"',$gender[1]);

    //進資料庫 體重沒有就不update體重
    $cal = explode('"value":"',$calroies);
    $cal2 = explode('"',$cal[1]);
    $step = explode('"value":"',$daystep);
    $step2 = explode('"',$step[1]);
    
    $dis = explode('"value":"',$distance);
    $dis2 = explode('"',$dis[1]);

    $heart = explode('"value":"',$heartrate);
    $heart2 = explode('"',$heart[1]);
    
    $total+=$step2[0];
    
    $updatQuery = "UPDATE personal_datas SET `calroies`='$cal2[0]',`date`='$day',`step_day`='$step2[0]',`distance`='$dis2[0]',`heartrate`='$heart2[0]',`step_month`='$total',`count`='$count' WHERE `user_id` like '$email'";
    $result = mysqli_query ($conn,$updatQuery);
    
    //印出json
    $result = $conn -> query("SELECT * FROM `personal_datas` where `user_id` like '$email'");
    while ($row = $result->fetch_assoc())
    {
        echo $row['calroies']."\n";
        echo $row['distance']."\n";
        echo $row['step_day']."\n";
        echo $row['point']."\n";
        echo $row['count']."\n";
        echo $row['date']."\n";
    }
    $result = $conn -> query("SELECT * FROM `achieve`where`user_id` like '$email'");
    while ($row = $result->fetch_assoc())
    {
        echo $row['value']."\n";
    }

    $result = $conn -> query("SELECT * FROM `personal_datas` WHERE `user_id`='$email'");
    while ($row = $result->fetch_assoc()) // 當該指令執行有回傳
    {
        $weight = $row['weight'];
        $height = $row['height'];
    }
    if($gender2[0]=="FEMALE");{
        $bmr = ((9.6*$weight)+(1.8*$height)-(4.7*$age3[0]))+655;
    }
    if($gender2[0]=="MALE"){
        $bmr = ((13.7*$weight)+(5.0*$height)-(6.8*$age3[0]))+66;
    }
    echo $bmr*1.55;
    $conn -> close();
?>

