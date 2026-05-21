<?php
/**
 * RoomSaathi - My Listings
 */
$pageTitle = 'My Listings';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$listings = getUserListings($userId);
$flash = getFlash();

require_once '../../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">My Listings</h1>
                <a href="post.php" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition flex items-center gap-2">
                    <span>➕</span> Post New Room
                </a>
            </div>

            <!-- Success Message -->
            <?php if (isset($flash['success'])): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                ✅ <?php echo $flash['success']; ?>
            </div>
            <?php endif; ?>

            <!-- Listings Grid -->
            <?php if (empty($listings)): ?>
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="flex justify-center mb-4">
                    <svg class="w-24 h-24 text-primary/30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">No Listings Yet</h2>
                <p class="text-gray-600 mb-6">You haven't posted any rooms yet. Start by posting your first listing!</p>
                <a href="post.php" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark inline-block">
                    Post Your First Room
                </a>
            </div>
            <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($listings as $listing): 
                    $photos = json_decode($listing['photos'], true) ?: [];
                    $amenities = json_decode($listing['amenities'], true) ?: [];
                ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 relative">
                        <?php if (!empty($photos)): ?>
                            <img src="<?php echo SITE_URL; ?>/uploads/listings/<?php echo $photos[0]; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-blue-50">
                                <svg class="w-24 h-24 text-primary/40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                            Active
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-800 mb-1"><?php echo htmlspecialchars($listing['title']); ?></h3>
                        <p class="text-gray-500 text-sm mb-2">📍 <?php echo $listing['locality']; ?>, <?php echo $listing['city']; ?></p>
                        
                        <p class="text-primary text-2xl font-bold mb-3">₹<?php echo number_format($listing['rent']); ?><span class="text-sm text-gray-500 font-normal">/month</span></p>
                        
                        <div class="flex gap-2 flex-wrap mb-4">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">🛏️ <?php echo ucfirst($listing['room_type']); ?></span>
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">🪑 <?php echo ucfirst($listing['furnishing']); ?></span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="edit.php?id=<?php echo $listing['id']; ?>" class="flex-1 text-center bg-primary-light text-primary py-2 rounded-lg hover:bg-primary hover:text-white transition text-sm">
                                ✏️ Edit
                            </a>
                            <button onclick="deleteListing(<?php echo $listing['id']; ?>)" class="flex-1 text-center bg-red-100 text-red-600 py-2 rounded-lg hover:bg-red-500 hover:text-white transition text-sm">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        function deleteListing(id) {
            if (confirm('Are you sure you want to delete this listing?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>

<?php require_once '../../includes/footer.php'; ?>
