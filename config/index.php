<?php
require_once 'config/database.php';

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenThumb Community - Grow Together</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    
    <main class="container">
        <!-- Hero Section with Registration First -->
        <div class="hero-section">
            <div class="hero-content">
                <h1>Welcome to GreenThumb Community</h1>
                <p>Join thousands of gardeners sharing their passion for growing vegetables, fruits, and herbs. Get access to 100+ detailed plant guides!</p>
                <div class="hero-stats">
                    <div class="stat"><i class="fas fa-seedling"></i> 100+ Plants</div>
                    <div class="stat"><i class="fas fa-users"></i> Active Community</div>
                    <div class="stat"><i class="fas fa-leaf"></i> Expert Tips</div>
                </div>
                <div class="hero-buttons">
                    <a href="pages/register.php" class="btn btn-primary btn-large"><i class="fas fa-user-plus"></i> Join Free Now</a>
                    <a href="pages/login.php" class="btn btn-outline btn-large"><i class="fas fa-sign-in-alt"></i> Login</a>
                </div>
            </div>
            <div class="hero-image">
                <i class="fas fa-leaf" style="font-size: 6rem; color: var(--green-primary);"></i>
                <i class="fas fa-apple-alt" style="font-size: 5rem; color: var(--red-accent);"></i>
                <i class="fas fa-carrot" style="font-size: 5rem; color: var(--blue-bright);"></i>
            </div>
        </div>

        <!-- Featured Plants -->
        <div class="card">
            <div class="section-header">
                <h2><i class="fas fa-apple-alt"></i> Featured Plants from Our Database (100+)</h2>
                <a href="pages/plants.php" class="btn-small">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="plants-grid" id="featured-plants">
                <?php
                $stmt = $pdo->query("SELECT * FROM plants ORDER BY RAND() LIMIT 8");
                $featuredPlants = $stmt->fetchAll();
                foreach($featuredPlants as $plant): ?>
                <div class="plant-card" onclick="window.location.href='pages/plants.php?plant=<?php echo $plant['id']; ?>'">
                    <img src="assets/images/plants/<?php echo strtolower(str_replace(' ', '-', $plant['name'])); ?>.jpg" 
                         onerror="this.src='https://via.placeholder.com/300x200?text=' + encodeURIComponent('<?php echo $plant['name']; ?>')"
                         alt="<?php echo escape($plant['name']); ?>">
                    <div class="plant-info">
                        <h3><?php echo escape($plant['name']); ?></h3>
                        <span class="badge"><?php echo escape($plant['category']); ?></span>
                        <p><?php echo substr(escape($plant['benefits']), 0, 80); ?>...</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Gardening Tips -->
        <div class="card">
            <h2><i class="fas fa-lightbulb"></i> Expert Gardening Tips</h2>
            <div class="tips-grid">
                <div class="tip-card">
                    <i class="fas fa-recycle"></i>
                    <h3>Compost Making</h3>
                    <p>Mix green (kitchen scraps) and brown (dry leaves) materials. Turn weekly for rich soil amendment. Ratio 3:1 brown to green.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-tint"></i>
                    <h3>Smart Watering</h3>
                    <p>Water deeply at base early morning. Mulch to retain moisture. Avoid wetting leaves to prevent fungal diseases.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-bug"></i>
                    <h3>Natural Pest Control</h3>
                    <p>Neem oil spray, companion planting with marigolds, handpick pests, attract beneficial insects like ladybugs.</p>
                </div>
                <div class="tip-card">
                    <i class="fas fa-calendar"></i>
                    <h3>Seasonal Planning</h3>
                    <p>Start seeds indoors 6-8 weeks before last frost. Succession planting for continuous harvest. Rotate crops annually.</p>
                </div>
            </div>
        </div>

        <!-- Why Join Section -->
        <div class="card">
            <h2><i class="fas fa-heart"></i> Why Join GreenThumb?</h2>
            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-database"></i>
                    <h3>100+ Plant Database</h3>
                    <p>Complete growing guides with planting instructions, harvest times, and health benefits</p>
                </div>
                <div class="feature">
                    <i class="fas fa-comments"></i>
                    <h3>Active Community</h3>
                    <p>Share experiences, ask questions, get answers from fellow gardeners worldwide</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <h3>Track Your Garden</h3>
                    <p>Save favorite plants, track your growing journey, and share your harvest</p>
                </div>
                <div class="feature">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Expert Guides</h3>
                    <p>Step-by-step instructions for every plant, from seed to harvest</p>
                </div>
            </div>
        </div>

        <!-- Recent Community Posts -->
        <div class="card">
            <h2><i class="fas fa-users"></i> Recent Community Posts</h2>
            <div class="posts-list">
                <?php
                $stmt = $pdo->query("SELECT p.*, u.username, u.profile_image FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 3");
                $recentPosts = $stmt->fetchAll();
                foreach($recentPosts as $post): ?>
                <div class="post-preview">
                    <div class="post-header">
                        <span><i class="fas fa-user-circle"></i> <?php echo escape($post['username']); ?></span>
                        <small><?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                    </div>
                    <p><?php echo escape(substr($post['content'], 0, 150)); ?>...</p>
                    <div class="post-stats">
                        <span><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> likes</span>
                        <a href="pages/register.php" class="btn-small">Join to Interact</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="pages/register.php" class="btn btn-outline">Join to See More Posts</a>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
    <script src="js/app.js"></script>
</body>
</html>