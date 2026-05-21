<?php
/**
 * RoomSaathi - Edit Listing Page
 */
$pageTitle = 'Edit Listing';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$listingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$listingId) {
    header('Location: ' . SITE_URL . '/pages/listings/my-listings.php');
    exit;
}

// Fetch listing
global $conn;
$sql = "SELECT * FROM listings WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $listingId, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    setFlash('error', 'Listing not found or you do not have permission to edit it.');
    header('Location: ' . SITE_URL . '/pages/listings/my-listings.php');
    exit;
}

$error = '';
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $rent = (float)($_POST['rent'] ?? 0);
    $deposit = (float)($_POST['deposit'] ?? 0);
    $city = sanitize($_POST['city'] ?? '');
    $locality = sanitize($_POST['locality'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $roomType = sanitize($_POST['room_type'] ?? 'private');
    $furnishing = sanitize($_POST['furnishing'] ?? 'semi-furnished');
    $availableFrom = $_POST['available_from'] ?? null;
    $genderPref = sanitize($_POST['gender_preference'] ?? 'any');
    $occupancy = (int)($_POST['occupancy'] ?? 1);
    $amenities = isset($_POST['amenities']) ? implode(',', $_POST['amenities']) : '';
    $status = sanitize($_POST['status'] ?? 'active');

    // Validation
    if (empty($title) || empty($rent) || empty($city)) {
        $error = 'Please fill in all required fields';
    } else {
        // Handle photo upload
        $photos = $listing['photos']; // Keep existing photos
        
        if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $uploadedPhotos = [];
            $uploadDir = '../../uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . $key . '_' . basename($_FILES['photos']['name'][$key]);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $uploadedPhotos[] = $fileName;
                    }
                }
            }
            
            if (!empty($uploadedPhotos)) {
                $photos = implode(',', $uploadedPhotos);
            }
        }

        // Update listing
        $updateSql = "UPDATE listings SET 
                      title = ?, description = ?, rent = ?, deposit = ?, city = ?, locality = ?, 
                      address = ?, room_type = ?, furnishing = ?, available_from = ?, 
                      gender_preference = ?, occupancy = ?, amenities = ?, photos = ?, status = ?
                      WHERE id = ? AND user_id = ?";
        
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ssddssssssssissii",
            $title, $description, $rent, $deposit, $city, $locality,
            $address, $roomType, $furnishing, $availableFrom,
            $genderPref, $occupancy, $amenities, $photos, $status,
            $listingId, $_SESSION['user_id']
        );

        if (mysqli_stmt_execute($updateStmt)) {
            setFlash('success', 'Listing updated successfully!');
            header('Location: ' . SITE_URL . '/pages/listings/details.php?id=' . $listingId);
            exit;
        } else {
            $error = 'Failed to update listing. Please try again.';
        }
    }
}

// Parse existing amenities
$existingAmenities = !empty($listing['amenities']) ? explode(',', $listing['amenities']) : [];

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Listing</h1>
                <p class="text-gray-500">Update your room/flat listing details</p>
            </div>
            <a href="<?php echo SITE_URL; ?>/pages/listings/my-listings.php" 
               class="text-gray-600 hover:text-primary flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">1</span>
                    Basic Information
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Listing Title *</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="e.g., Spacious 2BHK in Koramangala">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Describe your place, neighborhood, rules, etc."><?php echo htmlspecialchars($listing['description']); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Monthly Rent (₹) *</label>
                            <input type="number" name="rent" value="<?php echo $listing['rent']; ?>" required min="1000"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Security Deposit (₹)</label>
                            <input type="number" name="deposit" value="<?php echo $listing['deposit']; ?>" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">2</span>
                    Location Details
                </h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">City *</label>
                            <select name="city" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select City</option>
                                <?php 
                                $cities = ['Bengaluru', 'Mumbai', 'Delhi', 'Hyderabad', 'Chennai', 'Pune', 'Kolkata', 'Ahmedabad', 'Jaipur', 'Gurgaon', 'Noida'];
                                foreach ($cities as $city): 
                                ?>
                                <option value="<?php echo $city; ?>" <?php echo $listing['city'] === $city ? 'selected' : ''; ?>>
                                    <?php echo $city; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Locality/Area</label>
                            <input type="text" name="locality" value="<?php echo htmlspecialchars($listing['locality']); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="e.g., Koramangala, HSR Layout">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Complete address (shown only after matching)"><?php echo htmlspecialchars($listing['address']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Room Details -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">3</span>
                    Room Details
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Room Type</label>
                        <select name="room_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="private" <?php echo $listing['room_type'] === 'private' ? 'selected' : ''; ?>>Private Room</option>
                            <option value="shared" <?php echo $listing['room_type'] === 'shared' ? 'selected' : ''; ?>>Shared Room</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Furnishing</label>
                        <select name="furnishing" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="furnished" <?php echo $listing['furnishing'] === 'furnished' ? 'selected' : ''; ?>>Fully Furnished</option>
                            <option value="semi-furnished" <?php echo $listing['furnishing'] === 'semi-furnished' ? 'selected' : ''; ?>>Semi Furnished</option>
                            <option value="unfurnished" <?php echo $listing['furnishing'] === 'unfurnished' ? 'selected' : ''; ?>>Unfurnished</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Preferred Gender</label>
                        <select name="gender_preference" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="any" <?php echo $listing['gender_preference'] === 'any' ? 'selected' : ''; ?>>Any</option>
                            <option value="male" <?php echo $listing['gender_preference'] === 'male' ? 'selected' : ''; ?>>Male Only</option>
                            <option value="female" <?php echo $listing['gender_preference'] === 'female' ? 'selected' : ''; ?>>Female Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Available From</label>
                        <input type="date" name="available_from" value="<?php echo $listing['available_from']; ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">4</span>
                    Amenities
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php 
                    $allAmenities = [
                        'wifi' => '📶 WiFi',
                        'ac' => '❄️ AC',
                        'parking' => '🚗 Parking',
                        'gym' => '💪 Gym',
                        'laundry' => '🧺 Laundry',
                        'kitchen' => '🍳 Kitchen',
                        'tv' => '📺 TV',
                        'balcony' => '🌅 Balcony',
                        'security' => '🔒 Security',
                        'power_backup' => '🔌 Power Backup',
                        'water' => '💧 24/7 Water',
                        'cleaning' => '🧹 Cleaning'
                    ];
                    foreach ($allAmenities as $key => $label): 
                        $checked = in_array($key, $existingAmenities);
                    ?>
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 <?php echo $checked ? 'bg-primary-light border-primary' : 'border-gray-200'; ?>">
                        <input type="checkbox" name="amenities[]" value="<?php echo $key; ?>" 
                               <?php echo $checked ? 'checked' : ''; ?>
                               class="w-5 h-5 text-primary rounded">
                        <span class="ml-3"><?php echo $label; ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Photos -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">5</span>
                    Photos
                </h2>
                
                <?php if (!empty($listing['photos'])): ?>
                <div class="mb-4">
                    <p class="text-gray-600 mb-2">Current Photos:</p>
                    <div class="flex gap-2 overflow-x-auto">
                        <?php foreach (explode(',', $listing['photos']) as $photo): ?>
                        <img src="<?php echo SITE_URL; ?>/uploads/<?php echo $photo; ?>" 
                             class="w-24 h-24 object-cover rounded-lg"
                             onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=200'">
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden">
                    <label for="photos" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600">Click to upload new photos</p>
                        <p class="text-gray-400 text-sm">(This will replace current photos)</p>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">6</span>
                    Listing Status
                </h2>
                
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="active" 
                               <?php echo $listing['status'] === 'active' ? 'checked' : ''; ?>
                               class="w-5 h-5 text-primary">
                        <span class="ml-2">Active (Visible to others)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="inactive" 
                               <?php echo $listing['status'] === 'inactive' ? 'checked' : ''; ?>
                               class="w-5 h-5 text-primary">
                        <span class="ml-2">Inactive (Hidden)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="rented" 
                               <?php echo $listing['status'] === 'rented' ? 'checked' : ''; ?>
                               class="w-5 h-5 text-primary">
                        <span class="ml-2">Rented Out</span>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 bg-primary text-white py-4 rounded-xl font-semibold hover:bg-primary-dark transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
                <a href="<?php echo SITE_URL; ?>/pages/listings/details.php?id=<?php echo $listingId; ?>" 
                   class="px-8 py-4 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
