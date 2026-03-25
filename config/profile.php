<?php
require_once '../config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user = getUser($user_id);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $bio = trim($_POST['bio']);
    
    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, bio = ? WHERE id = ?");
    $stmt->execute([$fullname, $bio, $user_id]);
    
    header('Location: profile.php?updated=1');
    exit;
}

// Get user posts
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$user_posts = $stmt->fetchAll();

// Get saved plants
$stmt = $pdo->prepare("
    SELECT p.* FROM plants p 
    JOIN saved_plants s ON p.id = s.plant_id 
    WHERE s.user_id = ? 
    ORDER BY s.created_at DESC
");
$stmt->execute([$user_id]);
$saved_plants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GreenThumb</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="profile-container">
            <div class="card profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle" style="font-size: 6rem; color: var(--green-primary);"></i>
                    </div>
                    <div class="profile-info">
                        <h1><?php echo escape($user['username']); ?></h1>
                        <p class="email"><i class="fas fa-envelope"></i> <?php echo escape($user['email']); ?></p>
                        <p class="joined"><i class="fas fa-calendar-alt"></i> Joined <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
                
                <div class="profile-bio">
                    <h3><i class="fas fa-leaf"></i> About My Garden</h3>
                    <p><?php echo escape($user['bio'] ?? 'No bio yet. Click Edit Profile to add your gardening story.'); ?></p>
                </div>
                
                <button id="edit-profile-btn" class="btn btn-outline"><i class="fas fa-edit"></i> Edit Profile</button>
                
                <div id="edit-profile-form" style="display: none;">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" value="<?php echo escape($user['fullname'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" rows="3"><?php echo escape($user['bio'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                        <button type="button" id="cancel-edit" class="btn btn-outline">Cancel</button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-pen"></i> My Posts (<?php echo count($user_posts); ?>)</h2>
                <?php if (count($user_posts) > 0): ?>
                    <div class="posts-list">
                        <?php foreach ($user_posts as $post): ?>
                            <div class="post-item">
                                <div class="post-header">
                                    <small><?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                                </div>
                                <p><?php echo escape(substr($post['content'], 0, 150)); ?>...</p>
                                <div class="post-footer">
                                    <span><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> likes</span>
                                    <a href="post.php?id=<?php echo $post['id']; ?>" class="btn-small">View</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>You haven't posted anything yet. <a href="community.php">Share your first gardening experience!</a></p>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-bookmark"></i> Saved Plants (<?php echo count($saved_plants); ?>)</h2>
                <?php if (count($saved_plants) > 0): ?>
                    <div class="plants-grid small-grid">
                        <?php foreach ($saved_plants as $plant): ?>
                            <div class="plant-card" onclick="window.location.href='plants.php?plant=<?php echo $plant['id']; ?>'">
                                <img src="../assets/images/plants/<?php echo strtolower(str_replace(' ', '-', $plant['name'])); ?>.jpg" 
                                     onerror="this.src='https://via.placeholder.com/150x100?text=' + encodeURIComponent('<?php echo $plant['name']; ?>')"
                                     alt="<?php echo escape($plant['name']); ?>">
                                <div class="plant-info">
                                    <h4><?php echo escape($plant['name']); ?></h4>
                                    <span class="badge small"><?php echo escape($plant['category']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>You haven't saved any plants yet. <a href="plants.php">Browse our plant database</a> and save your favorites!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script>
        document.getElementById('edit-profile-btn')?.addEventListener('click', function() {
            document.getElementById('edit-profile-form').style.display = 'block';
            this.style.display = 'none';
        });
        document.getElementById('cancel-edit')?.addEventListener('click', function() {
            document.getElementById('edit-profile-form').style.display = 'none';
            document.getElementById('edit-profile-btn').style.display = 'inline-flex';
        });
        
        <?php if (isset($_GET['updated'])): ?>
            alert('Profile updated successfully!');
            window.location.href = 'profile.php';
        <?php endif; ?>
    </script>
</body>
</html>