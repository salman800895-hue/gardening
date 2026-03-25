<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4><i class="fas fa-leaf"></i> GreenThumb</h4>
            <p>Your gardening companion with 100+ plant guides</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <a href="../index.php">Home</a>
            <a href="plants.php">Plant Guide</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="community.php">Community</a>
                <a href="dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="register.php">Join Free</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
        <div class="footer-section">
            <h4>Connect</h4>
            <p><i class="fas fa-envelope"></i> hello@greenthumb.com</p>
            <div class="social-links">
                <i class="fab fa-facebook"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-pinterest"></i>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> GreenThumb Community. Grow together, harvest health.</p>
    </div>
</footer>