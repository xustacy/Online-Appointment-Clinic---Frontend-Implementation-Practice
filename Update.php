<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新病患個人資料</title>
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
            font-size: 18px;
            font-weight: bold;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
    </style>
    <script>
        function confirmToAdd() {
            // 使用 confirm 函數顯示確認提示框
            var result = confirm("請問您是否確定要更新個資？");
            if (result == 0) {
                window.location.href = "home.html";
            }
        }
    </script>
</head>
<body onload="confirmToAdd();">

    <form method="post" action="">
    <?php
    // 開始會話
    session_start();
    if(isset($_SESSION["pno"])) {
        $pno = $_SESSION["pno"];
        $name = $_SESSION["name"];
        $rId = $_SESSION["rId"];
        $birthday = $_SESSION["birthday"];
        $phone = $_SESSION["phone"];
        $address = $_SESSION["address"];
     }
    ?>
        <table>
            <tr>
                <th colspan="2">更新資料</th>
            </tr>
            <tr>
                <td>病歷號碼:</td>
                <td><input type="text" name="pno" value="<?php echo $pno; ?>" size="5" readonly /></td>
            </tr>
            <tr>
                <td>姓名:</td>
                <td><input type="text" name="name" value="<?php echo $name; ?>" size="20" /></td>
            </tr>
            <tr>
                <td>身分證字號:</td>
                <td><input type="text" name="rId" pattern="^[A-Za-z][12]\d{8}$" value="<?php echo $rId;?>" size="10" /></td>
            </tr>
            <tr>
                <td>生日:</td>
                <td><input type="date" name="birthday" value="<?php echo $birthday;?>" max="<?php echo date('Y-m-d'); ?>" size="10" /></td>
            </tr>
            <tr>
                <td>電話:</td>
                <td><input type="tel" name="phone" pattern="[0-9]{10}" value="<?php echo $phone;?>" size="10" /></td>
            </tr>
            <tr>
                <td>住址:</td>
                <td><input type="text" name="address"value="<?php echo $address;?> "size="30" /></td>
            </tr>
        </table>
        <input type="submit" name="UPDATE" value="更新" />
    </form>
<?php
    // 建立 mysqli 物件
    $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 檢查是否有足夠的 POST 資料
        if(isset($_POST["name"]) && isset($_POST["rId"]) && isset($_POST["birthday"]) && isset($_POST["phone"]) && isset($_POST["address"])) {
            // 準備資料
            $name = $_POST["name"];
            $rId = $_POST["rId"];
            $birthday = $_POST["birthday"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];

            // 指定 SQL 更新字串
            $sql_update = "UPDATE patient SET ";
            $sql_update .= "name ='" . $name ."',";
            $sql_update .= "rId ='" . $rId ."',";
            $sql_update .= "birthday ='" . $birthday ."',";
            $sql_update .= "phone ='" . $phone ."',";
            $sql_update .= "address ='" . $address ."' ";
            $sql_update .= "WHERE pno = '$pno'";

            // 送出 UTF8 編碼的 MySQL 指令
            mysqli_query($mysqli, 'SET NAMES utf8');

            // 執行 SQL 更新指令
            if(mysqli_query($mysqli, $sql_update)) {
                echo "<script>alert('資料已成功更新，請返回首頁。'); window.location.href = 'home.html';</script>";
            } else {
                die("資料庫更新記錄失敗: " . mysqli_error($mysqli) . "<br/>");
            }
        } 
    }
    // 關閉資料庫連接
    mysqli_close($mysqli);
?>
</body>
</html>