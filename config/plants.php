<?php
require_once '../config/database.php';

$category = $_GET['category'] ?? 'All';
$search = $_GET['search'] ?? '';
$plant_id = $_GET['plant'] ?? null;

// Get single plant detail
if ($plant_id) {
    $stmt = $pdo->prepare("SELECT * FROM plants WHERE id = ?");
    $stmt->execute([$plant_id]);
    $plant = $stmt->fetch();
    
    if (!$plant) {
        header('Location: plants.php');
        exit;
    }
    
    // Check if saved by user
    $saved = false;
    if (isLoggedIn()) {
        $stmt = $pdo->prepare("SELECT id FROM saved_plants WHERE user_id = ? AND plant_id = ?");
        $stmt->execute([$_SESSION['user_id'], $plant_id]);
        $saved = $stmt->fetch() ? true : false;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo escape($plant['name']); ?> - Plant Guide</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/navbar.css">
    </head>
    <body>
        <?php include '../components/navbar.php'; ?>
        
        <main class="container">
            <div class="plant-detail">
                <div class="card">
                    <a href="plants.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Plants</a>
                    
                    <div class="plant-detail-header">
                        <img src="../assets/images/plants/<?php echo strtolower(str_replace(' ', '-', $plant['name'])); ?>.jpg" 
                             onerror="this.src='https://via.placeholder.com/600x400?text=' + encodeURIComponent('<?php echo $plant['name']; ?>')"
                             alt="<?php echo escape($plant['name']); ?>">
                        <div class="plant-detail-info">
                            <h1><?php echo escape($plant['name']); ?></h1>
                            <span class="badge large"><?php echo escape($plant['category']); ?></span>
                            <span class="badge difficulty <?php echo strtolower($plant['difficulty']); ?>"><?php echo escape($plant['difficulty']); ?> Difficulty</span>
                            
                            <?php if (isLoggedIn()): ?>
                                <form method="POST" action="save_plant.php" style="display: inline;">
                                    <input type="hidden" name="plant_id" value="<?php echo $plant['id']; ?>">
                                    <input type="hidden" name="action" value="<?php echo $saved ? 'remove' : 'save'; ?>">
                                    <button type="submit" class="btn <?php echo $saved ? 'btn-outline' : 'btn-primary'; ?>">
                                        <i class="fas fa-<?php echo $saved ? 'bookmark' : 'bookmark'; ?>"></i>
                                        <?php echo $saved ? 'Saved' : 'Save Plant'; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="plant-detail-section">
                        <h3><i class="fas fa-heartbeat"></i> Health Benefits</h3>
                        <p><?php echo escape($plant['benefits']); ?></p>
                    </div>
                    
                    <div class="plant-detail-section">
                        <h3><i class="fas fa-seedling"></i> How to Plant & Grow</h3>
                        <p><?php echo escape($plant['planting_guide']); ?></p>
                    </div>
                    
                    <div class="plant-detail-grid">
                        <div class="info-card">
                            <i class="fas fa-calendar"></i>
                            <strong>Best Season</strong>
                            <p><?php echo escape($plant['season']); ?></p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-clock"></i>
                            <strong>Harvest Time</strong>
                            <p><?php echo escape($plant['harvest_time']); ?></p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-sun"></i>
                            <strong>Sun Requirement</strong>
                            <p><?php echo escape($plant['sun_requirement']); ?></p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-tint"></i>
                            <strong>Water Needs</strong>
                            <p><?php echo escape($plant['water_needs']); ?></p>
                        </div>
                    </div>
                    
                    <div class="plant-detail-section">
                        <h3><i class="fas fa-lightbulb"></i> Growing Tips</h3>
                        <ul>
                            <li>Space plants appropriately for good air circulation</li>
                            <li>Mulch around base to retain moisture and suppress weeds</li>
                            <li>Monitor for pests and diseases regularly</li>
                            <li>Harvest at peak ripeness for best flavor</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        
        <?php include '../components/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

// List all plants
$query = "SELECT * FROM plants WHERE 1=1";
$params = [];

if ($category !== 'All') {
    $query .= " AND category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $query .= " AND name LIKE ?";
    $params[] = "%$search%";
}

$query .= " ORDER BY name";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$plants = $stmt->fetchAll();

// Get unique categories
$stmt = $pdo->query("SELECT DISTINCT category FROM plants ORDER BY category");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Guide - GreenThumb</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="card">
            <h2><i class="fas fa-apple-alt"></i> Complete Plant Guide</h2>
            <p>Browse our database of <?php echo count($plants); ?> vegetables, fruits, herbs, and more</p>
            
            <!-- Search and Filter -->
            <div class="plant-filters">
                <form method="GET" action="" class="search-form">
                    <input type="text" name="search" placeholder="Search plants..." value="<?php echo escape($search); ?>">
                    <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
                </form>
                
                <div class="category-filters">
                    <a href="?category=All<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="filter-chip <?php echo $category === 'All' ? 'active' : ''; ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="?category=<?php echo urlencode($cat['category']); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="filter-chip <?php echo $category === $cat['category'] ? 'active' : ''; ?>">
                            <?php echo escape($cat['category']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Plants Grid -->
            <div class="plants-grid large-grid">
                <?php foreach ($plants as $plant): ?>
                    <div class="plant-card" onclick="window.location.href='?plant=<?php echo $plant['id']; ?>'">
                        <img src="../assets/images/plants/<?php echo strtolower(str_replace(' ', '-', $plant['name'])); ?>.jpg" 
                             onerror="this.src='https://via.placeholder.com/300x200?text=' + encodeURIComponent('<?php echo $plant['name']; ?>')"
                             alt="<?php echo escape($plant['name']); ?>">
                        <div class="plant-info">
                            <h3><?php echo escape($plant['name']); ?></h3>
                            <span class="badge"><?php echo escape($plant['category']); ?></span>
                            <span class="difficulty-badge <?php echo strtolower($plant['difficulty']); ?>">
                                <?php echo escape($plant['difficulty']); ?>
                            </span>
                            <p><?php echo substr(escape($plant['benefits']), 0, 80); ?>...</p>
                            <div class="plant-meta">
                                <span><i class="fas fa-clock"></i> <?php echo escape($plant['harvest_time']); ?></span>
                                <span><i class="fas fa-sun"></i> <?php echo escape($plant['sun_requirement']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($plants) === 0): ?>
                <p class="no-results">No plants found. Try a different search.</p>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/app.js"></script>
</body>
</html>