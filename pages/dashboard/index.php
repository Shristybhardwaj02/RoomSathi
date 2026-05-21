<?php
/**
 * RoomSaathi - Dashboard
 */
$pageTitle = 'Dashboard';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

// Check if profile is complete
if ($_SESSION['profile_complete'] == 0) {
    redirect('/pages/profile/setup-step1.php');
}

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
$stats = getDashboardStats($userId);
$recentListings = getListings(['limit' => 3]);

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-8 page-enter">
    <div class="max-w-6xl mx-auto px-4">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-primary via-blue-700 to-indigo-800 text-white rounded-3xl p-8 mb-8 shadow-primary-lg relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute inset-0">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 right-20 w-32 h-32 border border-white/10 rounded-full"></div>
                </div>
                <div class="flex items-center gap-6 relative z-10">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center overflow-hidden shadow-lg border border-white/20 hover-scale">
                        <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                            <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-4xl">👤</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold mb-2 text-shadow-lg">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 👋</h1>
                        <p class="text-blue-200 flex items-center gap-2">
                            <span class="chip bg-white/20 text-white text-xs py-1"><?php echo $user['occupation']; ?></span>
                            <span class="w-1 h-1 bg-blue-300 rounded-full"></span>
                            <span><?php echo $user['city']; ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
                <div class="stat-card hover-scale-sm cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-primary/10 to-blue-100 w-14 h-14 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold gradient-text"><?php echo $stats['listings']; ?></p>
                            <p class="text-gray-400 font-medium text-sm">My Listings</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card hover-scale-sm cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-green-100 to-emerald-100 w-14 h-14 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💚</span>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-green-600"><?php echo $stats['matches']; ?></p>
                            <p class="text-gray-400 font-medium text-sm">Matches</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card hover-scale-sm cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-yellow-100 to-amber-100 w-14 h-14 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💌</span>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-amber-600"><?php echo $stats['interests']; ?></p>
                            <p class="text-gray-400 font-medium text-sm">New Interests</p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card hover-scale-sm cursor-default">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-purple-100 to-pink-100 w-14 h-14 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl">💬</span>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-purple-600">0</p>
                            <p class="text-gray-400 font-medium text-sm">Messages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <a href="../listings/browse.php" class="p-6 rounded-2xl border border-gray-200/60 bg-white/50 backdrop-blur-sm group hover:border-primary/30 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary/10 to-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 group-hover:text-primary transition-colors">Browse Rooms</h3>
                            <p class="text-gray-400 text-sm">Find your perfect roommate</p>
                        </div>
                    </div>
                </a>
                
                <a href="../listings/post.php" class="p-6 rounded-2xl border border-gray-200/60 bg-white/50 backdrop-blur-sm group hover:border-green-300 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 group-hover:text-green-600 transition-colors">Post a Room</h3>
                            <p class="text-gray-400 text-sm">List your room for rent</p>
                        </div>
                    </div>
                </a>
                
                <a href="../matching/matches.php" class="p-6 rounded-2xl border border-gray-200/60 bg-white/50 backdrop-blur-sm group hover:border-pink-300 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 group-hover:text-pink-600 transition-colors">View Matches</h3>
                            <p class="text-gray-400 text-sm">See your roommate matches</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Listings -->
            <div class="rounded-2xl border border-gray-200/60 bg-white/50 backdrop-blur-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Recent Listings Near You</h2>
                    <a href="../listings/browse.php" class="inline-flex items-center gap-1 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold hover:bg-primary/20 transition-all">
                        View All 
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                <?php if (empty($recentListings)): ?>
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/10 to-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Listings Yet</h3>
                    <p class="text-gray-500">Be the first to post a room!</p>
                </div>
                <?php else: ?>
                <div class="grid md:grid-cols-3 gap-5">
                    <?php foreach ($recentListings as $listing): ?>
                    <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                        <div class="bg-gradient-to-br from-primary/5 to-blue-50 h-40 flex items-center justify-center relative overflow-hidden">
                            <?php 
                            $photos = json_decode($listing['photos'], true);
                            if ($photos && count($photos) > 0): 
                            ?>
                                <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photos[0]; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <span class="text-5xl">🏠</span>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 group-hover:text-primary transition-colors"><?php echo htmlspecialchars($listing['title']); ?></h3>
                            <p class="text-gray-400 text-sm flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <?php echo $listing['locality']; ?>, <?php echo $listing['city']; ?>
                            </p>
                            <p class="gradient-text font-extrabold mt-2 text-lg">₹<?php echo number_format($listing['rent']); ?><span class="text-gray-400 text-sm font-normal">/month</span></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>
