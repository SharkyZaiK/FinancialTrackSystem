<?php session_start(); ?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入頁面</title>
    <!-- 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 使內容置於螢幕中央 */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-register {
            display: block;
            margin: 20px auto 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>使用者登入</h2>
        <!-- 登入表單 -->
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">帳號:</label>
                <input type="text" name="username" id="username" class="form-control" required pattern="[0-9a-zA-Z]+" title="僅限英數字">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">密碼:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <input type="submit" value="登入" class="btn btn-primary w-100">
        </form>
        <hr>
        <!-- 註冊按鈕 -->
        <p class="text-center">還沒有帳號嗎？立即註冊：</p>
        <button class="btn btn-success btn-register" onclick="window.location.href='register.php'">註冊新帳號</button>
    </div>

    <!-- 引入 Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
