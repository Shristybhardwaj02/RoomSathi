<?php
/**
 * RoomSaathi - Matches Page (Simplified)
 */
$pageTitle = 'My Matches';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];

// Get mutual matches (where both users liked each other's listings)
$sql = "SELECT DISTINCT 
            CASE WHEN l1.user_id = ? THEN l2.user_id ELSE l1.user_id END as matched_user_id,
            CASE WHEN l1.user_id = ? THEN l2.listing_id ELSE l1.listing_id END as their_listing_id,
            GREATEST(l1.created_at, l2.created_at) as matched_at
        FROM likes l1
        JOIN likes l2 ON l1.listing_owner_id = l2.user_id AND l1.user_id = l2.listing_owner_id
        WHERE (l1.user_id = ? OR l1.listing_owner_id = ?)
        ORDER BY matched_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiii", $userId, $userId, $userId, $userId);
mysqli_stmt_execute($stmt);
$matchesResult = mysqli_stmt_get_result($stmt);

$matches = [];
$processedUsers = [];

while ($row = mysqli_fetch_assoc($matchesResult)) {
    if (!in_array($row['matched_user_id'], $processedUsers)) {
        // Get matched user details
        $userSql = "SELECT * FROM users WHERE id = ?";
        $userStmt = mysqli_prepare($conn, $userSql);
        mysqli_stmt_bind_param($userStmt, "i", $row['matched_user_id']);
        mysqli_stmt_execute($userStmt);
        $user = mysqli_stmt_get_result($userStmt)->fetch_assoc();
        
        if ($user) {
            // Get their listing details
            $listingSql = "SELECT * FROM listings WHERE id = ?";
            $listingStmt = mysqli_prepare($conn, $listingSql);
            mysqli_stmt_bind_param($listingStmt, "i", $row['their_listing_id']);
            mysqli_stmt_execute($listingStmt);
            $listing = mysqli_stmt_get_result($listingStmt)->fetch_assoc();
            
            $matches[] = [
                'user' => $user,
                'listing' => $listing,
                'listing_id' => $row['their_listing_id'],
                'matched_at' => $row['matched_at']
            ];
            
            $processedUsers[] = $row['matched_user_id'];
        }
    }
}

// Get pending interests (people who liked your listings)
$pendingSql = "SELECT l.*, u.name, u.profile_photo, u.age, u.gender, u.city as user_city,
               lst.title as listing_title, lst.id as listing_id
               FROM likes l
               JOIN users u ON l.user_id = u.id
               JOIN listings lst ON l.listing_id = lst.id
               WHERE l.listing_owner_id = ? AND l.status = 'pending'
               ORDER BY l.created_at DESC LIMIT 5";
$pendingStmt = mysqli_prepare($conn, $pendingSql);
mysqli_stmt_bind_param($pendingStmt, "i", $userId);
mysqli_stmt_execute($pendingStmt);
$pendingResult = mysqli_stmt_get_result($pendingStmt);
$pendingInterests = mysqli_fetch_all($pendingResult, MYSQLI_ASSOC);

require_once '../../includes/header.php';
?>

<div class="min-h-screen py-8 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-br from-pink-400/10 to-rose-400/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-0 w-80 h-80 bg-gradient-to-tr from-primary/5 to-purple-400/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto px-4 relative">
        
        <!-- Premium Header -->
        <div class="text-center mb-10 page-wrapper">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-100 to-rose-100 rounded-full mb-4">
                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span class="text-pink-600 font-semibold text-sm">Your Connections</span>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-3">
                Your <span class="bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">Matches</span>
            </h1>
            <p class="text-gray-500">People who matched with you</p>
        </div>

        <?php if (!empty($pendingInterests)): ?>
        <!-- Pending Interests Banner -->
        <div class="bg-white rounded-2xl p-5 mb-6 border-l-4 border-yellow-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-100 to-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800"><?php echo count($pendingInterests); ?> people interested in your listings</p>
                        <p class="text-sm text-gray-500">Browse their profiles to match back!</p>
                    </div>
                </div>
                <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all text-sm font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Browse Now
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Matches List -->
        <?php if (empty($matches)): ?>
        <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-gray-100">
            <div class="w-24 h-24 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">No Matches Yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">When you and another user both like each other's listings, you'll match!</p>
            <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-rose-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-xl hover:-translate-y-1 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Browse Listings
            </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 gap-5 w-full">
            <?php foreach ($matches as $match): 
                $photos = [];
                if (!empty($match['listing']['photos'])) {
                    $raw = $match['listing']['photos'];
                    $photos = ($raw[0] === '[') ? json_decode($raw, true) : explode(',', $raw);
                }
                $photo = !empty($photos) ? $photos[0] : null;
            ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full">
                <!-- Listing Image - Full Background -->
                <div class="relative h-64 md:h-72 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden w-full">
                    <?php if ($photo): ?>
                    <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photo; ?>" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                         onerror="this.parentElement.innerHTML='<div class=\"w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-blue-50\"><svg class=\"w-16 h-16 text-primary/40\" fill=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z\"/></svg></div>
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-blue-50">
                        <svg class="w-16 h-16 text-primary/40" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Content Overlay -->
                    <!-- <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div> -->
                </div>
                
                <!-- Content Below Image -->
                <div class="p-5 w-full">
                    <div class="flex flex-col gap-3 mb-4 w-full">
                        <div class="flex items-start justify-between w-full">
                            <div class="flex items-center gap-3 flex-1">
                                <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $match['user']['profile_photo'] ?: 'default.jpg'; ?>" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-primary flex-shrink-0"
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($match['user']['name']); ?>&background=050f91&color=fff'">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-800 truncate"><?php echo htmlspecialchars($match['user']['name']); ?></h3>
                                        <p class="text-gray-500 text-sm truncate">
                                            <?php echo $match['user']['age'] ? $match['user']['age'] . ' yrs' : ''; ?>
                                            <?php if ($match['user']['city']): ?> • <?php echo $match['user']['city']; ?><?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1 rounded-xl text-sm font-medium shadow-sm whitespace-nowrap flex-shrink-0">Matched!</span>
                            </div>
                        </div>
                        
                        <?php if ($match['listing']): ?>
                        <div class="w-full">
                            <h4 class="font-medium text-gray-800 truncate"><?php echo htmlspecialchars($match['listing']['title']); ?></h4>
                            <p class="text-gray-500 text-sm truncate">
                                📍 <?php echo htmlspecialchars($match['listing']['locality'] . ', ' . $match['listing']['city']); ?> 
                                • ₹<?php echo number_format($match['listing']['rent']); ?>/month
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-3 w-full">
                        <a href="<?php echo SITE_URL; ?>/pages/chat/conversation.php?user=<?php echo $match['user']['id']; ?>" 
                           class="flex-1 bg-gradient-to-r from-primary to-blue-600 text-white py-2.5 rounded-xl text-center hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center gap-2 min-w-0">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="truncate">Message</span>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/pages/listings/details.php?id=<?php echo $match['listing_id']; ?>" 
                           class="px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all whitespace-nowrap flex-shrink-0">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
