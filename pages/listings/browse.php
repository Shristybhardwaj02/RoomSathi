<?php
/**
 * RoomSaathi - Browse Listings (Card-based like Tinder)
 */
$pageTitle = 'Browse Listings';
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

// Get listings (show all for now - can exclude own later)
$allListings = getListings($filters);
// To exclude your own listings, uncomment the next 3 lines:
// $listings = array_filter($allListings, function($l) use ($userId) {
//     return $l['user_id'] != $userId;
// });
$listings = array_values($allListings);

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

require_once '../../includes/header.php';
?>

<div class="min-h-screen py-8 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-br from-primary/5 to-blue-400/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-0 w-80 h-80 bg-gradient-to-tr from-purple-400/10 to-primary/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-lg mx-auto px-4 relative">
        
        <!-- Premium Header -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary/10 to-blue-500/10 rounded-full mb-4">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                <span class="text-primary font-semibold text-sm">Live Listings</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                Find Your <span class="gradient-text">Perfect Space</span>
            </h1>
            <p class="text-gray-500">Swipe right to like, left to pass</p>
        </div>
            
        <!-- Filters -->
        <div class="bg-white rounded-2xl p-5 mb-6 shadow-lg border border-gray-100" style="animation: slideInRight 0.5s ease-out;">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <span class="font-semibold text-gray-800">Filter Listings</span>
            </div>
            <form method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm text-gray-600 mb-2 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        City
                    </label>
                    <select name="city" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-primary focus:ring-0 transition-all bg-gray-50 focus:bg-white">
                        <option value="">All Cities</option>
                        <?php foreach ($cities as $city): ?>
                        <option value="<?php echo $city; ?>" <?php echo (isset($_GET['city']) && $_GET['city'] === $city) ? 'selected' : ''; ?>>
                            <?php echo $city; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm text-gray-600 mb-2 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Max Rent
                    </label>
                    <select name="max_rent" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:border-primary focus:ring-0 transition-all bg-gray-50 focus:bg-white">
                        <option value="">Any</option>
                        <option value="10000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '10000') ? 'selected' : ''; ?>>₹10,000</option>
                        <option value="15000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '15000') ? 'selected' : ''; ?>>₹15,000</option>
                        <option value="20000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '20000') ? 'selected' : ''; ?>>₹20,000</option>
                        <option value="30000" <?php echo (isset($_GET['max_rent']) && $_GET['max_rent'] == '30000') ? 'selected' : ''; ?>>₹30,000</option>
                    </select>
                </div>
                <button type="submit" class="bg-gradient-to-r from-primary to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Search
                </button>
            </form>
        </div>

        <!-- Card Stack -->
        <div id="card-container" class="relative" style="height: 580px;">
            <?php if (empty($listings)): ?>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="empty-state">
                    <div class="empty-state-icon flex justify-center mb-4">
                        <svg class="w-20 h-20 text-primary/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">No Listings Found</h3>
                    <p class="text-gray-500 mb-4">Try changing your filters or check back later</p>
                    <a href="browse.php" class="btn-gradient text-white px-6 py-2.5 rounded-xl inline-block">Reset Filters</a>
                </div>
            </div>
            <?php else: ?>
            
            <?php foreach ($listings as $index => $listing): 
                $compatibility = calculateCompatibility($userId, $listing['user_id']);
                $photos = json_decode($listing['photos'], true) ?: [];
            ?>
            <div class="listing-card absolute inset-0 bg-white rounded-3xl overflow-hidden transition-all duration-300 shadow-xl border border-gray-100 <?php echo $index === 0 ? '' : 'hidden'; ?>" 
                 data-id="<?php echo $listing['id']; ?>"
                 data-index="<?php echo $index; ?>"
                 style="animation: scaleIn 0.4s ease-out;">
                
                <!-- Image -->
                <div class="h-72 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden image-container">
                    <?php if (!empty($photos)): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photos[0]; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-blue-500/10">
                            <svg class="w-32 h-32 text-primary/40" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Match Badge -->
                    <div class="absolute top-4 right-4 <?php echo $compatibility >= 70 ? 'bg-gradient-to-r from-green-500 to-emerald-600' : ($compatibility >= 50 ? 'bg-gradient-to-r from-yellow-500 to-amber-600' : 'bg-gradient-to-r from-orange-500 to-red-500'); ?> text-white px-4 py-2 rounded-2xl font-bold shadow-lg flex items-center gap-2">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        <?php echo $compatibility; ?>% Match
                    </div>
                    
                    <!-- Price on Image -->
                    <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-xl shadow-lg">
                        <span class="text-xl font-extrabold gradient-text">₹<?php echo number_format($listing['rent']); ?></span>
                        <span class="text-xs text-gray-400 font-normal">/month</span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-xl font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($listing['title']); ?></h2>
                    </div>
                    
                    <p class="text-gray-500 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <?php echo $listing['locality']; ?>, <?php echo $listing['city']; ?>
                    </p>
                    
                    <div class="flex gap-2 flex-wrap mb-4">
                        <span class="tag tag-primary">🛏️ <?php echo ucfirst($listing['room_type']); ?></span>
                        <span class="tag tag-primary">🪑 <?php echo ucfirst($listing['furnishing']); ?></span>
                        <span class="tag tag-primary">👤 <?php echo $listing['gender_preference'] === 'any' ? 'Any' : ucfirst($listing['gender_preference']); ?></span>
                    </div>
                    
                    <p class="text-gray-600 text-sm line-clamp-2"><?php echo htmlspecialchars(substr($listing['description'], 0, 100)); ?>...</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-white via-white/95 to-transparent">
                    <div class="flex justify-center gap-5">
                        <!-- Dislike/Pass Button - Pastel Red -->
                        <button onclick="passListing(<?php echo $listing['id']; ?>)" 
                                class="w-16 h-16 bg-red-100 hover:bg-red-200 border-2 border-red-200 hover:border-red-300 rounded-2xl flex items-center justify-center shadow-md hover:scale-110 hover:-rotate-12 transition-all group">
                            <svg class="w-8 h-8 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <!-- Details Button - Pastel Purple -->
                        <a href="details.php?id=<?php echo $listing['id']; ?>"
                           class="w-16 h-16 bg-purple-100 hover:bg-purple-200 border-2 border-purple-200 hover:border-purple-300 rounded-2xl flex items-center justify-center shadow-md hover:scale-110 transition-all group" title="View Details">
                            <svg class="w-7 h-7 text-purple-500 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <!-- Like Button - Pastel Green -->
                        <button onclick="likeListing(<?php echo $listing['id']; ?>)"
                                class="w-16 h-16 bg-green-100 hover:bg-green-200 border-2 border-green-200 hover:border-green-300 rounded-2xl flex items-center justify-center shadow-md hover:scale-110 hover:rotate-12 transition-all group">
                            <svg class="w-8 h-8 text-green-500 group-hover:text-green-600 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
                
            <!-- No More Cards -->
            <div id="no-more-cards" class="absolute inset-0 flex items-center justify-center hidden">
                <div class="empty-state page-wrapper">
                    <div class="empty-state-icon">
                        <svg class="w-16 h-16 text-primary mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">You've Seen All Listings!</h3>
                    <p class="text-gray-500 mb-6">Check back later for new rooms</p>
                    <a href="browse.php" class="btn-gradient text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center gap-2 hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Refresh
                    </a>
                </div>
            </div>
            
            <?php endif; ?>
        </div>

        <!-- Counter -->
        <div class="text-center mt-6">
            <div class="inline-flex items-center gap-3 px-6 py-3 glass-container">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-bold text-lg gradient-text" id="card-counter"><?php echo count($listings); ?></span>
                </div>
                <span class="text-gray-500">listings available</span>
            </div>
        </div>

    </div>
</div>

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

<?php require_once '../../includes/footer.php'; ?>

</body>
</html>
