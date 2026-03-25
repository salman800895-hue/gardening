<?php
require_once '../config/database.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plant_id = $_POST['plant_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];
    
    if ($action === 'save') {
        $stmt = $pdo->prepare("INSERT INTO saved_plants (user_id, plant_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $plant_id]);
    } elseif ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM saved_plants WHERE user_id = ? AND plant_id = ?");
        $stmt->execute([$user_id, $plant_id]);
    }
    
    header("Location: plants.php?plant=$plant_id");
    exit;
}
?>