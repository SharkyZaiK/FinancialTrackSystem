<?php
session_start();
require_once 'conn.php';

// 檢查使用者是否已登入
if (!isset($_SESSION['uName'])) {
    header("Location: index.php");
    exit();
}

// 保存之前的輸入值（避免表單重新加載後丟失用戶輸入）
$previous_email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$previous_password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

// 獲取表單提交的資料
$new_email = isset($_POST['email']) ? trim($_POST['email']) : null;
$new_password = isset($_POST['password']) ? trim($_POST['password']) : null;
$uId = $_SESSION['uName']; // 從 Session 中獲取登入帳號

// 初始化修改標記
$email_updated = false;
$password_updated = false;

// 更新密碼
if ($new_password) {
    // 加密新密碼
    $new_password_hashed = hash('sha256', $new_password);

    // 從資料庫獲取當前密碼和上次密碼
    $stmt = $conn->prepare("SELECT password, password1 FROM account WHERE uId = ?");
    $stmt->bind_param("s", $uId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $current_password = $user['password'];
        $previous_password = $user['password1'];

        // 驗證新密碼不可與當前或上次密碼相同
        if ($new_password_hashed === $current_password || $new_password_hashed === $previous_password) {
            die("新密碼不可與當前密碼或上次密碼相同！<a href='account.php'>返回帳戶頁面</a>");
        }

        // 更新密碼並將當前密碼存入 password1
        $stmt = $conn->prepare("
            UPDATE account 
            SET password = ?, password1 = ? 
            WHERE uId = ?
        ");
        $stmt->bind_param("sss", $new_password_hashed, $current_password, $uId);

        if ($stmt->execute()) {
            $password_updated = true;
        } else {
            echo "密碼更新失敗，請稍後再試！<a href='account.php'>返回帳戶頁面</a>";
            exit();
        }
    } else {
        echo "使用者不存在，請重新登入！<a href='index.php'>返回登入頁面</a>";
        exit();
    }
}

// 更新電子郵件
if ($new_email) {
    $stmt = $conn->prepare("UPDATE account SET email = ? WHERE uId = ?");
    $stmt->bind_param("ss", $new_email, $uId);

    if ($stmt->execute()) {
        $email_updated = true;
    } else {
        echo "電子郵件更新失敗，請稍後再試！<a href='account.php'>返回帳戶頁面</a>";
        exit();
    }
}

// 根據更新結果顯示對應提示
if ($email_updated || $password_updated) {
    echo "<h3>修改結果：</h3>";
    echo "<ul>";
    if ($email_updated) {
        echo "<li>電子郵件更新成功！</li>";
    }
    if ($password_updated) {
        echo "<li>密碼更新成功！</li>";
    }
    echo "</ul>";
    echo "<a href='account.php'>返回帳戶頁面</a>";
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <!-- 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">修改帳號資訊</h2>
        <form action="update.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">新電子郵件</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="輸入新電子郵件"
                       value="<?php echo $previous_email; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">新密碼</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="輸入新密碼"
                       value="<?php echo $previous_password; ?>">
            </div>
            <button type="submit" class="btn btn-success">保存修改</button>
            <a href="account.php" class="btn btn-secondary">取消</a>
        </form>
    </div>

    <!-- 引入 Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
