<?php
/**
 * RoomSaathi - Settings Page
 */
$pageTitle = 'Settings';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];

// Get user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

$success = '';
$error = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'All password fields are required';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $error = 'Current password is incorrect';
        } elseif (strlen($newPassword) < 8) {
            $error = 'New password must be at least 8 characters';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "si", $hashedPassword, $userId);
            
            if (mysqli_stmt_execute($updateStmt)) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password';
            }
        }
    } elseif ($_POST['action'] === 'delete_account') {
        $confirmDelete = $_POST['confirm_delete'] ?? '';
        
        if ($confirmDelete === 'DELETE') {
            // Delete user account (cascade will handle related records)
            $deleteSql = "DELETE FROM users WHERE id = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, "i", $userId);
            
            if (mysqli_stmt_execute($deleteStmt)) {
                session_destroy();
                header('Location: ' . SITE_URL . '/?deleted=1');
                exit;
            } else {
                $error = 'Failed to delete account';
            }
        } else {
            $error = 'Please type DELETE to confirm account deletion';
        }
    }
}

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
                <p class="text-gray-500">Manage your account settings</p>
            </div>
            <a href="<?php echo SITE_URL; ?>/pages/profile/my-profile.php" 
               class="text-gray-600 hover:text-primary">
                ← Back to Profile
            </a>
        </div>

        <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <!-- Account Info -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Account Information
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Email</span>
                    <span class="font-medium"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Phone</span>
                    <span class="font-medium"><?php echo htmlspecialchars($user['phone']); ?></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Member Since</span>
                    <span class="font-medium"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">Account Status</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                        ✓ Verified
                    </span>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Change Password
            </h2>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="change_password">
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">New Password</label>
                    <input type="password" name="new_password" required minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-gray-400 text-sm mt-1">Minimum 8 characters</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <button type="submit" 
                        class="bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h2>
            
            <div class="space-y-2">
                <a href="<?php echo SITE_URL; ?>/pages/profile/edit.php" 
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </span>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/listings/my-listings.php" 
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        My Listings
                    </span>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/about.php" 
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        About RoomSaathi
                    </span>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/faq.php" 
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-all">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        FAQs
                    </span>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-red-100">
            <h2 class="text-lg font-semibold text-red-600 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Danger Zone
            </h2>
            
            <p class="text-gray-600 mb-4">
                Once you delete your account, there is no going back. Please be certain.
            </p>
            
            <button onclick="showDeleteModal()" 
                    class="bg-red-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-600 transition-all">
                Delete Account
            </button>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold text-red-600 mb-4">Delete Account?</h3>
        <p class="text-gray-600 mb-4">
            This will permanently delete your account, all listings, messages, and matches. This action cannot be undone.
        </p>
        
        <form method="POST">
            <input type="hidden" name="action" value="delete_account">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Type DELETE to confirm</label>
                <input type="text" name="confirm_delete" required
                       class="w-full px-4 py-3 border border-red-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent"
                       placeholder="DELETE">
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="hideDeleteModal()" 
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-red-600">
                    Delete Forever
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>

<?php require_once '../../includes/footer.php'; ?>
