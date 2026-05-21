<?php
/**
 * RoomSaathi - About Us Page
 */
$pageTitle = 'About Us';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">About RoomSaathi</h1>
            <p class="text-xl text-blue-100">Finding the perfect roommate, simplified.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Our Story -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Our Story</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                RoomSaathi was born out of a simple observation: finding a compatible roommate shouldn't be stressful. 
                As students and young professionals ourselves, we experienced the challenges of searching for 
                roommates through unreliable classifieds and random connections.
            </p>
            <p class="text-gray-600 leading-relaxed">
                We created RoomSaathi to make this process easier, safer, and more efficient. By combining 
                smart matching algorithms with a user-friendly interface, we help people find not just a 
                room, but a compatible living partner.
            </p>
        </div>

        <!-- Mission & Vision -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Our Mission</h3>
                <p class="text-gray-600">
                    To connect compatible individuals and create harmonious living arrangements through 
                    technology-driven matchmaking.
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Our Vision</h3>
                <p class="text-gray-600">
                    To become India's most trusted platform for finding compatible roommates and 
                    shared accommodations.
                </p>
            </div>
        </div>

        <!-- How It Works -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">How RoomSaathi Works</h2>
            
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Create Profile</h4>
                    <p class="text-gray-500 text-sm">Sign up and complete your profile with lifestyle preferences</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Browse & Post</h4>
                    <p class="text-gray-500 text-sm">Browse available rooms or post your own listing</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Match & Connect</h4>
                    <p class="text-gray-500 text-sm">Like listings you're interested in and get matched</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">4</div>
                    <h4 class="font-semibold text-gray-800 mb-2">Move In!</h4>
                    <p class="text-gray-500 text-sm">Chat, meet, and finalize your new living arrangement</p>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">What Makes Us Different</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Smart Compatibility Matching</h4>
                        <p class="text-gray-500 text-sm">Our algorithm considers 8+ lifestyle factors to find your ideal match</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Verified Profiles</h4>
                        <p class="text-gray-500 text-sm">Phone & email verification for safer connections</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Tinder-Style Browsing</h4>
                        <p class="text-gray-500 text-sm">Intuitive swipe interface makes browsing fun and fast</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">In-App Messaging</h4>
                        <p class="text-gray-500 text-sm">Communicate securely without sharing personal contact</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-gradient-to-r from-primary to-blue-600 text-white rounded-2xl p-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold">1000+</div>
                    <div class="text-blue-100">Active Users</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">500+</div>
                    <div class="text-blue-100">Listings</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">10+</div>
                    <div class="text-blue-100">Cities</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">95%</div>
                    <div class="text-blue-100">Satisfaction</div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Ready to Find Your Roommate?</h2>
            <p class="text-gray-600 mb-6">Join thousands of users who found their perfect match on RoomSaathi</p>
            <?php if (!isLoggedIn()): ?>
            <a href="<?php echo SITE_URL; ?>/pages/auth/signup.php" 
               class="inline-block bg-primary text-white px-8 py-4 rounded-full font-semibold hover:bg-primary-dark transition-all">
                Get Started Free
            </a>
            <?php else: ?>
            <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" 
               class="inline-block bg-primary text-white px-8 py-4 rounded-full font-semibold hover:bg-primary-dark transition-all">
                Browse Listings
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
