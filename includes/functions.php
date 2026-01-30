<?php
/**
 * RoomSaathi - Helper Functions
 */

require_once 'config.php';

/**
 * Register a new user
 */
function registerUser($name, $email, $phone, $password) {
    global $conn;
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate OTP
    $otp = generateOTP();
    $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
    $sql = "INSERT INTO users (name, email, phone, password, otp_code, otp_expiry) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $hashedPassword, $otp, $otpExpiry);
    
    if (mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'user_id' => mysqli_insert_id($conn), 'otp' => $otp];
    }
    
    return ['success' => false, 'error' => mysqli_error($conn)];
}

/**
 * Login user
 */
function loginUser($email, $password) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 0) {
                return ['success' => false, 'error' => 'Please verify your account first'];
            }
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['profile_complete'] = $user['profile_complete'];
            
            return ['success' => true, 'user' => $user];
        }
    }
    
    return ['success' => false, 'error' => 'Invalid email or password'];
}

/**
 * Verify OTP
 */
function verifyOTP($userId, $otp) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE id = ? AND otp_code = ? AND otp_expiry > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $userId, $otp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_fetch_assoc($result)) {
        // Mark as verified
        $updateSql = "UPDATE users SET is_verified = 1, otp_code = NULL WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "i", $userId);
        mysqli_stmt_execute($updateStmt);
        
        return true;
    }
    
    return false;
}

/**
 * Generate 6-digit OTP
 */
function generateOTP() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Update user profile (Step 1)
 */
function updateProfileStep1($userId, $data) {
    global $conn;
    
    $sql = "UPDATE users SET age = ?, gender = ?, occupation = ?, city = ?, user_type = ?, bio = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssssi", 
        $data['age'], 
        $data['gender'], 
        $data['occupation'], 
        $data['city'], 
        $data['user_type'],
        $data['bio'],
        $userId
    );
    
    return mysqli_stmt_execute($stmt);
}

/**
 * Update user preferences (Step 2)
 */
function updateProfileStep2($userId, $data) {
    global $conn;
    
    // Check if preferences exist
    $checkSql = "SELECT id FROM user_preferences WHERE user_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "i", $userId);
    mysqli_stmt_execute($checkStmt);
    $exists = mysqli_stmt_get_result($checkStmt)->num_rows > 0;
    
    if ($exists) {
        $sql = "UPDATE user_preferences SET 
                smoking = ?, drinking = ?, food_preference = ?, pets = ?,
                sleep_schedule = ?, cleanliness = ?, guests = ?, personality = ?
                WHERE user_id = ?";
    } else {
        $sql = "INSERT INTO user_preferences 
                (smoking, drinking, food_preference, pets, sleep_schedule, cleanliness, guests, personality, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi",
        $data['smoking'],
        $data['drinking'],
        $data['food_preference'],
        $data['pets'],
        $data['sleep_schedule'],
        $data['cleanliness'],
        $data['guests'],
        $data['personality'],
        $userId
    );
    
    if (mysqli_stmt_execute($stmt)) {
        // Mark profile as complete
        $updateSql = "UPDATE users SET profile_complete = 1 WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "i", $userId);
        mysqli_stmt_execute($updateStmt);
        
        $_SESSION['profile_complete'] = 1;
        return true;
    }
    
    return false;
}

/**
 * Get user by ID
 */
function getUserById($userId) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/**
 * Get user preferences
 */
function getUserPreferences($userId) {
    global $conn;
    
    $sql = "SELECT * FROM user_preferences WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/**
 * Calculate compatibility percentage
 */
function calculateCompatibility($userId1, $userId2) {
    $prefs1 = getUserPreferences($userId1);
    $prefs2 = getUserPreferences($userId2);
    
    if (!$prefs1 || !$prefs2) return 0;
    
    $factors = ['smoking', 'drinking', 'food_preference', 'pets', 'sleep_schedule', 'cleanliness', 'guests', 'personality'];
    $matches = 0;
    
    foreach ($factors as $factor) {
        if ($prefs1[$factor] === $prefs2[$factor]) {
            $matches++;
        }
    }
    
    return round(($matches / 8) * 100);
}

/**
 * Create a new listing
 */
function createListing($userId, $data, $photos) {
    global $conn;
    
    $photosJson = json_encode($photos);
    $amenitiesJson = isset($data['amenities']) ? json_encode($data['amenities']) : '[]';
    
    $sql = "INSERT INTO listings (user_id, title, description, rent, deposit, city, locality, address, 
            room_type, furnishing, available_from, gender_preference, occupancy, amenities, photos)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issddsssssssis",
        $userId,
        $data['title'],
        $data['description'],
        $data['rent'],
        $data['deposit'],
        $data['city'],
        $data['locality'],
        $data['address'],
        $data['room_type'],
        $data['furnishing'],
        $data['available_from'],
        $data['gender_preference'],
        $data['occupancy'],
        $amenitiesJson,
        $photosJson
    );
    
    return mysqli_stmt_execute($stmt) ? mysqli_insert_id($conn) : false;
}

/**
 * Get all active listings
 */
function getListings($filters = []) {
    global $conn;
    
    $sql = "SELECT l.*, u.name as owner_name, u.profile_photo as owner_photo 
            FROM listings l 
            JOIN users u ON l.user_id = u.id 
            WHERE l.status = 'active'";
    
    if (!empty($filters['city'])) {
        $sql .= " AND l.city = '" . sanitize($filters['city']) . "'";
    }
    if (!empty($filters['min_rent'])) {
        $sql .= " AND l.rent >= " . (int)$filters['min_rent'];
    }
    if (!empty($filters['max_rent'])) {
        $sql .= " AND l.rent <= " . (int)$filters['max_rent'];
    }
    
    $sql .= " ORDER BY l.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Get user's listings
 */
function getUserListings($userId) {
    global $conn;
    
    $sql = "SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

/**
 * Like a listing
 */
function likeListing($userId, $listingId) {
    global $conn;
    
    // Get listing owner
    $sql = "SELECT user_id FROM listings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $listingId);
    mysqli_stmt_execute($stmt);
    $listing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    
    if (!$listing) return false;
    
    $ownerId = $listing['user_id'];
    
    // Insert like
    $insertSql = "INSERT IGNORE INTO likes (user_id, listing_id, listing_owner_id) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);
    mysqli_stmt_bind_param($insertStmt, "iii", $userId, $listingId, $ownerId);
    
    return mysqli_stmt_execute($insertStmt);
}

/**
 * Get matches (mutual likes)
 */
function getMatches($userId) {
    global $conn;
    
    $sql = "SELECT l.*, u.name, u.profile_photo, u.city as user_city,
            (SELECT COUNT(*) FROM likes WHERE listing_owner_id = ? AND user_id = l.user_id) as mutual
            FROM likes lk
            JOIN listings l ON lk.listing_id = l.id
            JOIN users u ON l.user_id = u.id
            WHERE lk.user_id = ? AND lk.status = 'accepted'
            ORDER BY lk.created_at DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $userId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

/**
 * Upload profile photo
 */
function uploadProfilePhoto($userId, $file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File too large (max 5MB)'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
    $destination = UPLOAD_PATH . 'profiles/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        global $conn;
        $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $filename, $userId);
        mysqli_stmt_execute($stmt);
        
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'error' => 'Upload failed'];
}

/**
 * Get dashboard stats
 */
function getDashboardStats($userId) {
    global $conn;
    
    // Total listings
    $listingsSql = "SELECT COUNT(*) as count FROM listings WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $listingsSql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $listings = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['count'];
    
    // Total matches
    $matchesSql = "SELECT COUNT(*) as count FROM likes WHERE user_id = ? AND status = 'accepted'";
    $stmt = mysqli_prepare($conn, $matchesSql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $matches = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['count'];
    
    // Received interests
    $interestsSql = "SELECT COUNT(*) as count FROM likes WHERE listing_owner_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $interestsSql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $interests = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['count'];
    
    return [
        'listings' => $listings,
        'matches' => $matches,
        'interests' => $interests
    ];
}
?>
