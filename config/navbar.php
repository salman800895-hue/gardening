<?php
$isLoggedIn = isset($_SESSION['user_id']);
?>
<nav class="navbar">
    <div class="logo">
        <i class="fas fa-leaf"></i>
        <a href="../index.php">GreenThumb</a>
    </div>
    <div class="nav-links">
        <a href="../index.php"><i class="fas fa-home"></i> Home</a>
        <?php if ($isLoggedIn): ?>
            <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="community.php"><i class="fas fa-users"></i> Community</a>
            <a href="plants.php"><i class="fas fa-apple-alt"></i> Plants</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="logout.php" class="nav-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <span class="user-greeting"><i class="fas fa-user-circle"></i> <?php echo escape($_SESSION['username']); ?></span>
        <?php else: ?>
            <a href="plants.php"><i class="fas fa-apple-alt"></i> Plants</a>
            <a href="register.php" class="nav-btn"><i class="fas fa-user-plus"></i> Join Free</a>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </div>
</nav>