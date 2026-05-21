<?php
/**
 * RoomSaathi - Messages/Chat List Page
 */
$pageTitle = 'Messages';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];

// Get all conversations (grouped by user)
$sql = "SELECT 
            CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END as other_user_id,
            MAX(m.created_at) as last_message_time,
            SUM(CASE WHEN m.receiver_id = ? AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
        FROM messages m
        WHERE m.sender_id = ? OR m.receiver_id = ?
        GROUP BY other_user_id
        ORDER BY last_message_time DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiii", $userId, $userId, $userId, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$conversations = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Get user details
    $userSql = "SELECT id, name, profile_photo, occupation FROM users WHERE id = ?";
    $userStmt = mysqli_prepare($conn, $userSql);
    mysqli_stmt_bind_param($userStmt, "i", $row['other_user_id']);
    mysqli_stmt_execute($userStmt);
    $user = mysqli_stmt_get_result($userStmt)->fetch_assoc();
    
    // Get last message
    $msgSql = "SELECT message, sender_id, created_at FROM messages 
               WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
               ORDER BY created_at DESC LIMIT 1";
    $msgStmt = mysqli_prepare($conn, $msgSql);
    mysqli_stmt_bind_param($msgStmt, "iiii", $userId, $row['other_user_id'], $row['other_user_id'], $userId);
    mysqli_stmt_execute($msgStmt);
    $lastMsg = mysqli_stmt_get_result($msgStmt)->fetch_assoc();
    
    if ($user) {
        $conversations[] = [
            'user' => $user,
            'last_message' => $lastMsg,
            'unread_count' => $row['unread_count'],
            'last_time' => $row['last_message_time']
        ];
    }
}

// Calculate total unread
$totalUnread = array_sum(array_column($conversations, 'unread_count'));

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Messages</h1>
                <p class="text-gray-500">
                    <?php echo $totalUnread > 0 ? $totalUnread . ' unread message(s)' : 'Your conversations'; ?>
                </p>
            </div>
            <a href="<?php echo SITE_URL; ?>/pages/matching/matches.php" 
               class="text-primary hover:text-primary-dark font-medium">
                View Matches →
            </a>
        </div>

        <?php if (empty($conversations)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="w-24 h-24 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Messages Yet</h3>
            <p class="text-gray-500 mb-6">Start connecting with potential roommates!</p>
            <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" 
               class="inline-block bg-primary text-white px-6 py-3 rounded-xl hover:bg-primary-dark transition-all">
                Browse Listings
            </a>
        </div>
        <?php else: ?>
        <!-- Conversations List -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <?php foreach ($conversations as $index => $conv): ?>
            <a href="<?php echo SITE_URL; ?>/pages/chat/conversation.php?user=<?php echo $conv['user']['id']; ?>" 
               class="block p-4 hover:bg-gray-50 transition-all <?php echo $index > 0 ? 'border-t border-gray-100' : ''; ?>">
                <div class="flex items-center gap-4">
                    <!-- Avatar -->
                    <div class="relative">
                        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $conv['user']['profile_photo'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($conv['user']['name']); ?>"
                             class="w-14 h-14 rounded-full object-cover <?php echo $conv['unread_count'] > 0 ? 'border-2 border-primary' : ''; ?>"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($conv['user']['name']); ?>&background=050f91&color=fff'">
                        <?php if ($conv['unread_count'] > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium">
                            <?php echo $conv['unread_count']; ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800 <?php echo $conv['unread_count'] > 0 ? '' : 'font-normal'; ?>">
                                <?php echo htmlspecialchars($conv['user']['name']); ?>
                            </h3>
                            <span class="text-xs text-gray-400">
                                <?php echo timeAgo($conv['last_time']); ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 truncate mt-1 <?php echo $conv['unread_count'] > 0 ? 'font-medium text-gray-700' : ''; ?>">
                            <?php if ($conv['last_message']['sender_id'] == $userId): ?>
                            <span class="text-gray-400">You: </span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($conv['last_message']['message']); ?>
                        </p>
                    </div>

                    <!-- Arrow -->
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
