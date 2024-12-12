<?php
require_once('conn.php');

// 定義錯誤訊息變數
$error_message = "";
$success_message = "";

// 當表單提交時進行處理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $realname = trim($_POST['realname']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : null; // 電子郵件可選

    // 檢查必填欄位是否為空
    if (empty($username) || empty($password) || empty($realname)) {
        $error_message = "帳號、密碼和姓名為必填欄位，請重新填寫。";
    } else {
        // 檢查密碼是否符合複雜度要求
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            $error_message = "密碼需包含至少八個字元，並包含大小寫字母及數字。";
        } else {
            // 將密碼加密
            $password_hash = hash('sha256', $password);

            // 檢查帳號是否已存在
            $stmt = $conn->prepare("SELECT * FROM account WHERE uId=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "帳號已存在，請使用其他帳號。";
            } else {
                // 新增使用者資料到資料庫
                $stmt = $conn->prepare("INSERT INTO account (uId, password, uName, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $password_hash, $realname, $email);

                if ($stmt->execute()) {
                    $success_message = "註冊成功！<a href='index.php'>返回登入</a>";
                } else {
                    $error_message = "註冊失敗，請稍後再試。";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊新帳號</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">註冊新帳號</h2>

        <!-- 顯示錯誤訊息 -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- 顯示成功訊息 -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">帳號（必填）</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">密碼（必填）</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="realname" class="form-label">姓名（必填）</label>
                <input type="text" class="form-control" id="realname" name="realname" value="<?php echo isset($_POST['realname']) ? htmlspecialchars($_POST['realname']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">電子郵件（選填）</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-success">註冊</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
