<?php
/**
 * RoomSaathi - Landing Page
 */
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/pages/dashboard/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomSaathi - Find Your Perfect Roommate</title>
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="flex items-center">
                <img src="assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="hidden md:flex gap-6 items-center">
                <a href="#features" class="text-gray-600 hover:text-primary">Features</a>
                <a href="#how-it-works" class="text-gray-600 hover:text-primary">How It Works</a>
                <a href="#listings" class="text-gray-600 hover:text-primary">Listings</a>
                <a href="pages/auth/login.php" class="text-gray-600 hover:text-primary font-medium">Login</a>
                <a href="pages/auth/signup.php" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-dark transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="hero" class="bg-gradient-to-br from-primary to-blue-800 text-white pt-28 pb-20 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Find Your Perfect Roommate in One Swipe!</h1>
                    <p class="text-xl mb-8 text-blue-100">Smart matching based on lifestyle compatibility. No more random roommates, no more conflicts.</p>
                    
                    <div class="flex flex-wrap gap-4 mb-10">
                        <a href="pages/auth/signup.php" class="bg-white text-primary px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 shadow-lg transition">
                            Get Started Free
                        </a>
                        <a href="#how-it-works" class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-primary transition">
                            See How It Works
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-4 gap-4">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold">10K+</h3>
                            <p class="text-blue-200 text-sm">Users</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold">5K+</h3>
                            <p class="text-blue-200 text-sm">Matches</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold">20+</h3>
                            <p class="text-blue-200 text-sm">Cities</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold">87%</h3>
                            <p class="text-blue-200 text-sm">Match Rate</p>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Card Preview -->
                <div class="hidden md:block">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 text-gray-800 transform rotate-3 hover:rotate-0 transition-transform">
                        <div class="bg-gray-200 h-48 rounded-xl mb-4 flex items-center justify-center">
                            <span class="text-6xl">🏠</span>
                        </div>
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg">Cozy Room in Koramangala</h3>
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">92%</span>
                        </div>
                        <p class="text-gray-500 mb-2">Bangalore, Karnataka</p>
                        <p class="text-primary font-bold text-xl mb-4">₹12,000/month</p>
                        <div class="flex gap-2">
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm">No Smoking</span>
                            <span class="bg-primary-light text-primary px-3 py-1 rounded-full text-sm">Vegetarian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="py-20 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose RoomSaathi?</h2>
                <p class="text-gray-600 text-lg">We're not just another listing site. We're your roommate matchmaker.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">🎯</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Smart Matching</h3>
                    <p class="text-gray-600">Our algorithm matches you based on 8 lifestyle factors for perfect compatibility.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">💯</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Compatibility Score</h3>
                    <p class="text-gray-600">See your match percentage BEFORE connecting. No more guessing!</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">👆</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Swipe to Match</h3>
                    <p class="text-gray-600">Tinder-style cards make browsing fun and fast. Like or pass!</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">🔒</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Verified & Safe</h3>
                    <p class="text-gray-600">OTP verification and hidden contact details keep you safe.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">💬</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">In-App Chat</h3>
                    <p class="text-gray-600">Message your matches directly without sharing personal numbers.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <span class="text-3xl">🆓</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">100% Free</h3>
                    <p class="text-gray-600">No hidden charges, no premium plans. Completely free forever.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION -->
    <section id="how-it-works" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-gray-600 text-lg">Find your perfect roommate in 4 simple steps</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">1</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Create Profile</h3>
                    <p class="text-gray-600">Sign up and tell us about your lifestyle preferences.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">2</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Browse Listings</h3>
                    <p class="text-gray-600">Swipe through rooms with compatibility scores.</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">3</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Like & Match</h3>
                    <p class="text-gray-600">Like listings. When they like back - it's a match!</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">4</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Connect & Move In</h3>
                    <p class="text-gray-600">Chat with matches and move in with your roommate!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- LIFESTYLE MATCHING SECTION -->
    <section id="matching" class="py-20 px-4 bg-primary-light">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Smart Lifestyle Matching</h2>
                <p class="text-gray-600 text-lg">We match you based on 8 key lifestyle factors</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🚬</span>
                    <h4 class="font-bold text-gray-800">Smoking</h4>
                    <p class="text-gray-500 text-sm">Yes / No / Occasionally</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🍺</span>
                    <h4 class="font-bold text-gray-800">Drinking</h4>
                    <p class="text-gray-500 text-sm">Yes / No / Socially</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🍽️</span>
                    <h4 class="font-bold text-gray-800">Food</h4>
                    <p class="text-gray-500 text-sm">Veg / Non-Veg / Any</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🐾</span>
                    <h4 class="font-bold text-gray-800">Pets</h4>
                    <p class="text-gray-500 text-sm">Have / Love / No Pets</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🌙</span>
                    <h4 class="font-bold text-gray-800">Sleep</h4>
                    <p class="text-gray-500 text-sm">Early / Night Owl / Flexible</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🧹</span>
                    <h4 class="font-bold text-gray-800">Cleanliness</h4>
                    <p class="text-gray-500 text-sm">Very Clean / Moderate</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">🎉</span>
                    <h4 class="font-bold text-gray-800">Guests</h4>
                    <p class="text-gray-500 text-sm">Never / Sometimes / Often</p>
                </div>
                <div class="bg-white p-6 rounded-xl text-center shadow">
                    <span class="text-4xl mb-3 block">👤</span>
                    <h4 class="font-bold text-gray-800">Personality</h4>
                    <p class="text-gray-500 text-sm">Introvert / Extrovert</p>
                </div>
            </div>
            
            <p class="text-center mt-8 text-gray-600">Each matching factor = 12.5% → 8/8 match = 100% compatibility!</p>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section id="cta" class="py-20 px-4 bg-primary text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Find Your Perfect Roommate?</h2>
            <p class="text-xl text-blue-200 mb-8">Join thousands of students and professionals who found compatible roommates.</p>
            <a href="pages/auth/signup.php" class="inline-block bg-white text-primary px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 shadow-lg transition">
                Create Free Account
            </a>
            <p class="mt-4 text-blue-200">No credit card required • 100% free • Takes 2 minutes</p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <img src="assets/images/Logo.svg" alt="RoomSaathi" class="h-10 mb-4 brightness-0 invert">
                    <p class="text-gray-400">Find your perfect roommate with smart lifestyle matching.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <div class="flex flex-col gap-2">
                        <a href="#features" class="text-gray-400 hover:text-white">Features</a>
                        <a href="#how-it-works" class="text-gray-400 hover:text-white">How It Works</a>
                        <a href="pages/auth/login.php" class="text-gray-400 hover:text-white">Login</a>
                        <a href="pages/auth/signup.php" class="text-gray-400 hover:text-white">Sign Up</a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Cities</h4>
                    <div class="flex flex-col gap-2 text-gray-400">
                        <span>Bangalore</span>
                        <span>Mumbai</span>
                        <span>Delhi NCR</span>
                        <span>Pune</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <div class="flex flex-col gap-2 text-gray-400">
                        <p>📧 hello@roomsaathi.com</p>
                        <p>📞 +91 98765 43210</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>© 2026 RoomSaathi. Made with ❤️ by Shristy | College Project</p>
            </div>
        </div>
    </footer>

</body>
</html>
