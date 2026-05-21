<?php
/**
 * RoomSaathi - Chat Conversation Page
 */
$pageTitle = 'Chat';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];
$otherUserId = isset($_GET['user']) ? (int)$_GET['user'] : 0;
$listingId = isset($_GET['listing']) ? (int)$_GET['listing'] : null;

if (!$otherUserId || $otherUserId == $userId) {
    header('Location: ' . SITE_URL . '/pages/chat/');
    exit;
}

// Get other user details
$userSql = "SELECT id, name, profile_photo, occupation, city FROM users WHERE id = ?";
$userStmt = mysqli_prepare($conn, $userSql);
mysqli_stmt_bind_param($userStmt, "i", $otherUserId);
mysqli_stmt_execute($userStmt);
$otherUser = mysqli_stmt_get_result($userStmt)->fetch_assoc();

if (!$otherUser) {
    header('Location: ' . SITE_URL . '/pages/chat/');
    exit;
}

// Fetch listing info for header context (if any)
$listing = null;
if ($listingId) {
    $listingSql = "SELECT title, city, rent FROM listings WHERE id = ?";
    $listingStmt = mysqli_prepare($conn, $listingSql);
    mysqli_stmt_bind_param($listingStmt, "i", $listingId);
    mysqli_stmt_execute($listingStmt);
    $listing = mysqli_stmt_get_result($listingStmt)->fetch_assoc();
}

$pageTitle = 'Chat with ' . $otherUser['name'];
require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-100 flex flex-col" style="padding-top: 0;">
    <!-- Chat Header -->
    <div class="bg-white shadow-md px-4 py-3 flex items-center gap-4 sticky top-16 z-40">
        <a href="<?php echo SITE_URL; ?>/pages/chat/" class="text-gray-600 hover:text-primary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        
        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $otherUser['profile_photo'] ?: 'default.jpg'; ?>" 
             alt="<?php echo htmlspecialchars($otherUser['name']); ?>"
             class="w-12 h-12 rounded-full object-cover border-2 border-primary"
             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($otherUser['name']); ?>&background=050f91&color=fff'">
        
        <div class="flex-1">
            <h2 class="font-semibold text-gray-800"><?php echo htmlspecialchars($otherUser['name']); ?></h2>
            <p class="text-sm text-gray-500">
                <?php echo $otherUser['occupation'] ? htmlspecialchars($otherUser['occupation']) . ' • ' : ''; ?>
                <?php echo htmlspecialchars($otherUser['city'] ?? 'RoomSaathi Member'); ?>
            </p>
        </div>

        <a href="<?php echo SITE_URL; ?>/pages/profile/view.php?id=<?php echo $otherUserId; ?>" 
           class="text-primary hover:text-primary-dark">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </a>
    </div>

    <?php if ($listing): ?>
    <!-- Listing Context -->
    <div class="bg-primary-light px-4 py-2 flex items-center gap-2">
        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span class="text-sm text-primary">
            Discussing: <strong><?php echo htmlspecialchars($listing['title']); ?></strong> 
            (₹<?php echo number_format($listing['rent']); ?>/month)
        </span>
        <a href="<?php echo SITE_URL; ?>/pages/listings/details.php?id=<?php echo $listingId; ?>" 
           class="ml-auto text-xs text-primary hover:underline">View Listing</a>
    </div>
    <?php endif; ?>

    <!-- Messages Container (loaded via AJAX) -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4" id="messagesContainer" style="max-height: calc(100vh - 250px);">
        <div class="text-center py-12" id="messagesLoader">
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <p class="text-gray-500">Loading messages...</p>
        </div>
    </div>

    <!-- Quick Replies (for empty chat) -->
    <?php if (empty($messages)): ?>
    <div class="px-4 py-2 flex gap-2 overflow-x-auto bg-white border-t">
        <button onclick="sendQuickReply('Hi! I\'m interested in your listing.')" 
                class="whitespace-nowrap bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-200">
            👋 Hi! I'm interested
        </button>
        <button onclick="sendQuickReply('Is the room still available?')" 
                class="whitespace-nowrap bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-200">
            🏠 Is it available?
        </button>
        <button onclick="sendQuickReply('Can we schedule a visit?')" 
                class="whitespace-nowrap bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-200">
            📅 Schedule visit?
        </button>
    </div>
    <?php endif; ?>

    <!-- Message Input -->
    <div class="bg-white border-t px-4 py-3 sticky bottom-0">
        <form method="POST" class="flex items-center gap-3" id="messageForm">
            <input type="text" name="message" id="messageInput" 
                   class="flex-1 px-4 py-3 bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                   placeholder="Type a message..." autocomplete="off" required>
            <button type="submit" 
                    class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-primary-dark transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </form>
    </div>

    <script>
        const otherUserId = <?php echo (int)$otherUserId; ?>;
        const listingId = <?php echo $listingId ? (int)$listingId : 'null'; ?>;
        const messagesContainer = document.getElementById('messagesContainer');

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        async function fetchMessages() {
            try {
                const data = new URLSearchParams();
                data.append('other_user_id', otherUserId);
                if (listingId !== null) data.append('listing_id', listingId);

                const res = await fetch('get-messages.php', {
                    method: 'POST',
                    body: data
                });
                const html = await res.text();
                messagesContainer.innerHTML = html || '<div class="text-center py-12"><p class="text-gray-500">No messages yet. Start the conversation!</p></div>';
                scrollToBottom();
            } catch (e) {
                console.error('Failed to fetch messages', e);
            }
        }

        // Poll every 3 seconds
        fetchMessages();
        setInterval(fetchMessages, 3000);

        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const msg = input.value.trim();
            if (!msg) return;

            const data = new URLSearchParams();
            data.append('other_user_id', otherUserId);
            if (listingId !== null) data.append('listing_id', listingId);
            data.append('message', msg);

            try {
                const res = await fetch('send-message.php', { method: 'POST', body: data });
                const json = await res.json();
                if (json.success) {
                    input.value = '';
                    fetchMessages();
                }
            } catch (err) {
                console.error('Send failed', err);
            }
        });
    </script>
</div>

<script>
// Scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
});

// Quick reply function
function sendQuickReply(message) {
    document.getElementById('messageInput').value = message;
    document.getElementById('messageForm').submit();
}

// Auto-refresh messages every 5 seconds (simple polling)
setInterval(function() {
    // In production, you'd use AJAX here to fetch new messages
    // For now, we'll skip auto-refresh to keep it simple
}, 5000);
</script>

<?php require_once '../../includes/footer.php'; ?>
