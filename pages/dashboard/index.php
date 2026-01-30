<?php
/**
 * RoomSaathi - Dashboard
 */
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RoomSaathi</title>
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
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="flex items-center">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="flex gap-6 items-center">
                <a href="index.php" class="text-primary font-medium">Dashboard</a>
                <a href="../listings/browse.php" class="text-gray-600 hover:text-primary">Browse</a>
                <a href="../matching/matches.php" class="text-gray-600 hover:text-primary">Matches</a>
                <a href="../profile/my-profile.php" class="text-gray-600 hover:text-primary">Profile</a>
                <a href="../auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 text-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4">
        <div class="max-w-6xl mx-auto">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-primary to-blue-800 text-white rounded-2xl p-8 mb-8">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center overflow-hidden">
                        <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                            <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-4xl">👤</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 👋</h1>
                        <p class="text-blue-200"><?php echo $user['occupation']; ?> • <?php echo $user['city']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary-light w-14 h-14 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🏠</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['listings']; ?></p>
                            <p class="text-gray-600">My Listings</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 w-14 h-14 rounded-full flex items-center justify-center">
                            <span class="text-2xl">💚</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['matches']; ?></p>
                            <p class="text-gray-600">Matches</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-100 w-14 h-14 rounded-full flex items-center justify-center">
                            <span class="text-2xl">💌</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['interests']; ?></p>
                            <p class="text-gray-600">New Interests</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="bg-purple-100 w-14 h-14 rounded-full flex items-center justify-center">
                            <span class="text-2xl">💬</span>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                            <p class="text-gray-600">Messages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="../listings/browse.php" class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary text-white w-14 h-14 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                            <span class="text-2xl">🔍</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">Browse Rooms</h3>
                            <p class="text-gray-600">Find your perfect roommate</p>
                        </div>
                    </div>
                </a>
                
                <a href="../listings/post.php" class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                            <span class="text-2xl">➕</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">Post a Room</h3>
                            <p class="text-gray-600">List your room for rent</p>
                        </div>
                    </div>
                </a>
                
                <a href="../matching/matches.php" class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition group">
                    <div class="flex items-center gap-4">
                        <div class="bg-pink-500 text-white w-14 h-14 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                            <span class="text-2xl">❤️</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">View Matches</h3>
                            <p class="text-gray-600">See your roommate matches</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Listings -->
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Recent Listings Near You</h2>
                    <a href="../listings/browse.php" class="text-primary hover:underline">View All →</a>
                </div>
                
                <?php if (empty($recentListings)): ?>
                <div class="text-center py-12 text-gray-500">
                    <span class="text-5xl block mb-4">🏠</span>
                    <p>No listings yet. Be the first to post!</p>
                </div>
                <?php else: ?>
                <div class="grid md:grid-cols-3 gap-6">
                    <?php foreach ($recentListings as $listing): ?>
                    <div class="border rounded-xl overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gray-200 h-40 flex items-center justify-center">
                            <?php 
                            $photos = json_decode($listing['photos'], true);
                            if ($photos && count($photos) > 0): 
                            ?>
                                <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photos[0]; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-5xl">🏠</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($listing['title']); ?></h3>
                            <p class="text-gray-500 text-sm"><?php echo $listing['locality']; ?>, <?php echo $listing['city']; ?></p>
                            <p class="text-primary font-bold mt-2">₹<?php echo number_format($listing['rent']); ?>/month</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

</body>
</html>
