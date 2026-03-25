<?php
require_once '../config/database.php';
requireLogin();

$post_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT p.*, u.username, u.profile_image 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: community.php');
    exit;
}

// Get comments
$stmt = $pdo->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = ? 
    ORDER BY c.created_at ASC
");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();

// Handle comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $_SESSION['user_id'], $comment]);
        header("Location: post.php?id=$post_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post - GreenThumb</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="card post-detail">
            <a href="community.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Community</a>
            
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
                <span><i class="fas fa-heart"></i> <?php echo $post['likes']; ?> likes</span>
                <span><i class="fas fa-comment"></i> <?php echo count($comments); ?> comments</span>
            </div>
            
            <div class="comments-section">
                <h3>Comments (<?php echo count($comments); ?>)</h3>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?php echo escape($comment['username']); ?></strong>
                        <p><?php echo escape($comment['comment']); ?></p>
                        <small><?php echo date('M d, Y', strtotime($comment['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
                
                <form method="POST" action="" class="comment-form">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
</body>
</html>