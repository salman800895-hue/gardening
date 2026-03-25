<?php
require_once '../config/database.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = trim($_POST['fullname']);
    $bio = trim($_POST['bio']);
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username already taken';
        } else {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered';
            } else {
                // Create user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, fullname, bio) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashed_password, $fullname, $bio])) {
                    $user_id = $pdo->lastInsertId();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Registration failed. Please try again.';
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
    <title>Register - GreenThumb Community</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/forms.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="form-container">
            <div class="card form-card">
                <h2><i class="fas fa-user-plus"></i> Join GreenThumb Community</h2>
                <p class="subtitle">Get access to 100+ plant guides and connect with gardeners</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="form">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Username *</label>
                        <input type="text" name="username" required value="<?php echo escape($_POST['username'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" required value="<?php echo escape($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password *</label>
                        <input type="password" name="password" required>
                        <small>At least 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirm Password *</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-user-tag"></i> Full Name (Optional)</label>
                        <input type="text" name="fullname" value="<?php echo escape($_POST['fullname'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-leaf"></i> About Your Garden (Optional)</label>
                        <textarea name="bio" rows="3"><?php echo escape($_POST['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Create Free Account <i class="fas fa-check-circle"></i></button>
                    
                    <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                </form>
                
                <div class="benefits-list">
                    <h3>What you'll get:</h3>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Access to 100+ plant growing guides</li>
                        <li><i class="fas fa-check-circle"></i> Share your gardening journey</li>
                        <li><i class="fas fa-check-circle"></i> Get tips from experienced gardeners</li>
                        <li><i class="fas fa-check-circle"></i> Save your favorite plants</li>
                        <li><i class="fas fa-check-circle"></i> Join a supportive community</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/auth.js"></script>
</body>
</html>