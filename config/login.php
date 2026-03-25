<?php
require_once '../config/database.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username/email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenThumb Community</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/forms.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="form-container">
            <div class="card form-card">
                <h2><i class="fas fa-sign-in-alt"></i> Welcome Back!</h2>
                <p class="subtitle">Login to continue your gardening journey</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="form">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Username or Email</label>
                        <input type="text" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    
                    <p class="text-center">New to GreenThumb? <a href="register.php">Create a free account</a></p>
                </form>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/auth.js"></script>
</body>
</html>