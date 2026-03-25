<?php
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardening Tips - GreenThumb</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <main class="container">
        <div class="card">
            <h2><i class="fas fa-lightbulb"></i> Essential Gardening Tips</h2>
            <p>Pro tips to help you grow a thriving garden</p>
            
            <div class="tips-list">
                <div class="tip-section">
                    <h3><i class="fas fa-seedling"></i> Soil Preparation</h3>
                    <ul>
                        <li>Test soil pH annually - most vegetables prefer 6.0-7.0</li>
                        <li>Add organic compost to improve soil structure</li>
                        <li>Rotate crops to prevent nutrient depletion</li>
                        <li>Use cover crops like clover in off-season</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h3><i class="fas fa-tint"></i> Watering Wisdom</h3>
                    <ul>
                        <li>Water deeply but less frequently to encourage deep roots</li>
                        <li>Water early morning to reduce evaporation</li>
                        <li>Use drip irrigation or soaker hoses for efficiency</li>
                        <li>Mulch to retain moisture and suppress weeds</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h3><i class="fas fa-sun"></i> Sunlight Requirements</h3>
                    <ul>
                        <li>Most vegetables need 6-8 hours of direct sun</li>
                        <li>Leafy greens tolerate partial shade</li>
                        <li>Observe your garden's sun patterns throughout the day</li>
                        <li>Use reflective surfaces to increase light in shady areas</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h3><i class="fas fa-bug"></i> Natural Pest Control</h3>
                    <ul>
                        <li>Companion planting: marigolds repel nematodes, basil repels flies</li>
                        <li>Attract beneficial insects like ladybugs and lacewings</li>
                        <li>Use neem oil or insecticidal soap for soft-bodied pests</li>
                        <li>Handpick larger pests like caterpillars and beetles</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h3><i class="fas fa-chart-line"></i> Fertilizing Guide</h3>
                    <ul>
                        <li>Use balanced fertilizer (10-10-10) for most vegetables</li>
                        <li>Nitrogen-rich fertilizer for leafy greens</li>
                        <li>Phosphorus-rich for root crops and flowers</li>
                        <li>Compost tea as gentle organic fertilizer</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h3><i class="fas fa-calendar"></i> Seasonal Tasks</h3>
                    <ul>
                        <li>Spring: Start seeds indoors, prepare beds, plant cool-season crops</li>
                        <li>Summer: Mulch, water deeply, harvest regularly, watch for pests</li>
                        <li>Fall: Plant garlic, clean up debris, add compost, plant cover crops</li>
                        <li>Winter: Plan next year's garden, maintain tools, start seeds indoors</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../components/footer.php'; ?>
</body>
</html>