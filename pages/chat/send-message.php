 <?php
/**
 * Endpoint: send-message.php
 * Accepts POST: other_user_id, listing_id (optional), message
 * Returns JSON { success: true|false }
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];
$otherUserId = isset($_POST['other_user_id']) ? (int)$_POST['other_user_id'] : 0;
$listingId = isset($_POST['listing_id']) && $_POST['listing_id'] !== '' ? (int)$_POST['listing_id'] : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

$response = ['success' => false];

if (!$otherUserId || $otherUserId == $userId || $message === '') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$insertSql = "INSERT INTO messages (sender_id, receiver_id, listing_id, message) VALUES (?, ?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertSql);
mysqli_stmt_bind_param($insertStmt, "iiis", $userId, $otherUserId, $listingId, $message);
$ok = mysqli_stmt_execute($insertStmt);

$response['success'] = (bool)$ok;

header('Content-Type: application/json');
echo json_encode($response);
exit;
