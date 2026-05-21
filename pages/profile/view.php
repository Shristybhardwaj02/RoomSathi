<?php
/**
 * RoomSaathi - View Other User's Profile
 */
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/auth/login.php');
    exit;
}

$userId = intval($_GET['id'] ?? 0);
$currentUserId = $_SESSION['user_id'];

if ($userId <= 0 || $userId === $currentUserId) {
    header('Location: ' . SITE_URL . '/pages/profile/my-profile.php');
    exit;
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$viewUser = $stmt->fetch();

if (!$viewUser) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit;
}

// Get user preferences
$stmt = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
$stmt->execute([$userId]);
$preferences = $stmt->fetch();

// Calculate compatibility
$currentPrefs = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
$currentPrefs->execute([$currentUserId]);
$myPrefs = $currentPrefs->fetch();

$compatibility = 0;
if ($myPrefs && $preferences) {
    $fields = ['smoking', 'drinking', 'food_preference', 'pets', 'sleep_schedule', 'cleanliness', 'guests', 'personality'];
    $matches = 0;
    foreach ($fields as $field) {
        if (isset($myPrefs[$field]) && isset($preferences[$field]) && $myPrefs[$field] === $preferences[$field]) {
            $matches++;
        }
    }
    $compatibility = round(($matches / count($fields)) * 100);
}

// Get user's active listings
$stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$userId]);
$listings = $stmt->fetchAll();

$pageTitle = $viewUser['full_name'] . "'s Profile";
require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Back Button -->
        <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-primary mb-6 group">
            <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>

        <!-- Profile Header -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-br from-primary via-blue-700 to-indigo-800 h-36 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400/10 rounded-full blur-2xl"></div>
            </div>
            <div class="px-8 pb-8">
                <div class="flex flex-col md:flex-row md:items-end -mt-16 mb-6">
                    <div class="w-32 h-32 rounded-2xl border-4 border-white bg-white shadow-lg overflow-hidden">
                        <?php if (!empty($viewUser['profile_photo'])): ?>
                        <img src="<?php echo SITE_URL . '/uploads/' . $viewUser['profile_photo']; ?>" 
                             alt="Profile" class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="md:ml-6 mt-4 md:mt-0 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">
                                    <?php echo htmlspecialchars($viewUser['full_name']); ?>
                                    <?php if ($viewUser['is_verified']): ?>
                                    <span class="inline-flex items-center ml-2 text-blue-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    <?php endif; ?>
                                </h1>
                                <p class="text-gray-500">
                                    <?php echo ucfirst($viewUser['user_type'] ?? 'User'); ?> 
                                    <?php if ($viewUser['city']): ?>• <?php echo htmlspecialchars($viewUser['city']); ?><?php endif; ?>
                                </p>
                            </div>
                            <!-- Compatibility Badge -->
                            <div class="text-center bg-primary-light rounded-xl px-4 py-2">
                                <p class="text-3xl font-bold text-primary"><?php echo $compatibility; ?>%</p>
                                <p class="text-xs text-gray-500">Match</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <?php if ($viewUser['age']): ?>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-gray-800"><?php echo $viewUser['age']; ?></p>
                        <p class="text-gray-500 text-sm">Age</p>
                    </div>
                    <?php endif; ?>
                    <?php if ($viewUser['gender']): ?>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-gray-800"><?php echo ucfirst($viewUser['gender']); ?></p>
                        <p class="text-gray-500 text-sm">Gender</p>
                    </div>
                    <?php endif; ?>
                    <?php if ($viewUser['occupation']): ?>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($viewUser['occupation']); ?></p>
                        <p class="text-gray-500 text-sm">Occupation</p>
                    </div>
                    <?php endif; ?>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-gray-800">
                            <?php echo date('M Y', strtotime($viewUser['created_at'])); ?>
                        </p>
                        <p class="text-gray-500 text-sm">Member Since</p>
                    </div>
                </div>

                <!-- Bio -->
                <?php if (!empty($viewUser['bio'])): ?>
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-2">About</h3>
                    <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($viewUser['bio'])); ?></p>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <a href="<?php echo SITE_URL; ?>/pages/chat/conversation.php?user_id=<?php echo $userId; ?>" 
                       class="flex-1 bg-primary text-white py-3 rounded-xl font-semibold text-center hover:bg-primary-dark transition-all">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Send Message
                    </a>
                </div>
            </div>
        </div>

        <!-- Lifestyle Preferences -->
        <?php if ($preferences): ?>
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Lifestyle Preferences</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                $prefLabelsIcons = [
                    'smoking' => ['label' => 'Smoking', 'color' => 'orange', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>'],
                    'drinking' => ['label' => 'Drinking', 'color' => 'amber', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>'],
                    'food_preference' => ['label' => 'Food', 'color' => 'green', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3zm3 6h12M6 15h12"></path></svg>'],
                    'pets' => ['label' => 'Pets', 'color' => 'pink', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>'],
                    'sleep_schedule' => ['label' => 'Sleep', 'color' => 'indigo', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>'],
                    'cleanliness' => ['label' => 'Cleanliness', 'color' => 'cyan', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>'],
                    'guests' => ['label' => 'Guests', 'color' => 'purple', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'],
                    'personality' => ['label' => 'Personality', 'color' => 'blue', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'],
                ];
                foreach ($prefLabelsIcons as $key => $data):
                    if (!empty($preferences[$key])):
                        $matches = $myPrefs && isset($myPrefs[$key]) && $myPrefs[$key] === $preferences[$key];
                ?>
                <div class="p-4 rounded-xl <?php echo $matches ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-100'; ?> hover:shadow-md transition-all">
                    <div class="flex items-center mb-2">
                        <span class="w-8 h-8 rounded-lg bg-<?php echo $data['color']; ?>-100 flex items-center justify-center text-<?php echo $data['color']; ?>-600 mr-2"><?php echo $data['icon']; ?></span>
                        <span class="font-medium text-gray-700"><?php echo $data['label']; ?></span>
                        <?php if ($matches): ?>
                        <svg class="w-4 h-4 ml-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600 text-sm capitalize"><?php echo str_replace('_', ' ', $preferences[$key]); ?></p>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- User's Listings -->
        <?php if (!empty($listings)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">
                    <?php echo htmlspecialchars($viewUser['full_name']); ?>'s Listings
                </h2>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <?php foreach ($listings as $listing): ?>
                <a href="<?php echo SITE_URL; ?>/pages/listings/details.php?id=<?php echo $listing['id']; ?>" 
                   class="block bg-gray-50 rounded-xl overflow-hidden hover:shadow-lg transition-all border border-gray-100 group">
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <?php 
                        $photos = json_decode($listing['photos'], true);
                        if (!empty($photos)): 
                        ?>
                        <img src="<?php echo SITE_URL . '/uploads/' . $photos[0]; ?>" 
                             alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 truncate"><?php echo htmlspecialchars($listing['title']); ?></h3>
                        <p class="text-primary font-bold">₹<?php echo number_format($listing['rent']); ?>/mo</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
