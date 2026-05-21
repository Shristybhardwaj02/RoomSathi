<?php
/**
 * RoomSaathi - Admin Users Management
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

$pageTitle = 'Manage Users';
$message = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = intval($_POST['user_id'] ?? 0);
    
    if ($action === 'delete' && $userId > 0 && $userId != $_SESSION['user_id']) {
        // Delete user's listings first
        $pdo->prepare("DELETE FROM listings WHERE user_id = ?")->execute([$userId]);
        // Delete user's messages
        $pdo->prepare("DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?")->execute([$userId, $userId]);
        // Delete user's likes
        $pdo->prepare("DELETE FROM likes WHERE user_id = ?")->execute([$userId]);
        // Delete user preferences
        $pdo->prepare("DELETE FROM user_preferences WHERE user_id = ?")->execute([$userId]);
        // Delete user
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
        $message = 'User deleted successfully';
    }
    
    if ($action === 'verify' && $userId > 0) {
        $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?")->execute([$userId]);
        $message = 'User verified successfully';
    }
}

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Search & Filter
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$whereClause = "1=1";
$params = [];

if (!empty($search)) {
    $whereClause .= " AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($filter === 'verified') {
    $whereClause .= " AND is_verified = 1";
} elseif ($filter === 'unverified') {
    $whereClause .= " AND is_verified = 0";
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $whereClause");
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $perPage);

// Get users
$stmt = $pdo->prepare("
    SELECT u.*, 
           (SELECT COUNT(*) FROM listings WHERE user_id = u.id) as listing_count
    FROM users u 
    WHERE $whereClause 
    ORDER BY u.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$users = $stmt->fetchAll();
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
                   class="flex items-center px-6 py-3 bg-white/10 border-r-4 border-white">
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
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Manage Users</h1>
                <p class="text-gray-500">Total: <?php echo $totalUsers; ?> users</p>
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
                        <input type="text" name="search" placeholder="Search name, email, phone..." 
                               value="<?php echo htmlspecialchars($search); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <select name="filter" class="px-4 py-2 border rounded-lg">
                        <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Users</option>
                        <option value="verified" <?php echo $filter === 'verified' ? 'selected' : ''; ?>>Verified</option>
                        <option value="unverified" <?php echo $filter === 'unverified' ? 'selected' : ''; ?>>Unverified</option>
                    </select>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                        Search
                    </button>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Contact</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Listings</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Joined</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
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
                                        <p class="text-gray-500 text-sm"><?php echo ucfirst($u['user_type'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-800 text-sm"><?php echo htmlspecialchars($u['email']); ?></p>
                                <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($u['phone'] ?? 'N/A'); ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">
                                    <?php echo $u['listing_count']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($u['is_verified']): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Verified</span>
                                <?php else: ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                <?php echo date('M j, Y', strtotime($u['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <?php if (!$u['is_verified']): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="verify">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <button type="submit" class="text-green-600 hover:text-green-800" title="Verify">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($users)): ?>
                <div class="text-center py-12 text-gray-500">
                    No users found
                </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo $filter; ?>"
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
