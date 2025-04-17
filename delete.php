<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteButton"])) {
    $pno = $_POST["pno"];

    // 資料庫連接
    $mysqli = new mysqli("localhost", "root", "qaz112233", "r_system") or die("無法開啟 MySQL 資料庫連接!<br/>");

    // 刪除之前不再需要確認，直接執行刪除
    $sql = "DELETE FROM patient WHERE pno = '$pno'";
    if ($mysqli->query($sql)) {
        // 使用 JavaScript 彈出提示視窗
        echo '<script>alert("病患資料已成功刪除，回到首頁");</script>';

        // 重新導向回首頁
        echo '<script>window.location.href = "first_page.html";</script>';
    } else {
        echo "刪除失敗: " . $mysqli->error;
    }

    $mysqli->close(); // 關閉資料庫連接
}
?>
