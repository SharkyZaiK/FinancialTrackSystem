<?php
session_start();
require_once 'conn.php';

// 檢查使用者是否已登入
if (!isset($_SESSION['uName'])) {
    header("Location: index.php");
    exit();
}

// 從 Session 中獲取登入的帳號
$uName = $_SESSION['uName'];

// 從資料庫獲取使用者資訊
$stmt = $conn->prepare("SELECT uName, email FROM account WHERE uId = ?");
$stmt->bind_param("s", $uName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "無法取得使用者資訊，請重新登入。";
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <!-- 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">簡易記帳系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="account.php">Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">使用者資訊</h2>
        <div class="card">
            <div class="card-body">
                <p><strong>姓名：</strong> <?php echo htmlspecialchars($user['uName']); ?></p>
                <p><strong>電子郵件：</strong> <?php echo htmlspecialchars($user['email']); ?></p>

                <!-- 修改按鈕 -->
                <a href="update.php" class="btn btn-primary">修改密碼或電子郵件</a>
            </div>
        </div>
    </div>

    <!-- 引入 Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
