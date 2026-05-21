<?php
/**
 * RoomSaathi - Listing Details Page
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Get listing ID
$listingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$listingId) {
    header('Location: ' . SITE_URL . '/pages/listings/browse.php');
    exit;
}

// Fetch listing details
global $conn;
$sql = "SELECT l.*, u.name as owner_name, u.profile_photo as owner_photo, u.age as owner_age, 
        u.gender as owner_gender, u.occupation as owner_occupation, u.bio as owner_bio,
        u.id as owner_id
        FROM listings l 
        JOIN users u ON l.user_id = u.id 
        WHERE l.id = ? AND l.status = 'active'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $listingId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    header('Location: ' . SITE_URL . '/pages/listings/browse.php');
    exit;
}

// Update view count
$updateViews = "UPDATE listings SET views = views + 1 WHERE id = ?";
$viewStmt = mysqli_prepare($conn, $updateViews);
mysqli_stmt_bind_param($viewStmt, "i", $listingId);
mysqli_stmt_execute($viewStmt);

// Check if user has already liked this listing
$hasLiked = false;
if (isLoggedIn()) {
    $likeCheckSql = "SELECT id FROM likes WHERE user_id = ? AND listing_id = ?";
    $likeStmt = mysqli_prepare($conn, $likeCheckSql);
    mysqli_stmt_bind_param($likeStmt, "ii", $_SESSION['user_id'], $listingId);
    mysqli_stmt_execute($likeStmt);
    $hasLiked = mysqli_stmt_get_result($likeStmt)->num_rows > 0;
}

// Calculate compatibility if logged in and not own listing
$compatibility = 0;
if (isLoggedIn() && $_SESSION['user_id'] != $listing['owner_id']) {
    $compatibility = calculateCompatibility($_SESSION['user_id'], $listing['owner_id']);
}

$pageTitle = $listing['title'];

// Parse amenities - handle both JSON and comma-separated formats
$amenities = [];
if (!empty($listing['amenities'])) {
    $raw = $listing['amenities'];
    // Check if it's JSON format
    if (strpos($raw, '[') === 0 || strpos($raw, '{') === 0) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $amenities = $decoded;
        }
    } else {
        // Comma-separated format
        $amenities = explode(',', $raw);
    }
}

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Back Button -->
        <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" 
           class="inline-flex items-center text-gray-600 hover:text-primary mb-6 card-material px-4 py-2 w-fit hover-lift">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Browse
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Photo Gallery -->
                <div class="card-material overflow-hidden">
                    <?php 
                    $photosRaw = $listing['photos'];
                    // Handle both JSON array and comma-separated formats
                    if (!empty($photosRaw) && $photosRaw[0] === '[') {
                        $photos = json_decode($photosRaw, true) ?: [];
                    } else {
                        $photos = !empty($photosRaw) ? explode(',', $photosRaw) : [];
                    }
                    $mainPhoto = !empty($photos) ? $photos[0] : null;
                    ?>
                    <div class="relative h-80 md:h-96">
                        <?php if ($mainPhoto): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $mainPhoto; ?>" 
                             alt="<?php echo htmlspecialchars($listing['title']); ?>"
                             class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800'">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <svg class="w-32 h-32 text-primary/40" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-lg">
                                Available
                            </span>
                        </div>
                        
                        <!-- Views -->
                        <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <?php echo $listing['views']; ?> views
                        </div>
                    </div>
                    
                    <?php if (count($photos) > 1): ?>
                    <div class="p-4 flex gap-2 overflow-x-auto">
                        <?php foreach ($photos as $photo): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photo; ?>" 
                             class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-75"
                             onclick="this.parentElement.previousElementSibling.querySelector('img').src = this.src">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Listing Details -->
                <div class="card-material p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($listing['title']); ?></h1>
                            <p class="text-gray-500 flex items-center mt-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <?php echo htmlspecialchars($listing['locality'] . ', ' . $listing['city']); ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-primary">₹<?php echo number_format($listing['rent']); ?></p>
                            <p class="text-gray-500 text-sm">/month</p>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-5 my-4 bg-gradient-to-r from-primary/5 to-blue-50 rounded-xl px-4">
                        <div class="text-center">
                            <p class="text-gray-500 text-sm">Room Type</p>
                            <p class="font-semibold capitalize"><?php echo $listing['room_type']; ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500 text-sm">Furnishing</p>
                            <p class="font-semibold capitalize"><?php echo str_replace('-', ' ', $listing['furnishing']); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500 text-sm">Preferred For</p>
                            <p class="font-semibold capitalize"><?php echo $listing['gender_preference']; ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500 text-sm">Deposit</p>
                            <p class="font-semibold">₹<?php echo number_format($listing['deposit']); ?></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">About This Place</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($listing['description'])); ?>
                        </p>
                    </div>

                    <!-- Amenities -->
                    <?php if (!empty($amenities)): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Amenities</h3>
                        <div class="flex flex-wrap gap-3">
                            <?php 
                            // Clean amenities - handle JSON format
                            $cleanAmenities = [];
                            foreach ($amenities as $amenity) {
                                // Remove quotes, brackets, and extra characters
                                $clean = trim($amenity, '[]"\' ');
                                $clean = str_replace(['"', "'", '[', ']'], '', $clean);
                                $clean = trim(strtolower($clean));
                                if (!empty($clean)) {
                                    $cleanAmenities[] = $clean;
                                }
                            }
                            
                            $amenityIcons = [
                                'wifi' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>',
                                'ac' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                                'parking' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm4 4h4a2 2 0 110 4h-4V7zm0 4v6"></path></svg>',
                                'gym' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l3-9 4 18 3-9h4"></path></svg>',
                                'laundry' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm8 5a5 5 0 100 10 5 5 0 000-10z"></path></svg>',
                                'washing machine' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm8 5a5 5 0 100 10 5 5 0 000-10z"></path></svg>',
                                'kitchen' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3zm3 6h12M6 15h12"></path></svg>',
                                'tv' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                                'balcony' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                                'security' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                                'power backup' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                                'water supply' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"></path></svg>',
                                'water' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"></path></svg>',
                                'cleaning' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>'
                            ];
                            
                            foreach ($cleanAmenities as $amenity): 
                                $iconSvg = isset($amenityIcons[$amenity]) ? $amenityIcons[$amenity] : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            ?>
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary/5 to-blue-50 text-gray-700 rounded-full text-sm font-medium border border-primary/10 hover:border-primary/30 hover:shadow-sm transition-all">
                                <span class="text-primary"><?php echo $iconSvg; ?></span>
                                <?php echo ucfirst(str_replace('_', ' ', $amenity)); ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Available From -->
                    <div class="mt-6 flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Available from: <span class="font-semibold ml-1">
                            <?php echo $listing['available_from'] ? date('M d, Y', strtotime($listing['available_from'])) : 'Immediately'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Owner Card -->
                <div class="card-material p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Posted By</h3>
                    
                    <div class="flex items-center mb-4">
                        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $listing['owner_photo'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($listing['owner_name']); ?>"
                             class="w-16 h-16 rounded-full object-cover border-2 border-primary"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($listing['owner_name']); ?>&background=050f91&color=fff'">
                        <div class="ml-4">
                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($listing['owner_name']); ?></p>
                            <p class="text-gray-500 text-sm">
                                <?php echo $listing['owner_occupation'] ?: 'RoomSaathi Member'; ?>
                            </p>
                            <?php if ($listing['owner_age'] && $listing['owner_gender']): ?>
                            <p class="text-gray-400 text-sm capitalize">
                                <?php echo $listing['owner_age']; ?> yrs, <?php echo $listing['owner_gender']; ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($listing['owner_bio'])): ?>
                    <p class="text-gray-600 text-sm italic border-l-4 border-primary pl-3 mb-4">
                        "<?php echo htmlspecialchars($listing['owner_bio']); ?>"
                    </p>
                    <?php endif; ?>

                    <!-- Compatibility Score -->
                    <?php if (isLoggedIn() && isset($compatibility) && $compatibility > 0): ?>
                    <div class="bg-gradient-to-r from-primary to-blue-600 rounded-xl p-4 text-white mb-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Compatibility Score</span>
                            <span class="text-2xl font-bold"><?php echo $compatibility; ?>%</span>
                        </div>
                        <div class="w-full bg-white/30 rounded-full h-2 mt-2">
                            <div class="bg-white rounded-full h-2" style="width: <?php echo $compatibility; ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if (isLoggedIn()): ?>
                        <?php if ($_SESSION['user_id'] == $listing['owner_id']): ?>
                            <a href="<?php echo SITE_URL; ?>/pages/listings/edit.php?id=<?php echo $listingId; ?>" 
                               class="w-full bg-gradient-to-r from-primary to-blue-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Listing
                            </a>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php if ($hasLiked): ?>
                                    <button disabled class="w-full bg-green-100 text-green-600 py-3 rounded-xl font-semibold flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                                        </svg>
                                        Already Interested
                                    </button>
                                <?php else: ?>
                                    <form action="<?php echo SITE_URL; ?>/pages/listings/browse.php" method="POST">
                                        <input type="hidden" name="listing_id" value="<?php echo $listingId; ?>">
                                        <input type="hidden" name="action" value="like">
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            I'm Interested
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <a href="<?php echo SITE_URL; ?>/pages/chat/conversation.php?user=<?php echo $listing['owner_id']; ?>&listing=<?php echo $listingId; ?>" 
                                   class="w-full bg-gradient-to-r from-primary to-blue-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Send Message
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/pages/auth/login.php" 
                           class="w-full bg-gradient-to-r from-primary to-blue-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center">
                            Login to Contact
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Location Map Placeholder -->
                <div class="card-material p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Location</h3>
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl h-48 flex items-center justify-center border border-gray-200">
                        <div class="text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="font-medium"><?php echo htmlspecialchars($listing['locality']); ?></p>
                            <p class="text-sm"><?php echo htmlspecialchars($listing['city']); ?></p>
                        </div>
                    </div>
                    <?php if (!empty($listing['address'])): ?>
                    <p class="text-gray-600 text-sm mt-3">
                        <span class="font-medium">Full Address:</span><br>
                        <?php echo htmlspecialchars($listing['address']); ?>
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Posted Date -->
                <div class="card-material p-6">
                    <div class="flex items-center text-gray-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Posted <?php echo timeAgo($listing['created_at']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
