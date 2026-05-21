<?php
/**
 * RoomSaathi - Admin Listings Management
 */
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/auth/login.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);
$isAdmin = ($user['id'] == 1 || $user['email'] == 'admin@roomsaathi.com');

if (!$isAdmin) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit;
}

$pageTitle = 'Manage Listings';
$message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $listingId = intval($_POST['listing_id'] ?? 0);
    
    if ($action === 'delete' && $listingId > 0) {
        // Delete related likes
        $pdo->prepare("DELETE FROM likes WHERE listing_id = ?")->execute([$listingId]);
        // Delete listing
        $pdo->prepare("DELETE FROM listings WHERE id = ?")->execute([$listingId]);
        $message = 'Listing deleted successfully';
    }
    
    if ($action === 'toggle_status' && $listingId > 0) {
        $newStatus = $_POST['new_status'] ?? 'active';
        $pdo->prepare("UPDATE listings SET status = ? WHERE id = ?")->execute([$newStatus, $listingId]);
        $message = 'Listing status updated';
    }
}

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Search & Filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';

$whereClause = "1=1";
$params = [];

if (!empty($search)) {
    $whereClause .= " AND (l.title LIKE ? OR l.locality LIKE ? OR l.city LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($statusFilter !== 'all') {
    $whereClause .= " AND l.status = ?";
    $params[] = $statusFilter;
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM listings l WHERE $whereClause");
$countStmt->execute($params);
$totalListings = $countStmt->fetchColumn();
$totalPages = ceil($totalListings / $perPage);

// Get listings
$stmt = $pdo->prepare("
    SELECT l.*, u.full_name as owner_name, u.email as owner_email,
           (SELECT COUNT(*) FROM likes WHERE listing_id = l.id) as like_count
    FROM listings l 
    JOIN users u ON l.user_id = u.id 
    WHERE $whereClause 
    ORDER BY l.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$listings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - RoomSaathi Admin</title>
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
                   class="flex items-center px-6 py-3 hover:bg-white/10 transition-all">
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
                   class="flex items-center px-6 py-3 bg-white/10 border-r-4 border-white">
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
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Manage Listings</h1>
                <p class="text-gray-500">Total: <?php echo $totalListings; ?> listings</p>
            </div>

            <?php if ($message): ?>
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow mb-6 p-4">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Search title, location..." 
                               value="<?php echo htmlspecialchars($search); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <select name="status" class="px-4 py-2 border rounded-lg">
                        <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="active" <?php echo $statusFilter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $statusFilter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="rented" <?php echo $statusFilter === 'rented' ? 'selected' : ''; ?>>Rented</option>
                    </select>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                        Search
                    </button>
                </form>
            </div>

            <!-- Listings Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($listings as $listing): ?>
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <!-- Image -->
                    <div class="relative aspect-video bg-gray-200">
                        <?php 
                        $photos = json_decode($listing['photos'], true);
                        if (!empty($photos)): 
                        ?>
                        <img src="<?php echo SITE_URL . '/uploads/' . $photos[0]; ?>" 
                             alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-medium
                            <?php echo $listing['status'] === 'active' ? 'bg-green-500 text-white' : 
                                ($listing['status'] === 'rented' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white'); ?>">
                            <?php echo ucfirst($listing['status']); ?>
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-1 truncate">
                            <?php echo htmlspecialchars($listing['title']); ?>
                        </h3>
                        <p class="text-gray-500 text-sm mb-2">
                            <?php echo htmlspecialchars($listing['locality'] . ', ' . $listing['city']); ?>
                        </p>
                        <p class="text-primary font-bold">₹<?php echo number_format($listing['rent']); ?>/mo</p>

                        <!-- Owner Info -->
                        <div class="mt-3 pt-3 border-t flex items-center justify-between">
                            <div class="text-sm">
                                <p class="text-gray-600"><?php echo htmlspecialchars($listing['owner_name']); ?></p>
                                <p class="text-gray-400 text-xs"><?php echo date('M j, Y', strtotime($listing['created_at'])); ?></p>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <?php echo $listing['like_count']; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3 pt-3 border-t flex gap-2">
                            <a href="<?php echo SITE_URL; ?>/pages/listings/details.php?id=<?php echo $listing['id']; ?>" 
                               target="_blank"
                               class="flex-1 text-center py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                                View
                            </a>
                            
                            <form method="POST" class="flex-1">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                <?php if ($listing['status'] === 'active'): ?>
                                <input type="hidden" name="new_status" value="inactive">
                                <button type="submit" class="w-full py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 text-sm">
                                    Deactivate
                                </button>
                                <?php else: ?>
                                <input type="hidden" name="new_status" value="active">
                                <button type="submit" class="w-full py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm">
                                    Activate
                                </button>
                                <?php endif; ?>
                            </form>
                            
                            <form method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($listings)): ?>
            <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
                No listings found
            </div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex justify-center gap-2 mt-8">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $statusFilter; ?>"
                   class="px-4 py-2 rounded-lg <?php echo $i === $page ? 'bg-primary text-white' : 'bg-white hover:bg-gray-100'; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
