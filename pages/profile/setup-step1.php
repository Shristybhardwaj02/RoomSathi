<?php
/**
 * RoomSaathi - Profile Setup Step 1
 * Basic Information
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'age' => (int)$_POST['age'],
        'gender' => sanitize($_POST['gender']),
        'occupation' => sanitize($_POST['occupation']),
        'city' => sanitize($_POST['city']),
        'user_type' => sanitize($_POST['user_type']),
        'bio' => sanitize($_POST['bio'])
    ];
    
    // Validation
    if ($data['age'] < 18 || $data['age'] > 60) {
        $error = 'Age must be between 18 and 60';
    } elseif (empty($data['gender']) || empty($data['city']) || empty($data['occupation'])) {
        $error = 'Please fill all required fields';
    } else {
        // Handle profile photo upload
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['size'] > 0) {
            $uploadResult = uploadProfilePhoto($userId, $_FILES['profile_photo']);
            if (!$uploadResult['success']) {
                $error = $uploadResult['error'];
            }
        }
        
        if (empty($error) && updateProfileStep1($userId, $data)) {
            redirect('/pages/profile/setup-step2.php');
        } else {
            $error = $error ?: 'Failed to update profile';
        }
    }
}

$cities = ['Bangalore', 'Mumbai', 'Delhi NCR', 'Pune', 'Hyderabad', 'Chennai', 'Kolkata', 'Ahmedabad', 'Noida', 'Gurgaon'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setup - Step 1 - RoomSaathi</title>
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
            <div class="text-gray-600">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </div>
        </div>
    </nav>

    <!-- Progress Bar -->
    <div class="pt-20 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center">
                    <div class="bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="w-20 h-1 bg-gray-300"></div>
                    <div class="bg-gray-300 text-gray-600 w-10 h-10 rounded-full flex items-center justify-center font-bold">2</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="pb-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Basic Information</h1>
                    <p class="text-gray-600">Tell us about yourself</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Profile Photo -->
                    <div class="text-center">
                        <label class="block text-gray-700 font-medium mb-4">Profile Photo</label>
                        <div class="flex justify-center">
                            <div class="relative">
                                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                    <?php if ($user['profile_photo'] && $user['profile_photo'] !== 'default.jpg'): ?>
                                        <img src="<?php echo SITE_URL; ?>/uploads/profiles/<?php echo $user['profile_photo']; ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-5xl">👤</span>
                                    <?php endif; ?>
                                </div>
                                <label class="absolute bottom-0 right-0 bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-dark">
                                    <span>📷</span>
                                    <input type="file" name="profile_photo" accept="image/*" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Age -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Age *</label>
                            <input type="number" name="age" required min="18" max="60"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   value="<?php echo $user['age'] ?: ''; ?>"
                                   placeholder="Your age">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Gender *</label>
                            <select name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Occupation *</label>
                            <input type="text" name="occupation" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   value="<?php echo $user['occupation'] ?: ''; ?>"
                                   placeholder="e.g., Student, Software Engineer">
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">City *</label>
                            <select name="city" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $city; ?>" <?php echo $user['city'] === $city ? 'selected' : ''; ?>>
                                    <?php echo $city; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- User Type -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">I am looking to *</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="border-2 rounded-lg p-4 text-center cursor-pointer hover:border-primary <?php echo $user['user_type'] === 'seeking' ? 'border-primary bg-primary-light' : 'border-gray-300'; ?>">
                                <input type="radio" name="user_type" value="seeking" class="hidden" <?php echo $user['user_type'] === 'seeking' ? 'checked' : ''; ?>>
                                <span class="text-2xl block mb-2">🔍</span>
                                <span class="font-medium">Find a Room</span>
                            </label>
                            <label class="border-2 rounded-lg p-4 text-center cursor-pointer hover:border-primary <?php echo $user['user_type'] === 'offering' ? 'border-primary bg-primary-light' : 'border-gray-300'; ?>">
                                <input type="radio" name="user_type" value="offering" class="hidden" <?php echo $user['user_type'] === 'offering' ? 'checked' : ''; ?>>
                                <span class="text-2xl block mb-2">🏠</span>
                                <span class="font-medium">Offer a Room</span>
                            </label>
                            <label class="border-2 rounded-lg p-4 text-center cursor-pointer hover:border-primary <?php echo $user['user_type'] === 'both' ? 'border-primary bg-primary-light' : 'border-gray-300'; ?>">
                                <input type="radio" name="user_type" value="both" class="hidden" <?php echo $user['user_type'] === 'both' ? 'checked' : ''; ?>>
                                <span class="text-2xl block mb-2">🔄</span>
                                <span class="font-medium">Both</span>
                            </label>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">About Me</label>
                        <textarea name="bio" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                  placeholder="Tell potential roommates about yourself..."><?php echo $user['bio'] ?: ''; ?></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition">
                        Continue to Step 2 →
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Radio button styling
        document.querySelectorAll('input[name="user_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="user_type"]').forEach(r => {
                    r.closest('label').classList.remove('border-primary', 'bg-primary-light');
                    r.closest('label').classList.add('border-gray-300');
                });
                this.closest('label').classList.remove('border-gray-300');
                this.closest('label').classList.add('border-primary', 'bg-primary-light');
            });
        });
    </script>

</body>
</html>
