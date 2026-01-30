<?php
/**
 * RoomSaathi - Browse Listings (Card-based like Tinder)
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];

// Get filters
$filters = [];
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $filters['city'] = sanitize($_GET['city']);
}
if (isset($_GET['min_rent']) && !empty($_GET['min_rent'])) {
    $filters['min_rent'] = (int)$_GET['min_rent'];
}
if (isset($_GET['max_rent']) && !empty($_GET['max_rent'])) {
    $filters['max_rent'] = (int)$_GET['max_rent'];
}

// Get listings (excluding user's own)
$allListings = getListings($filters);
$listings = array_filter($allListings, function($l) use ($userId) {
    return $l['user_id'] != $userId;
});
$listings = array_values($listings);

// Handle like action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $listingId = (int)$_POST['listing_id'];
    
    if ($_POST['action'] === 'like') {
        likeListing($userId, $listingId);
    }
    
    // Return JSON for AJAX
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

$cities = ['Bangalore', 'Mumbai', 'Delhi NCR', 'Pune', 'Hyderabad', 'Chennai'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Listings - RoomSaathi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#050f91',
                        'primary-dark': '#03085c',
                        'primary-light': '#E8EAFF',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="flex items-center">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="flex gap-6 items-center">
                <a href="../dashboard/index.php" class="text-gray-600 hover:text-primary">Dashboard</a>
                <a href="browse.php" class="text-primary font-medium">Browse</a>
                <a href="../matching/matches.php" class="text-gray-600 hover:text-primary">Matches</a>
                <a href="../profile/my-profile.php" class="text-gray-600 hover:text-primary">Profile</a>
                <a href="../auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 text-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-12 px-4">
        <div class="max-w-lg mx-auto">
            
            <!-- Filters -->
            <div class="bg-white rounded-xl p-4 mb-6 shadow">
                <form method="GET" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm text-gray-600 mb-1">City</label>
                        <select name="city" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="">All Cities</option>
                            <?php foreach ($cities as $city): ?>
                            <option value="<?php echo $city; ?>" <?php echo (isset($_GET['city']) && $_GET['city'] === $city) ? 'selected' : ''; ?>>
                                <?php echo $city; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm text-gray-600 mb-1">Max Rent</label>
                        <select name="max_rent" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="">Any</option>
                            <option value="10000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '10000') ? 'selected' : ''; ?>>₹10,000</option>
                            <option value="15000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '15000') ? 'selected' : ''; ?>>₹15,000</option>
                            <option value="20000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '20000') ? 'selected' : ''; ?>>₹20,000</option>
                            <option value="30000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '30000') ? 'selected' : ''; ?>>₹30,000</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Card Stack -->
            <div id="card-container" class="relative" style="height: 550px;">
                <?php if (empty($listings)): ?>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <span class="text-6xl block mb-4">🏠</span>
                        <h3 class="text-xl font-bold mb-2">No Listings Found</h3>
                        <p>Try changing your filters or check back later</p>
                    </div>
                </div>
                <?php else: ?>
                
                <?php foreach ($listings as $index => $listing): 
                    $compatibility = calculateCompatibility($userId, $listing['user_id']);
                    $photos = json_decode($listing['photos'], true) ?: [];
                ?>
                <div class="listing-card absolute inset-0 bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 <?php echo $index === 0 ? '' : 'hidden'; ?>" 
                     data-id="<?php echo $listing['id']; ?>"
                     data-index="<?php echo $index; ?>">
                    
                    <!-- Image -->
                    <div class="h-64 bg-gray-200 relative">
                        <?php if (!empty($photos)): ?>
                            <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photos[0]; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-8xl">🏠</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Match Badge -->
                        <div class="absolute top-4 right-4 <?php echo $compatibility >= 70 ? 'bg-green-500' : ($compatibility >= 50 ? 'bg-yellow-500' : 'bg-orange-500'); ?> text-white px-4 py-2 rounded-full font-bold shadow-lg">
                            <?php echo $compatibility; ?>% Match
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($listing['title']); ?></h2>
                        </div>
                        
                        <p class="text-gray-500 mb-2">📍 <?php echo $listing['locality']; ?>, <?php echo $listing['city']; ?></p>
                        
                        <p class="text-primary text-2xl font-bold mb-4">₹<?php echo number_format($listing['rent']); ?><span class="text-sm text-gray-500 font-normal">/month</span></p>
                        
                        <div class="flex gap-2 flex-wrap mb-4">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">🛏️ <?php echo ucfirst($listing['room_type']); ?></span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">🪑 <?php echo ucfirst($listing['furnishing']); ?></span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">👤 <?php echo $listing['gender_preference'] === 'any' ? 'Any Gender' : ucfirst($listing['gender_preference']); ?></span>
                        </div>
                        
                        <p class="text-gray-600 text-sm line-clamp-2"><?php echo htmlspecialchars(substr($listing['description'], 0, 100)); ?>...</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-white via-white">
                        <div class="flex justify-center gap-6">
                            <button onclick="passListing(<?php echo $listing['id']; ?>)" 
                                    class="w-16 h-16 bg-gray-200 hover:bg-red-100 rounded-full flex items-center justify-center text-3xl shadow-lg hover:scale-110 transition">
                                ❌
                            </button>
                            <button onclick="likeListing(<?php echo $listing['id']; ?>)"
                                    class="w-16 h-16 bg-primary hover:bg-green-500 rounded-full flex items-center justify-center text-3xl shadow-lg hover:scale-110 transition text-white">
                                ❤️
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- No More Cards -->
                <div id="no-more-cards" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="text-center text-gray-500">
                        <span class="text-6xl block mb-4">✨</span>
                        <h3 class="text-xl font-bold mb-2">You've Seen All Listings!</h3>
                        <p class="mb-4">Check back later for new rooms</p>
                        <a href="browse.php" class="text-primary hover:underline">Refresh</a>
                    </div>
                </div>
                
                <?php endif; ?>
            </div>

            <!-- Counter -->
            <div class="text-center mt-4 text-gray-500">
                <span id="card-counter"><?php echo count($listings); ?></span> listings available
            </div>

        </div>
    </main>

    <script>
        let currentIndex = 0;
        const cards = document.querySelectorAll('.listing-card');
        const totalCards = cards.length;

        function showNextCard() {
            if (currentIndex < totalCards) {
                cards[currentIndex].classList.add('hidden');
            }
            currentIndex++;
            
            if (currentIndex < totalCards) {
                cards[currentIndex].classList.remove('hidden');
                document.getElementById('card-counter').textContent = totalCards - currentIndex;
            } else {
                document.getElementById('no-more-cards').classList.remove('hidden');
                document.getElementById('card-counter').textContent = '0';
            }
        }

        function likeListing(listingId) {
            // Animate card
            const card = document.querySelector(`[data-id="${listingId}"]`);
            card.style.transform = 'translateX(100%) rotate(20deg)';
            card.style.opacity = '0';
            
            // Send like to server
            fetch('browse.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=like&listing_id=${listingId}`
            });
            
            setTimeout(showNextCard, 300);
        }

        function passListing(listingId) {
            // Animate card
            const card = document.querySelector(`[data-id="${listingId}"]`);
            card.style.transform = 'translateX(-100%) rotate(-20deg)';
            card.style.opacity = '0';
            
            setTimeout(showNextCard, 300);
        }
    </script>

</body>
</html>
