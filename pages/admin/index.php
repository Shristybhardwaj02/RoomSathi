<?php
/**
 * RoomSaathi - Admin Dashboard
 */
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/auth/login.php');
    exit;
}

// For demo, allow first user or specific email as admin
$user = getUserById($_SESSION['user_id']);
$isAdmin = ($user['id'] == 1 || $user['email'] == 'admin@roomsaathi.com');

if (!$isAdmin) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit;
}

$pageTitle = 'Admin Dashboard';

// Get stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalListings = $pdo->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$activeListings = $pdo->query("SELECT COUNT(*) FROM listings WHERE status = 'active'")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$totalLikes = $pdo->query("SELECT COUNT(*) FROM likes")->fetchColumn();

// Recent users
$recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Recent listings
$recentListings = $pdo->query("
    SELECT l.*, u.full_name as owner_name 
    FROM listings l 
    JOIN users u ON l.user_id = u.id 
    ORDER BY l.created_at DESC 
    LIMIT 5
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - RoomSaathi</title>
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
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white">
            <div class="p-6">
                <a href="<?php echo SITE_URL; ?>" class="flex items-center gap-2 group">
                    <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-12 brightness-0 invert">
                    <span class="text-lg font-semibold text-white hidden sm:inline">RoomSathi</span>
                </a>
            </div>
            <nav class="mt-6">
                <a href="<?php echo SITE_URL; ?>/pages/admin/index.php" 
                   class="flex items-center px-6 py-3 bg-white/10 border-r-4 border-white">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/admin/users.php" 
                   class="flex items-center px-6 py-3 hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/admin/listings.php" 
                   class="flex items-center px-6 py-3 hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Listings
                </a>
                <div class="border-t border-white/20 my-4"></div>
                <a href="<?php echo SITE_URL; ?>/pages/dashboard.php" 
                   class="flex items-center px-6 py-3 hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Back to Site
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
                    <p class="text-gray-500">Welcome back, <?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>
                <div class="text-sm text-gray-500">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></p>
                            <p class="text-gray-500 text-sm">Total Users</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalListings; ?></p>
                            <p class="text-gray-500 text-sm">Total Listings</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $activeListings; ?></p>
                            <p class="text-gray-500 text-sm">Active Listings</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalMessages; ?></p>
                            <p class="text-gray-500 text-sm">Messages</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalLikes; ?></p>
                            <p class="text-gray-500 text-sm">Total Likes</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Users</h2>
                        <a href="users.php" class="text-primary text-sm hover:underline">View All</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($recentUsers as $u): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 overflow-hidden">
                                        <?php if (!empty($u['profile_photo'])): ?>
                                        <img src="<?php echo SITE_URL . '/uploads/' . $u['profile_photo']; ?>" 
                                             alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                        <span class="text-gray-500 font-medium">
                                            <?php echo strtoupper(substr($u['full_name'], 0, 1)); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($u['full_name']); ?></p>
                                        <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($u['email']); ?></p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">
                                    <?php echo date('M j', strtotime($u['created_at'])); ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Listings</h2>
                        <a href="listings.php" class="text-primary text-sm hover:underline">View All</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($recentListings as $listing): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 mr-3 overflow-hidden">
                                        <?php 
                                        $photos = json_decode($listing['photos'], true);
                                        if (!empty($photos)): 
                                        ?>
                                        <img src="<?php echo SITE_URL . '/uploads/' . $photos[0]; ?>" 
                                             alt="" class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            <?php echo htmlspecialchars(substr($listing['title'], 0, 25)); ?>...
                                        </p>
                                        <p class="text-gray-500 text-sm">
                                            By <?php echo htmlspecialchars($listing['owner_name']); ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php echo $listing['status'] === 'active' ? 'bg-green-100 text-green-700' : 
                                        ($listing['status'] === 'rented' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'); ?>">
                                    <?php echo ucfirst($listing['status']); ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
