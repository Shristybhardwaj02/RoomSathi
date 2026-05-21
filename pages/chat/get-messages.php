<?php
/**
 * Endpoint: get-messages.php
 * Accepts POST: other_user_id, listing_id (optional)
 * Returns HTML fragment of messages between current user and other_user
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];
$otherUserId = isset($_POST['other_user_id']) ? (int)$_POST['other_user_id'] : 0;
$listingId = isset($_POST['listing_id']) && $_POST['listing_id'] !== '' ? (int)$_POST['listing_id'] : null;

if (!$otherUserId || $otherUserId == $userId) {
    echo '';
    exit;
}

// Mark incoming messages as read
$markReadSql = "UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?";
$markReadStmt = mysqli_prepare($conn, $markReadSql);
mysqli_stmt_bind_param($markReadStmt, "ii", $otherUserId, $userId);
mysqli_stmt_execute($markReadStmt);

// Fetch messages
$msgSql = "SELECT m.*, 
           CASE WHEN m.sender_id = ? THEN 'sent' ELSE 'received' END as direction
           FROM messages m
           WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
           ORDER BY m.created_at ASC";
$msgStmt = mysqli_prepare($conn, $msgSql);
mysqli_stmt_bind_param($msgStmt, "iiiii", $userId, $userId, $otherUserId, $otherUserId, $userId);
mysqli_stmt_execute($msgStmt);
$messages = mysqli_fetch_all(mysqli_stmt_get_result($msgStmt), MYSQLI_ASSOC);

// Render HTML similar to conversation.php
foreach ($messages as $msg) {
    $direction = $msg['direction'];
    $safeMsg = htmlspecialchars($msg['message']);
    $time = date('h:i A', strtotime($msg['created_at']));
    $isRead = isset($msg['is_read']) && $msg['is_read'] ? true : false;
    ?>
    <div class="flex <?php echo $direction === 'sent' ? 'justify-end' : 'justify-start'; ?>">
        <div class="max-w-[75%] <?php echo $direction === 'sent' 
                ? 'bg-primary text-white rounded-2xl rounded-br-md' 
                : 'bg-white text-gray-800 rounded-2xl rounded-bl-md shadow'; ?> px-4 py-3">
            <p class="whitespace-pre-wrap break-words"><?php echo $safeMsg; ?></p>
            <p class="text-xs mt-1 <?php echo $direction === 'sent' ? 'text-blue-100' : 'text-gray-400'; ?> text-right">
                <?php echo $time; ?>
                <?php if ($direction === 'sent'): ?>
                    <span class="ml-1"><?php echo $isRead ? '✓✓' : '✓'; ?></span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php
}

exit;
