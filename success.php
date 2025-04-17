<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1{
            text-align:center;
            background-color: #4c6aaf;
            color: white;
            margin-bottom: 150px;

        }
        b {    
            font-size: 20px;
            display: block;
            margin-bottom: 8px;
            max-width: 600px;
            margin: 0 auto;
            color: #dc3545;
        }
        img {
            display: block; /* 讓圖片變成 block 元素，以便使用 margin: auto; */
            margin: auto; /* 水平置中 */
            border: 1px solid black; /* 添加邊框，如果需要的話 */
            max-width: 40%; /* 控制最大寬度 */
            height: auto; /* 保持圖片比例 */
            margin-bottom: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        input[type="submit"] {
            font-size: 18px;
            width: 100%;
            padding: 8px;/*與邊框之間的距離*/
            margin-bottom: 20px;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: white;
            
        }
    </style>
</head>
<body>

<h1>預約失敗</h1>
<img src="dinosaur.gif" border="1" height="30%" width="40%" alt="小恐龍"></img>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取選擇的日期和時段
    $selectedDate = $_POST["date"];
    $selectedTime = $_POST["time"];

    // 取得今天的日期
    $today = date('Y-m-d');

    // 往後一個月的日期
    $oneMonthLater = date('Y-m-d', strtotime('+1 month'));

    // 檢查是否為周六下午或周日全天，如果是，顯示提示
    $dayOfWeek = date('w', strtotime($selectedDate));

    if (($dayOfWeek == 6 && $selectedTime == "下午") || $dayOfWeek == 0) {
        echo "<b>週六下午及週日全天該時段為診所休診時間，麻煩請選擇其他時段。</b>";
    } elseif ($selectedDate < $today || $selectedDate > $oneMonthLater) {
        echo "<b>請選擇今天以後、往後一個月內的日期。請重新回到首頁再次預約</b>";

    }else{
        // 獲取表單數據
        $doctorName = $_POST["doctor"];
        $category = $_POST["category"];
        $date = $_POST["date"];
        $time = $_POST["time"];
        $pno = $_POST["pno"]; // 從隱藏的 input 中取得病歷號碼
    
        // 建立 mysqli 物件
        $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");

        // 檢查是否已經有相同日期的預約
        $checkQuery = "SELECT * FROM diagnose_time WHERE pno = '$pno' AND date = '$selectedDate'";
        $checkResult = $mysqli->query($checkQuery);

        // 查詢醫生的編號
        $doctorQuery = "SELECT dId FROM doctor WHERE name = '$doctorName'";
        $doctorResult = $mysqli->query($doctorQuery);

        if ($checkResult->num_rows > 0) {
            echo "<script>alert('您已經在該日期有預約，請選擇其他日期。');window.location.href = 'first_page.html';</script>";
        } else {
            if ($doctorResult->num_rows > 0) {
                $row = $doctorResult->fetch_assoc();
                $doctorId = $row["dId"];

                //12/21加了預約編號的部分-start
                // 查詢目前最大的預約編號
                $maxAppointmentIdQuery = "SELECT MAX(appointment_id) as max_id FROM diagnose_time";
                $maxIdResult = $mysqli->query($maxAppointmentIdQuery);

                $maxId = 0;
                if ($maxIdResult->num_rows > 0) {
                    $row = $maxIdResult->fetch_assoc();
                    $maxId = $row["max_id"];
                }

                // 計算下一個預約編號
                $nextAppointmentId = $maxId + 1;

                // 插入操作，使用計算後的預約編號
                $sql = "INSERT INTO diagnose_time (appointment_id, dId, pno, date, time, category) VALUES ('$nextAppointmentId', '$doctorId', '$pno', '$date', '$time', '$category')";
                //12/21加了預約編號的部分-end

                session_start();
                $_SESSION["appointmentId"] = $nextAppointmentId;//12/21加了預約編號的部分
                $_SESSION["dId"] = $doctorId;
                $_SESSION["pno"] = $pno;
                $_SESSION["d_name"] = $doctorName;
                $_SESSION["date"] = $date;
                $_SESSION["time"] = $time;
                $_SESSION["category"] = $category;

                // 送出 SQL 插入指令
                if ($mysqli->query($sql)) {
                    header("Location:r_success.php");
                    exit(); // 確保在轉向後結束腳本的執行
                }else {
                    echo "預約失敗: " . $mysqli->error;
                }
            }else {
                echo "<b>找不到對應的醫生。</b>";
            }
        }
        $mysqli->close(); // 關閉資料庫連接
    }
    if (isset($_POST["endButton"])) {
        // 如果是 "確認" 按鈕被按下，執行跳轉
        header("Location: first_page.html");
        exit();
    }
}
?>
<form action ="first_page.html">
    <input type="submit" name="endButton" value="回到首頁">
</form>

</body>
</html>