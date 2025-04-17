<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約成功</title>

    <script>
        // 檢查 Local Storage 中是否已經顯示過 alert
        var hasShownAlert = localStorage.getItem('hasShownAlert');

        if (!hasShownAlert) {
            // 如果尚未顯示過 alert，顯示它
            alert("請確認你的預約資訊是否正確");

            // 將已經顯示 alert 的狀態保存到 Local Storage
            localStorage.setItem('hasShownAlert', 'true');
        }

    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            font-size: 22px;
            background-color: #4c6aaf;
            color: white;
        }
        label {
            font-size: 16px;
            display: block;
            margin-bottom: 8px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            font-weight: bold;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        b{    
            font-size: 20px;
            display: block;
            margin-bottom: 8px;
            max-width: 600px;
            margin: 0 auto;
            color: #dc3545;
        }
    </style>
    
    <script>
        function confirmToModify() {
            // 使用 confirm 函數顯示確認提示框
            var result = confirm("請問您是否確定要編輯預約資料？");
            if (result == 0) {
                return false;
            }
        }
    </script>
</head>

<body>
    <form method="post" action="">
        <?php

        $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");
        
        // 開始會話
        session_start();
        if(isset($_SESSION["pno"])) {
            $nextAppointmentId = $_SESSION["appointmentId"];//12/21加了預約編號的部分
            $dId = $_SESSION["dId"];
            $pno = $_SESSION["pno"];
            $doctorname = $_SESSION["d_name"];
            $date = $_SESSION["date"];
            $time = $_SESSION["time"];
            $category = $_SESSION["category"];
            $previousdate = $_SESSION["date"];

         }

         // 查詢病人的名字
        $patientQuery = "SELECT name FROM patient WHERE pno = '$pno'";
        $patientResult = $mysqli->query($patientQuery);

        if ($patientResult->num_rows > 0) {
            $row = $patientResult->fetch_assoc();
            $patientname = $row["name"];

        }

        $mysqli->close(); // 關閉資料庫連接
        ?>
        <table>
            <tr>
                <th colspan="2">確認預約資料</th>
            </tr>

            <tr>
                <td>預約編號:</td>
                <td><input type="text" name="appointmentId" value="<?php echo $nextAppointmentId; ?>" size="5" readonly /></td>
            </tr>

            <tr>
                <td>病歷號碼:</td>
                <td><input type="text" name="pno" value="<?php echo $pno; ?>" size="5" readonly /></td>
            </tr>

            <tr>
                <td>姓名:</td>
                <td><input type="text" name="name" value="<?php echo $patientname; ?>" size="20" readonly /></td>
            </tr>
            
            <tr>
                <td>
                    <label for="doctor">看診醫生：</label>
                </td>
                <td>
                    <select id="doctor" name="doctorname" required>
                        <option value="陳玟茵" <?php if ($doctorname == '陳玟茵') echo 'selected'; ?>>陳玟茵</option>
                        <option value="許舒雅" <?php if ($doctorname == '許舒雅') echo 'selected'; ?>>許舒雅</option>
                        <option value="吳映潔" <?php if ($doctorname == '吳映潔') echo 'selected'; ?>>吳映潔</option>
                        <option value="無指定" <?php if ($doctorname == '無指定') echo 'selected'; ?>>無指定</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td for="date">看診日期:</td>
                <td><input type="date" name="date" value="<?php echo $date; ?>" required></td>
            </tr>

            <tr>
                <td for="time">看診時段：</td>
                <td>
                    <select id="time" name="time" required>
                        <option value="上午" <?php if ($time == '上午') echo 'selected'; ?>>上午</option>
                        <option value="下午" <?php if ($time == '下午') echo 'selected'; ?>>下午</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td for="category">看診內容：</td>
                <td>
                    <select id="category" name="category" required>
                        <option value="內科" <?php if ($category == '內科') echo 'selected'; ?>>內科</option>
                        <option value="針炙" <?php if ($category == '針炙') echo 'selected'; ?>>針炙</option>
                        <option value="整骨" <?php if ($category == '整骨') echo 'selected'; ?>>整骨</option>
                        <option value="其他" <?php if ($category == '其他') echo 'selected'; ?>>其他</option>
                    </select>
                </td>
            </tr>

        </table> 
        
        <input type="submit" name="MODIFY" value="編輯" onclick="return confirmToModify();"/>
        <input type="hidden" name="dId" value="<?php echo $dId; ?>">   
        <input type="hidden" name="previousdate" value="<?php echo $previousdate; ?>">
    </form>

    <form action="first_page.html" method="post">
    <input type="submit" name="homeButton" value="確認">
    </form>

    <?php
    // 建立 mysqli 物件
    $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 獲取選擇的日期和時段
        $selectedDate = $_POST["date"];
        $selectedTime = $_POST["time"];

        // 獲取原本選擇的日期
        $previousdate = $_POST["previousdate"];

        // 檢查是否已經有相同日期的預約（除了原本選擇的日期）
        $checkQuery = "SELECT * FROM diagnose_time WHERE pno = '$pno' AND date = '$selectedDate' AND date != '$previousdate'";
        $checkResult = $mysqli->query($checkQuery);

        // 取得今天的日期
        $today = date('Y-m-d');

        // 往後一個月的日期
        $oneMonthLater = date('Y-m-d', strtotime('+1 month'));

        // 檢查是否為周六下午或周日全天，如果是，顯示提示
        $dayOfWeek = date('w', strtotime($selectedDate));

        if (($dayOfWeek == 6 && $selectedTime == "下午") || $dayOfWeek == 0) {
            echo "<b>※週六下午及週日全天為診所休診時間，麻煩請選擇其他時段。</b>";
            return false;
        } elseif ($selectedDate < $today || $selectedDate > $oneMonthLater) {
            echo "<b>※ 麻煩請選擇今天以後、往後一個月內的日期，謝謝。</b>";
            return false;
        } elseif ($checkResult->num_rows > 0) {
            // 有其他相同日期的預約，不允許更新
            echo "<script>alert('目前選擇時間已經有預約，請選擇其他日期。');</script>";
            return false;
        } else{
            // 檢查是否有足夠的 POST 資料
            if(isset($_POST["appointmentId"]) && isset($_POST["dId"]) && isset($_POST["doctorname"]) && isset($_POST["pno"]) && isset($_POST["date"]) && isset($_POST["time"]) && isset($_POST["category"])) {
                // 準備資料
                $appointmentId = $_POST["appointmentId"];
                $dId = $_POST["dId"];
                $pno = $_POST["pno"];
                $doctorname = $_POST["doctorname"];
                $date = $_POST["date"];
                $time = $_POST["time"];
                $category = $_POST["category"];

                //用doctorname找dId
                $doctorQuery = "SELECT dId FROM doctor WHERE name = '$doctorname'";
                $doctorResult = $mysqli->query($doctorQuery);

                if ($doctorResult->num_rows > 0) {
                    $row = $doctorResult->fetch_assoc();
                    $doctor_id = $row["dId"];

                }

                // 指定 SQL 更新字串
                $sql_update = "UPDATE diagnose_time SET ";
                $sql_update .= "dId ='" . $doctor_id ."',";
                $sql_update .= "pno ='" . $pno ."',";
                $sql_update .= "date ='" . $date ."',";
                $sql_update .= "time ='" . $time ."',";
                $sql_update .= "category ='" . $category ."' ";
                $sql_update .= "WHERE appointment_id = '$appointmentId'";

                // 送出 UTF8 編碼的 MySQL 指令
                mysqli_query($mysqli, 'SET NAMES utf8');

                // 執行 SQL 更新指令
                if(mysqli_query($mysqli, $sql_update)) {
                    echo "<script>alert('資料已成功編輯，請返回首頁。'); window.location.href = 'first_page.html';</script>";
                } else {
                    die("資料庫更新記錄失敗: " . mysqli_error($mysqli) . "<br/>");
                }
            } 
        mysqli_close($mysqli);
        }
    }
    
?>

</body>
</html>