<?php
$link = @mysqli_connect("localhost", "root", "qaz112233") or die("無法開啟MySQL資料庫連接!<br/>");
mysqli_select_db($link, "r_system");

// 查詢目前病例號的最大值
$query = "SELECT MAX(pno) AS max_pno FROM patient";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
$max_pno = $row["max_pno"];

// 將最大值加一，並格式化為固定長度的字串
$next_pno = str_pad($max_pno + 1, 5, '0', STR_PAD_LEFT);

// 處理表單提交
if (isset($_POST["Insert"])) {
    // 檢查資料庫中是否已存在相同身分證
    $checkExistingIdQuery = "SELECT * FROM patient WHERE rId = '" . $_POST["rId"] . "'";
    $result = mysqli_query($link, $checkExistingIdQuery);

    if (mysqli_num_rows($result) > 0) {
        // 身分證已存在，顯示警告或採取其他操作
        echo '<script>alert("已存在相同身分證的記錄，請檢查您的資料");</script>';
    } else {
        // 身分證不存在，執行插入操作
        $sql = "INSERT INTO patient (pno, name, rId, birthday, phone, address) VALUES ('";
        $sql .= $next_pno . "','" . $_POST["name"] . "','";
        $sql .= $_POST["rId"] . "','" . $_POST["birthday"] . "','";
        $sql .= $_POST["phone"] . "','" . $_POST["address"] . "')";

        echo "<b>SQL指令: $sql</b><br/>";
        mysqli_query($link, 'SET NAMES utf8');

        if (mysqli_query($link, $sql)) {
            header("Location: first_page.html");
            exit();
        } else {
            die("資料庫新增記錄失敗<br/>");
        }
    }

    mysqli_close($link);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>病患新增</title>
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
            border: 1px solid #4c6aaf;
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
        .cancel-button {
            font-size:18px;
            font-weight: bold;
        }
    </style>
    <script>
        function confirmToAdd() {
            // 使用 confirm 函數顯示確認提示框
            var result = confirm("請問您是否要註冊診所帳號？");
            if (result == 0) {
                window.location.href = "first_page.html";
            }
        }

        function cancelForm() {
            // 使用 confirm 函數顯示確認提示框
            var result = confirm("您確定要取消新增嗎？");
            if (result) {
                window.location.href = "first_page.html";
            }
        }
        
        function showAlert() {
            // 使用 alert 函數顯示提示框
            alert("完成新增");
        }
    </script>
</head>

<body onload="confirmToAdd();">
    <form action="addmember.php" method="post">
        <table>
            <tr>
                <th colspan="2">新增診所會員</th>
            </tr>
            <tr>
                <td>病例號:</td>
                <td><input type="text" name="pno" value="<?php echo $next_pno; ?>" size="5" readonly /></td>
            </tr>
            <tr>
                <td>姓名:</td>
                <td><input type="text" name="name" size="20" required/></td>
            </tr>
            <tr>
                <td>身分證字號:</td>
                <td><input type="text" name="rId" size="10" pattern="^[A-Za-z][12]\d{8}$" required/></td>
            </tr>
            <tr>
                <td>生日:</td>
                <td><input type="date" name="birthday" size="10" max="<?php echo date('Y-m-d'); ?>" required/></td>
            </tr>
            <tr>
                <td>電話:</td>
                <td><input type="tel" name="phone" size="10" pattern="[0-9]{10}" required/></td>
            </tr>
            <tr>
                <td>住址:</td>
                <td><input type="text" name="address" size="30" required/></td>
            </tr>
        </table>
        <input type="submit" name="Insert" value="新增" onclick="showAlert();" />
        <input type="button" onclick="cancelForm()" value="取消" class="cancel-button" />
    </form>
</body>
</html>