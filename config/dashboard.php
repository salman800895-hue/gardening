<?php
require_once '../config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Get user stats
$stmt = $pdo->prepare("SELECT COUNT(*) as post_count FROM posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$post_count = $stmt->fetch()['post_count'];

$stmt = $pdo->prepare("SELECT SUM(likes) as total_likes FROM posts WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_likes = $stmt->fetch()['total_likes'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as saved_count FROM saved_plants WHERE user_id = ?");
$stmt->execute([$user_id]);
$saved_count = $stmt->fetch()['saved_count'];

// Get recent posts
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$my_posts = $stmt->fetchAll();

// Get user info
$user = getUser($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GreenThumb Community</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <div class="welcome-card">
                <h1>Welcome back, <?php echo escape($_SESSION['username']); ?>!</h1>
                <p>Your gardening journey continues. Share your experiences with the community.</p>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-leaf"></i>
                <h3><?php echo $post_count; ?></h3>
                <p>Your Posts</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-heart"></i>
                <h3><?php echo $total_likes; ?></h3>
                <p>Total Likes</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-bookmark"></i>
                <h3><?php echo $saved_count; ?></h3>
                <p>Saved Plants</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-seedling"></i>
                <h3>100+</h3>
                <p>Plants Database</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-pen"></i> Quick Actions</h2>
            </div>
            <div class="action-buttons">
                <a href="community.php" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create New Post</a>
                <a href="plants.php" class="btn btn-outline"><i class="fas fa-search"></i> Browse Plants</a>
                <a href="profile.php" class="btn btn-outline"><i class="fas fa-user-edit"></i> Edit Profile</a>
            </div>
        </div>
        
        <div class="card">
            <h2><i class="fas fa-history"></i> Your Recent Posts</h2>
            <?php if (count($my_posts) > 0): ?>
                <div class="posts-list">
                    <?php foreach ($my_posts as $post): ?>
                        <div class="post-item">
                            <div class="post-header">
                                <span><i class="fas fa-user-circle"></i> <?php echo escape($_SESSION['username']); ?></span>
                                <small><?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                            </div>
                            <p><?php echo escape($post['content']); ?></p>
                            <div class="post-footer">
                                <span><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> likes</span>
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn-small">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-posts">You haven't posted anything yet. <a href="community.php">Share your first gardening experience!</a></p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2><i class="fas fa-lightbulb"></i> Recommended Plants for You</h2>
            <div class="plants-grid">
                <?php
                $stmt = $pdo->query("SELECT * FROM plants ORDER BY RAND() LIMIT 4");
                $recommended = $stmt->fetchAll();
                foreach ($recommended as $plant): ?>
                <div class="plant-card" onclick="window.location.href='plants.php?plant=<?php echo $plant['id']; ?>'">
                    <img src="../assets/images/plants/<?php echo strtolower(str_replace(' ', '-', $plant['name'])); ?>.jpg" 
                         onerror="this.src='https://via.placeholder.com/300x200?text=' + encodeURIComponent('<?php echo $plant['name']; ?>')"
                         alt="<?php echo escape($plant['name']); ?>">
                    <div class="plant-info">
                        <h3><?php echo escape($plant['name']); ?></h3>
                        <span class="badge"><?php echo escape($plant['category']); ?></span>
                        <p><?php echo substr(escape($plant['benefits']), 0, 60); ?>...</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/app.js"></script>
</body>
</html>