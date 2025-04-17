<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            background-color: #4c6aaf;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4c6aaf;
            color: white;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        b{    
            text-align: center;
            font-size: 25px;
            display: block;
            margin-bottom: 8px;
            max-width: 600px;
            margin: 0 auto;
            color: #dc3545;
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
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            font-size: 16px;
            padding: 8px 16px;
        }
        .delete-btn {
            background-color: #f44336;
        }
        #recordCount {
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px;
        }
    </style>
    
    <script>
        function confirmDelete() {
            return confirm("是否確定要刪除紀錄?");
        }
    </script>
</head>

<body>
    <h1>歷史預約紀錄</h1>

    <?php
    session_start();

    if (isset($_POST["okButton"])) {
        header("Location: first_page.html");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rId = isset($_POST["rId"]) ? $_POST["rId"] : $_SESSION['rId'];
        $_SESSION['rId'] = $rId;

        $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");

        $patientSql = "SELECT pno, name FROM patient WHERE rId = '$rId'";
        $patientResult = $mysqli->query($patientSql);

        $totalRecords = 0;

        if ($patientResult->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>預約編號</th><th>病歷號碼</th><th>病患姓名</th><th>預約日期</th><th>時間</th><th>科別</th><th style="text-align: center;">操作</th></tr>';
            while ($patientRow = $patientResult->fetch_assoc()) {
                $pno = $patientRow["pno"];
            
                $diagnoseSql = "SELECT * FROM diagnose_time WHERE pno = '$pno'";
                $diagnoseResult = $mysqli->query($diagnoseSql);
            
                if ($diagnoseResult->num_rows > 0) {
                    while ($row = $diagnoseResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["appointment_id"] . '</td>';
                        echo '<td>' . $row["pno"] . '</td>';
                        echo '<td>' . $patientRow["name"] . '</td>';
                        echo '<td>' . $row["date"] . '</td>';
                        echo '<td>' . $row["time"] . '</td>';
                        echo '<td>' . $row["category"] . '</td>';
                        echo '<td><form method="post">
                        <input type="hidden" name="deleteId" value="' . $row["appointment_id"] . '">
                        <input type="hidden" name="rId" value="' . $_SESSION['rId'] . '">
                        <input class="delete-btn" type="submit" name="deleteButton" value="刪除">
                        </form></td>';
                        echo '</tr>';
                        $totalRecords++;
                    }
                    echo '<div id="recordCount" style="font-size: 30px; margin-top: -10px;">共有' . $totalRecords . '筆預約紀錄</div>';

                } else {
                    echo '<b colspan="7">該病人目前沒有任何預約紀錄</br>';
                }
                $diagnoseResult->close();
            }
            echo '</table>';
        } else {
            echo "<b>找不到對應的病患<br/>";
        }

        $patientResult->close();

        if (isset($_POST["deleteButton"])) {
            $deleteId = $_POST["deleteId"];
            $deleteSql = "DELETE FROM diagnose_time WHERE appointment_id = '$deleteId'";
            if ($mysqli->query($deleteSql)) {
                echo '<script>';
                echo 'alert("刪除成功！");';
                echo '$.ajax({';
                echo '    type: "POST",';
                echo '    url: window.location.href,';
                echo '    data: {},';
                echo '    success: function(response) {';
                echo '        $("body").html(response);';
                echo '    }';
                echo '});';
                echo '</script>';
            } else {
                echo "刪除預約紀錄失敗：" . $mysqli->error;
            }
        }

        $mysqli->close();

        ob_end_flush();
    }
    ?>
    <form method="post">
        <input type="submit" name="okButton" value="確定">
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
            var newRId = '<?php echo $_SESSION["rId"]; ?>';

        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                if (!confirmDelete()) {
                    event.preventDefault();
                }
            });
        });
    });
    </script>
</body>
</html>