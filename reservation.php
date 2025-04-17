<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Check Patient Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align:center;
            background-color: #4c6aaf;
            color: white;
        }
        .container {
            display: flex;
            margin-left:395px;
        }

        /* 病患資料表格樣式 */
        .patient-table {
            border-collapse: collapse;
            width: 60%;
        }
        .patient-table, .patient-table th, .patient-table td {
            border: 1px solid #4c6aaf;
        }
        .patient-table th, .patient-table td {
            padding: 15px;
            text-align: left;
        }
        .patient-table th {
            font-size: 22px;
            background-color: #4c6aaf;
            color: white;
        }
        .patient-table td {
            background-color: white;
        }
        .side-image {
            margin-left: 0px;
        }
        .side-image img {
            display: block;
            margin: auto; /* 设置上、右、下、左的 margin 值为 0 */
            border: none; /* 移除边框 */
            height: auto; /* 保持图片比例 */
            margin-bottom: 20px;
        }

        /* 預約表單樣式 */
        .appointment-table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            margin-bottom: 20px;
        }
        .appointment-table, .appointment-table th, .appointment-table td {
            border: 1px solid #4c6aaf;
        }
        .appointment-table th, .appointment-table td {
            padding: 15px;
            text-align: left;
        }
        .appointment-table th {
            font-size: 22px;
            background-color: #4c6aaf;
            color: white;
        }
        .appointment-table td {
            background-color: white;
        }
        
        b {
            font-size: 20px;
            display: block;
            margin-bottom: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 20px;
            font-size: 20px;
        }
        input {  
            font-size: 15px;
            margin-bottom: 20px;    
        }
        input[type="submit"] {
            font-size: 18px;
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: white;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        select {
            font-size: 15px;
            margin-bottom: 20px; 
        }
        img {
            display: block;
            margin: auto;
            border: 1px solid black;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("確定要刪除病患資料嗎？");
        }
    </script>
</head>

<body>
    
   <h1>病患資訊</h1>
    
   <?php
    // 檢查是否有 POST 資料送來
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 獲取病患編號
        $rId = $_POST["rId"];      
        // 建立 mysqli 物件
        $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");

        // 指定 SQL 查詢字串
        $sql = "SELECT * FROM patient WHERE rId = '$rId'";
        
        // 送出查詢的 SQL 指令
        if ($result = $mysqli->query($sql)) { 
            // 顯示查詢結果
            if ($row = $result->fetch_assoc()) { 
                // 將病歷號碼存儲在 session 中，以便在後續使用
                session_start();
                $_SESSION["pno"] = $row["pno"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["rId"] = $row["rId"];
                $_SESSION["birthday"] = $row["birthday"];
                $_SESSION["phone"] = $row["phone"];
                $_SESSION["address"] = $row["address"];

                echo " <div class='container'>";
                echo "<table class='patient-table'>";
                echo "<tr><th colspan='2' style='text-align: center;'>病患資料</th></tr>";
                echo "<tr><td>病歷號碼</td><td>" . $row["pno"] . "</td></tr>";
                echo "<tr><td>姓名</td><td>" . $row["name"] . "</td></tr>";
                echo "<tr><td>身分證號碼</td><td>" . $row["rId"] . "</td></tr>";
                echo "<tr><td>生日</td><td>" . $row["birthday"] . "</td></tr>";
                echo "<tr><td>電話</td><td>" . $row["phone"] . "</td></tr>";
                echo "<tr><td>地址</td><td>" . $row["address"] . "</td></tr>";
                echo "</table>";
                echo "<div class='side-image'><img src='Doctor.png' border='1' height='100%' width='100%'></div>";
                echo "</div>";
                echo "<br/>";
            } else {
               // 使用 header 函數將控制權轉向新增會員的 PHP 檔案
               header("Location: addmember.php");
               exit(); // 確保在轉向後結束腳本的執行
            }
            $result->close(); // 釋放佔用記憶體 
        } else {
            echo "查詢失敗: " . $mysqli->error;
        }

        $mysqli->close(); // 關閉資料庫連接

        // 檢查 "更新" 按鈕是否按下
        if (isset($_POST["updateButton"])) {
            // 如果是 "更新" 按鈕被按下，執行跳轉
            header("Location: update.php");
            exit();
        }

        // 檢查 "刪除" 按鈕是否按下
        if (isset($_POST["deleteButton"])) {
            // 如果是 "刪除" 按鈕被按下，執行跳轉
            header("Location: delete.php");
            exit();
        }
    }
?>

<form action="update.php" method="post">
    <input type="submit" name="updateButton" value="更新">
</form>

<form action="delete.php" method="post" onsubmit="return confirmDelete();">
    <input type="hidden" name="pno" value="<?php echo $_SESSION['pno']; ?>">
    <input type="submit" name="deleteButton" value="刪除">
</form>
   
<h1>醫生預約</h1>
<form action="success.php" method="post">
    <table class='appointment-table'>
        <tr>
            <th colspan="2" style='text-align: center;'>預約表單</th>
        </tr>
        <tr>
            <td>醫生</td>
            <td>
                <select id="doctor" name="doctor" required>
                    <option value="陳玟茵">陳玟茵</option>
                    <option value="許舒雅">許舒雅</option>
                    <option value="吳映潔">吳映潔</option>
                    <option value="無指定">無指定</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>內容</td>
            <td>
                <select id="category" name="category" required>
                    <option value="內科">內科</option>
                    <option value="針炙">針炙</option>
                    <option value="整骨">整骨</option>
                    <option value="其他">其他</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>日期</td>
            <td><input type="date" name="date" required></td>
        </tr>
        <tr>
            <td>時段</td>
            <td>
                <select id="time" name="time" required>
                    <option value="上午">上午</option>
                    <option value="下午">下午</option>
                </select>
                <br>
                <b>請注意：</b>
                <br>
                <b>週六下午及週日全天該時段為診所休診時間</b><br>
                <b>可預約日期區間為一個月，請不要預約太後面的日期</b>
            </td>
        </tr>
    </table>
       
    <!-- 將病歷號碼帶入隱藏的 input 中 -->
    <input type="hidden" name="pno" value="<?php echo $_SESSION['pno']; ?>">

    <input type="submit" value="預約">
    <img src="診所休診時間.png" border="1" height="100%" width="100%" alt="診所休診">

</form>


</body>
</html>