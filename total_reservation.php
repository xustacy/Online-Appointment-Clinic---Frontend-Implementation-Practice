<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align:center;
            background-color: #4c6aaf;
            color: white;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-weight: bold; /* 加粗 */
            font-size: 20px; /* 調整字體大小 */
        }

        th {
            font-size: 20px; /* 調整字體大小 */
            background-color: #4c6aaf;
            color: white;
        }
        b{    
            text-align:center;
            font-size: 20px;
            display: block;
            margin-bottom: 8px;
            max-width: 600px;
            margin: 0 auto;
            color: #dc3545;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        input{
            width: 100%;
            font-size: 15px;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            font-weight: bold;
            font-size: 20px;
            background-color: #4CAF50;
            color: white;
           cursor: pointer;
        }
    </style>
    
</head>

<body>
    <h1>總預約人數</h1>

    <?php

    // 檢查是否有 POST 資料送來
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 獲取病患編號
        $date = $_POST["date"]; // 使用身分證字號進行查詢
        
        // 建立 mysqli 物件
        $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");

        // 指定 SQL 查詢字串，先查詢 patient 表
        $diagnoseSql = "SELECT date, time, COUNT(*) AS total_appointments FROM diagnose_time WHERE date = '$date' GROUP BY date, time";
        $diagnoseResult = $mysqli->query($diagnoseSql);

               // 顯示查詢結果
               if ($diagnoseResult->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>日期</th><th>時間</th><th>總預約人數</th></tr>';
                while ($row = $diagnoseResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row["date"] . '</td>';
                    echo '<td>' . $row["time"] . '</td>';
                    echo '<td>' . $row["total_appointments"] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<b>該日期沒有任何預約紀錄</b>';
            }
    
            $diagnoseResult->close(); // 釋放佔用記憶體
            $mysqli->close(); // 關閉資料庫連接
        }
    
    

    // 檢查 "確認" 按鈕是否按下
    if (isset($_POST["okButton"])) {
        // 如果是 "確認" 按鈕被按下，執行跳轉
        header("Location: first_page.html");
        exit();
    }
    
    ?>
    <form method="post">
        <input type="submit" name="okButton" value="確定">
    </form>

</body>
</html>