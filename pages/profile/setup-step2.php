<?php
/**
 * RoomSaathi - Profile Setup Step 2
 * Lifestyle Preferences (8 Factors)
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$prefs = getUserPreferences($userId);
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'smoking' => sanitize($_POST['smoking']),
        'drinking' => sanitize($_POST['drinking']),
        'food_preference' => sanitize($_POST['food_preference']),
        'pets' => sanitize($_POST['pets']),
        'sleep_schedule' => sanitize($_POST['sleep_schedule']),
        'cleanliness' => sanitize($_POST['cleanliness']),
        'guests' => sanitize($_POST['guests']),
        'personality' => sanitize($_POST['personality'])
    ];
    
    // Validate all fields are filled
    $allFilled = true;
    foreach ($data as $value) {
        if (empty($value)) {
            $allFilled = false;
            break;
        }
    }
    
    if (!$allFilled) {
        $error = 'Please select all lifestyle preferences';
    } else {
        if (updateProfileStep2($userId, $data)) {
            setFlash('success', 'Profile setup complete! Welcome to RoomSaathi!');
            redirect('/pages/dashboard/index.php');
        } else {
            $error = 'Failed to save preferences';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setup - Step 2 - RoomSaathi</title>
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
                    <div class="bg-green-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">✓</div>
                    <div class="w-20 h-1 bg-primary"></div>
                    <div class="bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">2</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="pb-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Lifestyle Preferences</h1>
                    <p class="text-gray-600">Help us find your compatible roommate</p>
                    <p class="text-sm text-primary mt-2">Each matching factor = 12.5% compatibility</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <!-- Smoking -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🚬</span> Smoking Habit
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['smoking'] ?? '') === 'no' ? 'selected' : ''; ?>">
                                <input type="radio" name="smoking" value="no" class="hidden" <?php echo ($prefs['smoking'] ?? '') === 'no' ? 'checked' : ''; ?>>
                                <span>No</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['smoking'] ?? '') === 'occasionally' ? 'selected' : ''; ?>">
                                <input type="radio" name="smoking" value="occasionally" class="hidden" <?php echo ($prefs['smoking'] ?? '') === 'occasionally' ? 'checked' : ''; ?>>
                                <span>Occasionally</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['smoking'] ?? '') === 'yes' ? 'selected' : ''; ?>">
                                <input type="radio" name="smoking" value="yes" class="hidden" <?php echo ($prefs['smoking'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                                <span>Yes</span>
                            </label>
                        </div>
                    </div>

                    <!-- Drinking -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🍺</span> Drinking Habit
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['drinking'] ?? '') === 'no' ? 'selected' : ''; ?>">
                                <input type="radio" name="drinking" value="no" class="hidden" <?php echo ($prefs['drinking'] ?? '') === 'no' ? 'checked' : ''; ?>>
                                <span>No</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['drinking'] ?? '') === 'socially' ? 'selected' : ''; ?>">
                                <input type="radio" name="drinking" value="socially" class="hidden" <?php echo ($prefs['drinking'] ?? '') === 'socially' ? 'checked' : ''; ?>>
                                <span>Socially</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['drinking'] ?? '') === 'yes' ? 'selected' : ''; ?>">
                                <input type="radio" name="drinking" value="yes" class="hidden" <?php echo ($prefs['drinking'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                                <span>Yes</span>
                            </label>
                        </div>
                    </div>

                    <!-- Food Preference -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🍽️</span> Food Preference
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['food_preference'] ?? '') === 'vegetarian' ? 'selected' : ''; ?>">
                                <input type="radio" name="food_preference" value="vegetarian" class="hidden" <?php echo ($prefs['food_preference'] ?? '') === 'vegetarian' ? 'checked' : ''; ?>>
                                <span>Vegetarian</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['food_preference'] ?? '') === 'non-vegetarian' ? 'selected' : ''; ?>">
                                <input type="radio" name="food_preference" value="non-vegetarian" class="hidden" <?php echo ($prefs['food_preference'] ?? '') === 'non-vegetarian' ? 'checked' : ''; ?>>
                                <span>Non-Veg</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['food_preference'] ?? '') === 'any' ? 'selected' : ''; ?>">
                                <input type="radio" name="food_preference" value="any" class="hidden" <?php echo ($prefs['food_preference'] ?? '') === 'any' ? 'checked' : ''; ?>>
                                <span>Any</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pets -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🐾</span> Pet Preference
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['pets'] ?? '') === 'have' ? 'selected' : ''; ?>">
                                <input type="radio" name="pets" value="have" class="hidden" <?php echo ($prefs['pets'] ?? '') === 'have' ? 'checked' : ''; ?>>
                                <span>Have Pets</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['pets'] ?? '') === 'love' ? 'selected' : ''; ?>">
                                <input type="radio" name="pets" value="love" class="hidden" <?php echo ($prefs['pets'] ?? '') === 'love' ? 'checked' : ''; ?>>
                                <span>Love Pets</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['pets'] ?? '') === 'no' ? 'selected' : ''; ?>">
                                <input type="radio" name="pets" value="no" class="hidden" <?php echo ($prefs['pets'] ?? '') === 'no' ? 'checked' : ''; ?>>
                                <span>No Pets</span>
                            </label>
                        </div>
                    </div>

                    <!-- Sleep Schedule -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🌙</span> Sleep Schedule
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['sleep_schedule'] ?? '') === 'early_bird' ? 'selected' : ''; ?>">
                                <input type="radio" name="sleep_schedule" value="early_bird" class="hidden" <?php echo ($prefs['sleep_schedule'] ?? '') === 'early_bird' ? 'checked' : ''; ?>>
                                <span>Early Bird</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['sleep_schedule'] ?? '') === 'night_owl' ? 'selected' : ''; ?>">
                                <input type="radio" name="sleep_schedule" value="night_owl" class="hidden" <?php echo ($prefs['sleep_schedule'] ?? '') === 'night_owl' ? 'checked' : ''; ?>>
                                <span>Night Owl</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['sleep_schedule'] ?? '') === 'flexible' ? 'selected' : ''; ?>">
                                <input type="radio" name="sleep_schedule" value="flexible" class="hidden" <?php echo ($prefs['sleep_schedule'] ?? '') === 'flexible' ? 'checked' : ''; ?>>
                                <span>Flexible</span>
                            </label>
                        </div>
                    </div>

                    <!-- Cleanliness -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🧹</span> Cleanliness Level
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['cleanliness'] ?? '') === 'very_clean' ? 'selected' : ''; ?>">
                                <input type="radio" name="cleanliness" value="very_clean" class="hidden" <?php echo ($prefs['cleanliness'] ?? '') === 'very_clean' ? 'checked' : ''; ?>>
                                <span>Very Clean</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['cleanliness'] ?? '') === 'moderate' ? 'selected' : ''; ?>">
                                <input type="radio" name="cleanliness" value="moderate" class="hidden" <?php echo ($prefs['cleanliness'] ?? '') === 'moderate' ? 'checked' : ''; ?>>
                                <span>Moderate</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['cleanliness'] ?? '') === 'casual' ? 'selected' : ''; ?>">
                                <input type="radio" name="cleanliness" value="casual" class="hidden" <?php echo ($prefs['cleanliness'] ?? '') === 'casual' ? 'checked' : ''; ?>>
                                <span>Casual</span>
                            </label>
                        </div>
                    </div>

                    <!-- Guests -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">🎉</span> Guest Frequency
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['guests'] ?? '') === 'never' ? 'selected' : ''; ?>">
                                <input type="radio" name="guests" value="never" class="hidden" <?php echo ($prefs['guests'] ?? '') === 'never' ? 'checked' : ''; ?>>
                                <span>Never</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['guests'] ?? '') === 'sometimes' ? 'selected' : ''; ?>">
                                <input type="radio" name="guests" value="sometimes" class="hidden" <?php echo ($prefs['guests'] ?? '') === 'sometimes' ? 'checked' : ''; ?>>
                                <span>Sometimes</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['guests'] ?? '') === 'often' ? 'selected' : ''; ?>">
                                <input type="radio" name="guests" value="often" class="hidden" <?php echo ($prefs['guests'] ?? '') === 'often' ? 'checked' : ''; ?>>
                                <span>Often</span>
                            </label>
                        </div>
                    </div>

                    <!-- Personality -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="flex items-center gap-3 text-gray-700 font-medium mb-3">
                            <span class="text-2xl">👤</span> Personality Type
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="pref-option <?php echo ($prefs['personality'] ?? '') === 'introvert' ? 'selected' : ''; ?>">
                                <input type="radio" name="personality" value="introvert" class="hidden" <?php echo ($prefs['personality'] ?? '') === 'introvert' ? 'checked' : ''; ?>>
                                <span>Introvert</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['personality'] ?? '') === 'ambivert' ? 'selected' : ''; ?>">
                                <input type="radio" name="personality" value="ambivert" class="hidden" <?php echo ($prefs['personality'] ?? '') === 'ambivert' ? 'checked' : ''; ?>>
                                <span>Ambivert</span>
                            </label>
                            <label class="pref-option <?php echo ($prefs['personality'] ?? '') === 'extrovert' ? 'selected' : ''; ?>">
                                <input type="radio" name="personality" value="extrovert" class="hidden" <?php echo ($prefs['personality'] ?? '') === 'extrovert' ? 'checked' : ''; ?>>
                                <span>Extrovert</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="setup-step1.php" class="flex-1 border-2 border-gray-300 text-gray-700 py-4 rounded-lg font-bold text-lg hover:border-primary text-center transition">
                            ← Back
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition">
                            Complete Setup ✓
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .pref-option {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }
        .pref-option:hover {
            border-color: #050f91;
        }
        .pref-option.selected,
        .pref-option:has(input:checked) {
            border-color: #050f91;
            background: #E8EAFF;
        }
    </style>

    <script>
        document.querySelectorAll('.pref-option').forEach(option => {
            option.addEventListener('click', function() {
                const name = this.querySelector('input').name;
                document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                    input.closest('.pref-option').classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
    </script>

</body>
</html>
