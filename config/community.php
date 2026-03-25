<?php
require_once '../config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$success_message = '';

// Handle new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $content])) {
            $success_message = 'Post created successfully!';
        }
    }
}

// Handle like
if (isset($_GET['like'])) {
    $post_id = $_GET['like'];
    try {
        $stmt = $pdo->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $user_id]);
        $stmt = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
        $stmt->execute([$post_id]);
    } catch (PDOException $e) {
        // Already liked
    }
    header("Location: community.php");
    exit;
}

// Handle comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $comment]);
    }
    header("Location: community.php");
    exit;
}

// Get all posts
$stmt = $pdo->query("
    SELECT p.*, u.username, u.profile_image,
    (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = $user_id) as user_liked
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();

// Get comments for each post
foreach ($posts as &$post) {
    $stmt = $pdo->prepare("
        SELECT c.*, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$post['id']]);
    $post['comments'] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - GreenThumb</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="card">
            <div class="community-header">
                <h2><i class="fas fa-users"></i> Gardener's Community</h2>
                <p>Share your experiences, ask questions, and connect with fellow gardeners</p>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <!-- Create Post Form -->
            <div class="create-post">
                <h3><i class="fas fa-pen"></i> Share Your Gardening Experience</h3>
                <form method="POST" action="">
                    <textarea name="content" rows="3" placeholder="What's growing in your garden? Share tips, questions, or harvest photos..." required></textarea>
                    <button type="submit" name="create_post" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Publish Post</button>
                </form>
            </div>
        </div>
        
        <!-- Posts Feed -->
        <?php foreach ($posts as $post): ?>
        <div class="card post-card" id="post-<?php echo $post['id']; ?>">
            <div class="post-header">
                <div class="post-author">
                    <i class="fas fa-user-circle"></i>
                    <strong><?php echo escape($post['username']); ?></strong>
                </div>
                <small><?php echo date('F j, Y \a\t g:i a', strtotime($post['created_at'])); ?></small>
            </div>
            
            <div class="post-content">
                <p><?php echo nl2br(escape($post['content'])); ?></p>
            </div>
            
            <div class="post-actions">
                <?php if ($post['user_liked']): ?>
                    <span class="liked"><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> likes</span>
                <?php else: ?>
                    <a href="?like=<?php echo $post['id']; ?>" class="like-btn"><i class="far fa-heart"></i> <?php echo $post['likes']; ?> likes</a>
                <?php endif; ?>
                <span class="comment-count"><i class="far fa-comment"></i> <?php echo count($post['comments']); ?> comments</span>
            </div>
            
            <!-- Comments Section -->
            <div class="comments-section">
                <h4>Comments</h4>
                <?php foreach ($post['comments'] as $comment): ?>
                    <div class="comment">
                        <strong><?php echo escape($comment['username']); ?></strong>
                        <p><?php echo escape($comment['comment']); ?></p>
                        <small><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
                
                <form method="POST" action="" class="comment-form">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="text" name="comment" placeholder="Add a comment..." required>
                    <button type="submit" name="comment" class="btn-small">Post</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/posts.js"></script>
</body>
</html>