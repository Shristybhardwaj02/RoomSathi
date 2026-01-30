<?php
/**
 * RoomSaathi - My Profile
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
$prefs = getUserPreferences($userId);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'age' => (int)$_POST['age'],
        'gender' => sanitize($_POST['gender']),
        'occupation' => sanitize($_POST['occupation']),
        'city' => sanitize($_POST['city']),
        'user_type' => sanitize($_POST['user_type']),
        'bio' => sanitize($_POST['bio'])
    ];
    
    // Handle photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['size'] > 0) {
        uploadProfilePhoto($userId, $_FILES['profile_photo']);
    }
    
    if (updateProfileStep1($userId, $data)) {
        setFlash('success', 'Profile updated successfully!');
        redirect('/pages/profile/my-profile.php');
    }
}

$cities = ['Bangalore', 'Mumbai', 'Delhi NCR', 'Pune', 'Hyderabad', 'Chennai', 'Kolkata', 'Ahmedabad', 'Noida', 'Gurgaon'];

// Preference labels
$prefLabels = [
    'smoking' => ['no' => 'No Smoking', 'occasionally' => 'Occasionally', 'yes' => 'Smoker'],
    'drinking' => ['no' => 'No Drinking', 'socially' => 'Socially', 'yes' => 'Drinks'],
    'food_preference' => ['vegetarian' => 'Vegetarian', 'non-vegetarian' => 'Non-Veg', 'any' => 'Any Food'],
    'pets' => ['have' => 'Has Pets', 'love' => 'Loves Pets', 'no' => 'No Pets'],
    'sleep_schedule' => ['early_bird' => 'Early Bird', 'night_owl' => 'Night Owl', 'flexible' => 'Flexible'],
    'cleanliness' => ['very_clean' => 'Very Clean', 'moderate' => 'Moderate', 'casual' => 'Casual'],
    'guests' => ['never' => 'No Guests', 'sometimes' => 'Sometimes', 'often' => 'Often'],
    'personality' => ['introvert' => 'Introvert', 'ambivert' => 'Ambivert', 'extrovert' => 'Extrovert']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - RoomSaathi</title>
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
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="flex items-center">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="flex gap-6 items-center">
                <a href="../dashboard/index.php" class="text-gray-600 hover:text-primary">Dashboard</a>
                <a href="../listings/browse.php" class="text-gray-600 hover:text-primary">Browse</a>
                <a href="../matching/matches.php" class="text-gray-600 hover:text-primary">Matches</a>
                <a href="my-profile.php" class="text-primary font-medium">Profile</a>
                <a href="../auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 text-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-primary to-blue-800 text-white rounded-2xl p-8 mb-8">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <div class="w-28 h-28 bg-white/20 rounded-full flex items-center justify-center overflow-hidden">
                            <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                                <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-5xl">👤</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1"><?php echo htmlspecialchars($user['name']); ?></h1>
                        <p class="text-blue-200"><?php echo $user['occupation']; ?></p>
                        <p class="text-blue-200">📍 <?php echo $user['city']; ?> • <?php echo $user['age']; ?> years • <?php echo ucfirst($user['gender']); ?></p>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- Left Column - Basic Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Basic Information</h2>
                    
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Profile Photo</label>
                            <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Age</label>
                                <input type="number" name="age" value="<?php echo $user['age']; ?>" 
                                       class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Gender</label>
                                <select name="gender" class="w-full px-3 py-2 border rounded-lg">
                                    <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Occupation</label>
                            <input type="text" name="occupation" value="<?php echo htmlspecialchars($user['occupation']); ?>" 
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">City</label>
                            <select name="city" class="w-full px-3 py-2 border rounded-lg">
                                <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $city; ?>" <?php echo $user['city'] === $city ? 'selected' : ''; ?>><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Looking To</label>
                            <select name="user_type" class="w-full px-3 py-2 border rounded-lg">
                                <option value="seeking" <?php echo $user['user_type'] === 'seeking' ? 'selected' : ''; ?>>Find a Room</option>
                                <option value="offering" <?php echo $user['user_type'] === 'offering' ? 'selected' : ''; ?>>Offer a Room</option>
                                <option value="both" <?php echo $user['user_type'] === 'both' ? 'selected' : ''; ?>>Both</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">About Me</label>
                            <textarea name="bio" rows="3" class="w-full px-3 py-2 border rounded-lg"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary-dark">
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- Right Column - Lifestyle Preferences -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Lifestyle Preferences</h2>
                        <a href="setup-step2.php" class="text-primary text-sm hover:underline">Edit</a>
                    </div>
                    
                    <?php if ($prefs): ?>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🚬</span> Smoking</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['smoking'][$prefs['smoking']] ?? $prefs['smoking']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🍺</span> Drinking</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['drinking'][$prefs['drinking']] ?? $prefs['drinking']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🍽️</span> Food</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['food_preference'][$prefs['food_preference']] ?? $prefs['food_preference']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🐾</span> Pets</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['pets'][$prefs['pets']] ?? $prefs['pets']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🌙</span> Sleep</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['sleep_schedule'][$prefs['sleep_schedule']] ?? $prefs['sleep_schedule']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🧹</span> Cleanliness</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['cleanliness'][$prefs['cleanliness']] ?? $prefs['cleanliness']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>🎉</span> Guests</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['guests'][$prefs['guests']] ?? $prefs['guests']; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="flex items-center gap-2"><span>👤</span> Personality</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo $prefLabels['personality'][$prefs['personality']] ?? $prefs['personality']; ?>
                            </span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <p>No preferences set yet.</p>
                        <a href="setup-step2.php" class="text-primary hover:underline">Set preferences</a>
                    </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Account Information</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium"><?php echo $user['email']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium"><?php echo $user['phone']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="font-medium"><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
