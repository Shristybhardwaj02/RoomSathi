<?php
/**
 * RoomSaathi - My Profile
 */
$pageTitle = 'My Profile';
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

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8">
    <!-- Page Header with Gradient -->
    <div class="bg-gradient-to-br from-primary via-blue-800 to-indigo-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        
        <!-- Profile Header -->
        <div class="text-white pt-8 pb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Avatar with Ring -->
                <div class="relative group">
                    <div class="w-36 h-36 rounded-3xl bg-gradient-to-br from-white/20 to-white/5 p-1 shadow-2xl">
                        <div class="w-full h-full rounded-3xl overflow-hidden bg-white/10 flex items-center justify-center">
                            <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                                <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg class="w-16 h-16 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Status Badge -->
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg border-2 border-white">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight"><?php echo htmlspecialchars($user['name']); ?></h1>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-4">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-sm rounded-full text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <?php echo $user['occupation']; ?>
                        </span>
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-sm rounded-full text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <?php echo $user['city']; ?>
                        </span>
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-sm rounded-full text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <?php echo $user['age']; ?> years • <?php echo ucfirst($user['gender']); ?>
                        </span>
                    </div>
                    <?php if (!empty($user['bio'])): ?>
                    <p class="text-blue-100 max-w-xl"><?php echo htmlspecialchars(substr($user['bio'], 0, 150)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="max-w-4xl mx-auto px-4 -mt-2 relative z-20">

        <div class="grid md:grid-cols-2 gap-8">
            
            <!-- Left Column - Basic Info -->
            <div class="card-morph p-8" style="animation: slideInLeft 0.5s ease-out;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Basic Information</h2>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Profile Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                                    <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="profile_photo" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Age</label>
                            <input type="number" name="age" value="<?php echo $user['age']; ?>" 
                                   class="input-premium">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Gender</label>
                            <select name="gender" class="input-premium">
                                <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Occupation</label>
                        <input type="text" name="occupation" value="<?php echo htmlspecialchars($user['occupation']); ?>" 
                               class="input-premium">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">City</label>
                        <select name="city" class="input-premium">
                            <?php foreach ($cities as $city): ?>
                            <option value="<?php echo $city; ?>" <?php echo $user['city'] === $city ? 'selected' : ''; ?>><?php echo $city; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Looking To</label>
                        <select name="user_type" class="input-premium">
                            <option value="seeking" <?php echo $user['user_type'] === 'seeking' ? 'selected' : ''; ?>>Find a Room</option>
                            <option value="offering" <?php echo $user['user_type'] === 'offering' ? 'selected' : ''; ?>>Offer a Room</option>
                            <option value="both" <?php echo $user['user_type'] === 'both' ? 'selected' : ''; ?>>Both</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">About Me</label>
                        <textarea name="bio" rows="3" class="input-premium" style="resize: none;"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full py-4 rounded-xl font-semibold transition-all flex items-center justify-center gap-2 mt-6" style="background: linear-gradient(135deg, #050f91 0%, #3b5bdb 100%); color: white; box-shadow: 0 4px 15px rgba(5, 15, 145, 0.3);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Right Column - Lifestyle Preferences -->
            <div class="card-morph p-8" style="animation: slideInRight 0.5s ease-out;">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Lifestyle Preferences</h2>
                    </div>
                    <a href="setup-step2.php" class="px-4 py-2 bg-primary/10 text-primary rounded-xl text-sm font-semibold hover:bg-primary/20 transition-all">Edit</a>
                </div>
                
                <?php if ($prefs): ?>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Smoking</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['smoking'][$prefs['smoking']] ?? $prefs['smoking']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Drinking</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['drinking'][$prefs['drinking']] ?? $prefs['drinking']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3zm3 6h12M6 15h12"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Food</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['food_preference'][$prefs['food_preference']] ?? $prefs['food_preference']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Pets</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['pets'][$prefs['pets']] ?? $prefs['pets']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Sleep</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['sleep_schedule'][$prefs['sleep_schedule']] ?? $prefs['sleep_schedule']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Cleanliness</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['cleanliness'][$prefs['cleanliness']] ?? $prefs['cleanliness']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Guests</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['guests'][$prefs['guests']] ?? $prefs['guests']; ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:shadow-md transition-all">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <span class="font-medium text-gray-700">Personality</span>
                        </span>
                        <span class="tag tag-primary">
                            <?php echo $prefLabels['personality'][$prefs['personality']] ?? $prefs['personality']; ?>
                        </span>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-state py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">No Preferences Set</h3>
                    <p class="text-gray-500 mb-4">Set your preferences to find better matches</p>
                    <a href="setup-step2.php" class="btn-gradient text-white px-6 py-2.5 rounded-xl inline-block font-semibold">Set Preferences</a>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Account Info -->
        <div class="card-morph p-8 mt-8" style="animation: fadeIn 0.6s ease-out 0.2s both;">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Account Information</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-semibold text-gray-800"><?php echo $user['email']; ?></p>
                </div>
                <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="font-semibold text-gray-800"><?php echo $user['phone']; ?></p>
                </div>
                <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Member Since</p>
                    <p class="font-semibold text-gray-800"><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
