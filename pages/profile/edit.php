<?php
/**
 * RoomSaathi - Edit Profile Page
 */
$pageTitle = 'Edit Profile';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

global $conn;
$userId = $_SESSION['user_id'];

// Get current user data
$sql = "SELECT u.*, p.* FROM users u 
        LEFT JOIN user_preferences p ON u.id = p.user_id 
        WHERE u.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

$error = '';
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $gender = sanitize($_POST['gender'] ?? '');
    $occupation = sanitize($_POST['occupation'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    $userType = sanitize($_POST['user_type'] ?? 'seeking');

    // Preferences
    $smoking = sanitize($_POST['smoking'] ?? 'no');
    $drinking = sanitize($_POST['drinking'] ?? 'no');
    $foodPreference = sanitize($_POST['food_preference'] ?? 'any');
    $pets = sanitize($_POST['pets'] ?? 'no');
    $sleepSchedule = sanitize($_POST['sleep_schedule'] ?? 'flexible');
    $cleanliness = sanitize($_POST['cleanliness'] ?? 'moderate');
    $guests = sanitize($_POST['guests'] ?? 'sometimes');
    $personality = sanitize($_POST['personality'] ?? 'ambivert');

    // Validation
    if (empty($name) || empty($phone)) {
        $error = 'Name and phone are required';
    } else {
        // Handle photo upload
        $profilePhoto = $user['profile_photo'];
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = 'profile_' . $userId . '_' . time() . '.' . pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
                $profilePhoto = $fileName;
            }
        }

        // Update user
        $updateUserSql = "UPDATE users SET 
                          name = ?, phone = ?, age = ?, gender = ?, occupation = ?, 
                          city = ?, bio = ?, user_type = ?, profile_photo = ?
                          WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateUserSql);
        mysqli_stmt_bind_param($updateStmt, "ssissssssi", 
            $name, $phone, $age, $gender, $occupation, $city, $bio, $userType, $profilePhoto, $userId);
        
        $userUpdated = mysqli_stmt_execute($updateStmt);

        // Update or insert preferences
        $checkPrefSql = "SELECT id FROM user_preferences WHERE user_id = ?";
        $checkStmt = mysqli_prepare($conn, $checkPrefSql);
        mysqli_stmt_bind_param($checkStmt, "i", $userId);
        mysqli_stmt_execute($checkStmt);
        $prefExists = mysqli_stmt_get_result($checkStmt)->num_rows > 0;

        if ($prefExists) {
            $prefSql = "UPDATE user_preferences SET 
                        smoking = ?, drinking = ?, food_preference = ?, pets = ?,
                        sleep_schedule = ?, cleanliness = ?, guests = ?, personality = ?
                        WHERE user_id = ?";
        } else {
            $prefSql = "INSERT INTO user_preferences 
                        (smoking, drinking, food_preference, pets, sleep_schedule, cleanliness, guests, personality, user_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        
        $prefStmt = mysqli_prepare($conn, $prefSql);
        mysqli_stmt_bind_param($prefStmt, "ssssssssi", 
            $smoking, $drinking, $foodPreference, $pets, $sleepSchedule, $cleanliness, $guests, $personality, $userId);
        
        $prefUpdated = mysqli_stmt_execute($prefStmt);

        if ($userUpdated && $prefUpdated) {
            $_SESSION['user_name'] = $name;
            setFlash('success', 'Profile updated successfully!');
            header('Location: ' . SITE_URL . '/pages/profile/my-profile.php');
            exit;
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
                <p class="text-gray-500">Update your personal information</p>
            </div>
            <a href="<?php echo SITE_URL; ?>/pages/profile/my-profile.php" 
               class="text-gray-600 hover:text-primary">
                ← Back to Profile
            </a>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Profile Photo -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Photo</h2>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $user['profile_photo'] ?: 'default.jpg'; ?>" 
                             alt="Profile Photo" id="photoPreview"
                             class="w-24 h-24 rounded-full object-cover border-4 border-primary"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=050f91&color=fff&size=150'">
                        <label class="absolute bottom-0 right-0 bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                        </label>
                    </div>
                    <div>
                        <p class="text-gray-600">Click the camera icon to change your photo</p>
                        <p class="text-gray-400 text-sm">JPG, PNG, or GIF (max 5MB)</p>
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone *</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Age</label>
                        <input type="number" name="age" value="<?php echo $user['age']; ?>" min="18" max="99"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Occupation</label>
                        <input type="text" name="occupation" value="<?php echo htmlspecialchars($user['occupation'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="e.g., Software Engineer, Student">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">City</label>
                        <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Select City</option>
                            <?php 
                            $cities = ['Bengaluru', 'Mumbai', 'Delhi', 'Hyderabad', 'Chennai', 'Pune', 'Kolkata', 'Ahmedabad', 'Jaipur', 'Gurgaon', 'Noida'];
                            foreach ($cities as $city): 
                            ?>
                            <option value="<?php echo $city; ?>" <?php echo ($user['city'] ?? '') === $city ? 'selected' : ''; ?>>
                                <?php echo $city; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700 font-medium mb-2">Bio</label>
                    <textarea name="bio" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Tell potential roommates about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700 font-medium mb-2">I am</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="user_type" value="seeking" 
                                   <?php echo ($user['user_type'] ?? 'seeking') === 'seeking' ? 'checked' : ''; ?>
                                   class="w-5 h-5 text-primary">
                            <span class="ml-2">Looking for a room</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="user_type" value="offering" 
                                   <?php echo ($user['user_type'] ?? '') === 'offering' ? 'checked' : ''; ?>
                                   class="w-5 h-5 text-primary">
                            <span class="ml-2">Offering a room</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="user_type" value="both" 
                                   <?php echo ($user['user_type'] ?? '') === 'both' ? 'checked' : ''; ?>
                                   class="w-5 h-5 text-primary">
                            <span class="ml-2">Both</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Lifestyle Preferences -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Lifestyle Preferences</h2>
                <p class="text-gray-500 mb-4">These help us find compatible roommates for you</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🚬 Smoking</label>
                        <select name="smoking" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="no" <?php echo ($user['smoking'] ?? 'no') === 'no' ? 'selected' : ''; ?>>No</option>
                            <option value="yes" <?php echo ($user['smoking'] ?? '') === 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="occasionally" <?php echo ($user['smoking'] ?? '') === 'occasionally' ? 'selected' : ''; ?>>Occasionally</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🍺 Drinking</label>
                        <select name="drinking" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="no" <?php echo ($user['drinking'] ?? 'no') === 'no' ? 'selected' : ''; ?>>No</option>
                            <option value="yes" <?php echo ($user['drinking'] ?? '') === 'yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="socially" <?php echo ($user['drinking'] ?? '') === 'socially' ? 'selected' : ''; ?>>Socially</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🍽️ Food Preference</label>
                        <select name="food_preference" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="any" <?php echo ($user['food_preference'] ?? 'any') === 'any' ? 'selected' : ''; ?>>Any</option>
                            <option value="vegetarian" <?php echo ($user['food_preference'] ?? '') === 'vegetarian' ? 'selected' : ''; ?>>Vegetarian</option>
                            <option value="non-vegetarian" <?php echo ($user['food_preference'] ?? '') === 'non-vegetarian' ? 'selected' : ''; ?>>Non-Vegetarian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🐾 Pets</label>
                        <select name="pets" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="no" <?php echo ($user['pets'] ?? 'no') === 'no' ? 'selected' : ''; ?>>No pets</option>
                            <option value="have" <?php echo ($user['pets'] ?? '') === 'have' ? 'selected' : ''; ?>>I have pets</option>
                            <option value="love" <?php echo ($user['pets'] ?? '') === 'love' ? 'selected' : ''; ?>>Love pets</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🌙 Sleep Schedule</label>
                        <select name="sleep_schedule" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="flexible" <?php echo ($user['sleep_schedule'] ?? 'flexible') === 'flexible' ? 'selected' : ''; ?>>Flexible</option>
                            <option value="early_bird" <?php echo ($user['sleep_schedule'] ?? '') === 'early_bird' ? 'selected' : ''; ?>>Early Bird</option>
                            <option value="night_owl" <?php echo ($user['sleep_schedule'] ?? '') === 'night_owl' ? 'selected' : ''; ?>>Night Owl</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🧹 Cleanliness</label>
                        <select name="cleanliness" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="moderate" <?php echo ($user['cleanliness'] ?? 'moderate') === 'moderate' ? 'selected' : ''; ?>>Moderate</option>
                            <option value="very_clean" <?php echo ($user['cleanliness'] ?? '') === 'very_clean' ? 'selected' : ''; ?>>Very Clean</option>
                            <option value="casual" <?php echo ($user['cleanliness'] ?? '') === 'casual' ? 'selected' : ''; ?>>Casual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">👥 Guests</label>
                        <select name="guests" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="sometimes" <?php echo ($user['guests'] ?? 'sometimes') === 'sometimes' ? 'selected' : ''; ?>>Sometimes</option>
                            <option value="never" <?php echo ($user['guests'] ?? '') === 'never' ? 'selected' : ''; ?>>Never</option>
                            <option value="often" <?php echo ($user['guests'] ?? '') === 'often' ? 'selected' : ''; ?>>Often</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">🧠 Personality</label>
                        <select name="personality" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="ambivert" <?php echo ($user['personality'] ?? 'ambivert') === 'ambivert' ? 'selected' : ''; ?>>Ambivert</option>
                            <option value="introvert" <?php echo ($user['personality'] ?? '') === 'introvert' ? 'selected' : ''; ?>>Introvert</option>
                            <option value="extrovert" <?php echo ($user['personality'] ?? '') === 'extrovert' ? 'selected' : ''; ?>>Extrovert</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 bg-primary text-white py-4 rounded-xl font-semibold hover:bg-primary-dark transition-all">
                    Save Changes
                </button>
                <a href="<?php echo SITE_URL; ?>/pages/profile/my-profile.php" 
                   class="px-8 py-4 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>
