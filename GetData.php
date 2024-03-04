<?php
    // 設定 MySQL 的連線資訊並開啟連線
    // 資料庫位置、使用者名稱、使用者密碼、資料庫名稱
    $link = mysqli_connect("localhost", "admin", "123456", "school");
    $link -> set_charset("UTF8"); // 設定語系避免亂碼

    $email = $_POST['email'];
    
    // SQL 指令
    $result = $link -> query("SELECT * FROM `personal` where email like '$email'");
    while ($row = $result->fetch_assoc()) // 當該指令執行有回傳
    {
        $output[] = $row; // 就逐項將回傳的東西放到陣列中
        echo $row['name']."\n";
        echo $row['email']."\n";
        echo $row['cottonname'];
    }

    // 將資料陣列轉成 Json 並顯示在網頁上，並要求不把中文編成 UNICODE
    $link -> close(); // 關閉資料庫連線

?>