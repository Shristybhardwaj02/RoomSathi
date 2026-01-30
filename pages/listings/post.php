<?php
/**
 * RoomSaathi - Post New Listing
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => sanitize($_POST['title']),
        'description' => sanitize($_POST['description']),
        'rent' => (float)$_POST['rent'],
        'deposit' => (float)$_POST['deposit'],
        'city' => sanitize($_POST['city']),
        'locality' => sanitize($_POST['locality']),
        'address' => sanitize($_POST['address']),
        'room_type' => sanitize($_POST['room_type']),
        'furnishing' => sanitize($_POST['furnishing']),
        'available_from' => sanitize($_POST['available_from']),
        'gender_preference' => sanitize($_POST['gender_preference']),
        'occupancy' => (int)$_POST['occupancy'],
        'amenities' => isset($_POST['amenities']) ? $_POST['amenities'] : []
    ];
    
    // Validation
    if (empty($data['title']) || empty($data['rent']) || empty($data['city'])) {
        $error = 'Please fill all required fields';
    } else {
        // Handle photo uploads
        $photos = [];
        if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $uploadDir = UPLOAD_PATH . 'listings/';
            
            for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
                if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = 'listing_' . $userId . '_' . time() . '_' . $i . '.' . 
                                pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
                    
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $uploadDir . $filename)) {
                        $photos[] = $filename;
                    }
                }
            }
        }
        
        $listingId = createListing($userId, $data, $photos);
        
        if ($listingId) {
            setFlash('success', 'Listing created successfully!');
            redirect('/pages/listings/my-listings.php');
        } else {
            $error = 'Failed to create listing';
        }
    }
}

$cities = ['Bangalore', 'Mumbai', 'Delhi NCR', 'Pune', 'Hyderabad', 'Chennai', 'Kolkata', 'Ahmedabad', 'Noida', 'Gurgaon'];
$amenitiesList = ['WiFi', 'AC', 'Parking', 'Washing Machine', 'TV', 'Kitchen', 'Gym', 'Power Backup', 'Security', 'Water Supply'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Listing - RoomSaathi</title>
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
                <a href="browse.php" class="text-gray-600 hover:text-primary">Browse</a>
                <a href="../matching/matches.php" class="text-gray-600 hover:text-primary">Matches</a>
                <a href="../profile/my-profile.php" class="text-gray-600 hover:text-primary">Profile</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Post Your Room</h1>
                    <p class="text-gray-600">List your room and find compatible roommates</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <!-- Photos -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Room Photos</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary transition cursor-pointer" onclick="document.getElementById('photos').click()">
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden">
                            <span class="text-4xl block mb-2">📷</span>
                            <p class="text-gray-600">Click to upload photos (max 5)</p>
                            <p class="text-sm text-gray-400 mt-2">JPG, PNG up to 5MB each</p>
                        </div>
                        <div id="preview" class="flex gap-4 mt-4 flex-wrap"></div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Listing Title *</label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="e.g., Spacious Room in Koramangala">
                    </div>

                    <!-- Rent & Deposit -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Monthly Rent (₹) *</label>
                            <input type="number" name="rent" required min="1000"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   placeholder="12000">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Security Deposit (₹)</label>
                            <input type="number" name="deposit" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   placeholder="24000">
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">City *</label>
                            <select name="city" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Locality *</label>
                            <input type="text" name="locality" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   placeholder="e.g., Koramangala, HSR Layout">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                  placeholder="Detailed address (visible only after match)"></textarea>
                    </div>

                    <!-- Room Details -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Room Type</label>
                            <select name="room_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="private">Private Room</option>
                                <option value="shared">Shared Room</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Furnishing</label>
                            <select name="furnishing" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="furnished">Fully Furnished</option>
                                <option value="semi-furnished">Semi Furnished</option>
                                <option value="unfurnished">Unfurnished</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Looking For</label>
                            <select name="gender_preference" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="any">Any Gender</option>
                                <option value="male">Male Only</option>
                                <option value="female">Female Only</option>
                            </select>
                        </div>
                    </div>

                    <!-- Available From & Occupancy -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Available From</label>
                            <input type="date" name="available_from"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Roommates Needed</label>
                            <select name="occupancy" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                            </select>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <?php foreach ($amenitiesList as $amenity): ?>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:border-primary">
                                <input type="checkbox" name="amenities[]" value="<?php echo $amenity; ?>" class="text-primary">
                                <span class="text-sm"><?php echo $amenity; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                  placeholder="Describe your room, neighborhood, house rules, etc."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition">
                        Post Listing
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Photo preview
        document.getElementById('photos').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            
            Array.from(e.target.files).slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'w-24 h-24 rounded-lg overflow-hidden';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

</body>
</html>
