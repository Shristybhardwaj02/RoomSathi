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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-glow { box-shadow: 0 0 80px rgba(102, 126, 234, 0.4); }
        .float-animation { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(2deg); } 50% { transform: translateY(-20px) rotate(-2deg); } }
        .gradient-border { background: linear-gradient(white, white) padding-box, linear-gradient(135deg, #050f91, #667eea, #764ba2) border-box; border: 3px solid transparent; }
        .card-3d { perspective: 1000px; }
        .card-3d-inner { transform-style: preserve-3d; transition: transform 0.6s; }
        .card-3d:hover .card-3d-inner { transform: rotateY(5deg) rotateX(5deg); }
        .text-shadow-lg { text-shadow: 0 4px 30px rgba(0, 0, 0, 0.3); }
        .btn-shine { position: relative; overflow: hidden; }
        .btn-shine::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(to right, transparent, rgba(255,255,255,0.3), transparent); transform: rotate(45deg); animation: shine 3s infinite; }
        @keyframes shine { 0% { left: -50%; } 100% { left: 150%; } }
        .nav-item { position: relative; padding: 10px 18px; font-weight: 500; color: #4b5563; border-radius: 12px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-size: 0.95rem; display: flex; align-items: center; gap: 8px; }
        .nav-item:hover { color: #050f91; background: linear-gradient(135deg, rgba(5, 15, 145, 0.08) 0%, rgba(59, 91, 219, 0.06) 100%); }
        .nav-item::before { content: ''; position: absolute; bottom: 4px; left: 50%; width: 0; height: 3px; background: linear-gradient(90deg, #050f91, #3b5bdb); border-radius: 3px; transform: translateX(-50%); transition: width 0.3s ease; }
        .nav-item:hover::before { width: 60%; }
        .nav-icon { width: 20px; height: 20px; opacity: 0.7; transition: all 0.2s; }
        .nav-item:hover .nav-icon { opacity: 1; transform: scale(1.1); }
    </style>
</head>
<body class="bg-white page-enter">

    <!-- NAVIGATION BAR -->
    <nav class="fixed w-full top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-1">
            <div class="flex justify-between items-center">
                <a href="index.php" class="flex items-center gap-2 group">
                    <img src="assets/images/Logo.svg" alt="RoomSaathi" class="h-12 group-hover:scale-105 transition-transform">
                    <span class="text-lg font-semibold text-primary hidden sm:inline">RoomSathi</span>
                </a>
                <div class="hidden md:flex gap-2 items-center">
                    <a href="#features" class="nav-item">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        Features
                    </a>
                    <a href="#how-it-works" class="nav-item">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        How It Works
                    </a>
                    <a href="#matching" class="nav-item">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Matching
                    </a>
                    <a href="pages/auth/login.php" class="nav-item font-semibold">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Login
                    </a>
                    <a href="pages/auth/signup.php" class="ml-2 bg-gradient-to-r from-primary to-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5 transition-all">Sign Up Free</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="hero" class="relative bg-gradient-to-b from-gray-50 to-white text-gray-800 pt-32 pb-28 px-4 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-primary/5 rounded-full blur-3xl animate-pulse-soft"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-100/50 rounded-full blur-3xl animate-pulse-soft" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 w-[800px] h-[800px] -translate-x-1/2 -translate-y-1/2 border border-gray-100 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 w-[600px] h-[600px] -translate-x-1/2 -translate-y-1/2 border border-gray-100 rounded-full"></div>
        </div>
        
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in-up">
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-sm font-medium px-5 py-2.5 rounded-full mb-6 border border-primary/20">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        #1 Roommate Matching Platform
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-gray-800">Find Your Perfect Roommate in <span class="text-primary">One Swipe!</span></h1>
                    <p class="text-xl mb-8 text-gray-500 leading-relaxed">Smart matching based on lifestyle compatibility. No more random roommates, no more conflicts.</p>
                    
                    <div class="flex flex-wrap gap-4 mb-12">
                        <a href="pages/auth/signup.php" class="group bg-gradient-to-r from-primary to-blue-700 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-primary/30 hover:-translate-y-1 transition-all flex items-center gap-2">
                            Get Started Free
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="#how-it-works" class="border-2 border-gray-200 text-gray-700 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-50 hover:border-primary/20 transition-all">
                            See How It Works
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-4 gap-3">
                        <div class="text-center p-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-default hover-scale-sm">
                            <h3 class="text-2xl md:text-3xl font-bold text-primary">10K+</h3>
                            <p class="text-gray-500 text-sm">Users</p>
                        </div>
                        <div class="text-center p-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-default hover-scale-sm">
                            <h3 class="text-2xl md:text-3xl font-bold text-primary">5K+</h3>
                            <p class="text-gray-500 text-sm">Matches</p>
                        </div>
                        <div class="text-center p-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-default hover-scale-sm">
                            <h3 class="text-2xl md:text-3xl font-bold text-primary">20+</h3>
                            <p class="text-gray-500 text-sm">Cities</p>
                        </div>
                        <div class="text-center p-4 rounded-2xl bg-white/80 backdrop-blur-sm border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-default hover-scale-sm">
                            <h3 class="text-2xl md:text-3xl font-bold text-primary">87%</h3>
                            <p class="text-gray-500 text-sm">Match Rate</p>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Card Preview -->
                <div class="hidden md:block animate-fade-in-up delay-200">
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200 rounded-2xl p-5 text-gray-800 shadow-lg max-w-sm">
                        <div class="bg-gradient-to-br from-primary/10 via-blue-50 to-indigo-100 h-40 rounded-xl mb-3 flex items-center justify-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-xl bg-white/80 flex items-center justify-center">
                                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                        </div>
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg">Cozy Room in Koramangala</h3>
                            <span class="bg-primary/10 text-primary text-xs font-semibold px-2 py-1 rounded-full">92%</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Bangalore, Karnataka
                        </p>
                        <p class="text-primary font-extrabold text-2xl mb-3">₹12,000<span class="text-gray-400 text-sm font-normal">/month</span></p>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                No Smoking
                            </span>
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Vegetarian
                            </span>
                        </div>
                        <!-- Progress bar -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Compatibility</span>
                                <span class="font-semibold text-primary">92%</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="py-24 px-4 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 animate-fade-in-up">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Why Choose Us
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-800 mt-3 mb-4">Why Choose <span class="text-primary">RoomSaathi</span>?</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">We're not just another listing site. We're your roommate matchmaker.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-primary/20 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Smart Matching</h3>
                    <p class="text-gray-500 leading-relaxed">Our algorithm matches you based on 8 lifestyle factors for perfect compatibility.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-green-200 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Compatibility Score</h3>
                    <p class="text-gray-500 leading-relaxed">See your match percentage BEFORE connecting. No more guessing!</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-purple-200 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Swipe to Match</h3>
                    <p class="text-gray-500 leading-relaxed">Tinder-style cards make browsing fun and fast. Like or pass!</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-red-200 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Verified & Safe</h3>
                    <p class="text-gray-500 leading-relaxed">OTP verification and hidden contact details keep you safe.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">In-App Chat</h3>
                    <p class="text-gray-500 leading-relaxed">Message your matches directly without sharing personal numbers.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg hover:border-amber-200 transition-all group">
                    <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">100% Free</h3>
                    <p class="text-gray-500 leading-relaxed">No hidden charges, no premium plans. Completely free forever.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION -->
    <section id="how-it-works" class="py-24 px-4 bg-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.02]">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, #050f91 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Simple Process
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-800 mt-3 mb-4">How It <span class="text-primary">Works</span></h2>
                <p class="text-gray-500 text-lg">Find your perfect roommate in 4 simple steps</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all group">
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-primary text-white flex items-center justify-center text-xl font-bold group-hover:scale-110 transition-transform">1</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Create Profile</h3>
                    <p class="text-gray-500 text-sm">Sign up and tell us about your lifestyle preferences.</p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all group">
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-primary text-white flex items-center justify-center text-xl font-bold group-hover:scale-110 transition-transform">2</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Browse Listings</h3>
                    <p class="text-gray-500 text-sm">Swipe through rooms with compatibility scores.</p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all group">
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-primary text-white flex items-center justify-center text-xl font-bold group-hover:scale-110 transition-transform">3</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Like & Match</h3>
                    <p class="text-gray-500 text-sm">Like listings. When they like back - it's a match!</p>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-lg hover:border-green-200 transition-all group">
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-green-500 text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Connect & Move In</h3>
                    <p class="text-gray-500 text-sm">Chat with matches and move in with your roommate!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- LIFESTYLE MATCHING SECTION -->
    <section id="matching" class="py-24 px-4 bg-gradient-to-b from-gray-50 to-white relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    Smart Algorithm
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-800 mt-3 mb-4">Smart Lifestyle <span class="text-primary">Matching</span></h2>
                <p class="text-gray-500 text-lg">We match you based on 8 key lifestyle factors</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Smoking</h4>
                    <p class="text-gray-400 text-sm">Yes / No / Occasionally</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Drinking</h4>
                    <p class="text-gray-400 text-sm">Yes / No / Socially</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3zm3 6h12M6 15h12"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Food</h4>
                    <p class="text-gray-400 text-sm">Veg / Non-Veg / Any</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-pink-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Pets</h4>
                    <p class="text-gray-400 text-sm">Have / Love / No Pets</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Sleep</h4>
                    <p class="text-gray-400 text-sm">Early / Night Owl / Flexible</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-cyan-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Cleanliness</h4>
                    <p class="text-gray-400 text-sm">Very Clean / Moderate</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Guests</h4>
                    <p class="text-gray-400 text-sm">Never / Sometimes / Often</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-lg hover:border-primary/20 transition-all">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Personality</h4>
                    <p class="text-gray-400 text-sm">Introvert / Extrovert</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <div class="inline-block py-5 px-8 bg-white/80 backdrop-blur-sm border border-gray-100 rounded-2xl shadow-sm">
                    <p class="text-gray-600">Each matching factor = <span class="font-bold text-primary">12.5%</span> → 8/8 match = <span class="font-extrabold text-primary text-lg">100% compatibility!</span></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section id="cta" class="py-24 px-4 bg-gradient-to-br from-primary via-blue-800 to-indigo-900 text-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="w-20 h-20 mx-auto mb-8 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Ready to Find Your Perfect Roommate?</h2>
            <p class="text-xl text-blue-200 mb-10 max-w-2xl mx-auto">Join thousands of students and professionals who found compatible roommates.</p>
            <a href="pages/auth/signup.php" class="group inline-flex items-center gap-3 bg-white text-primary px-10 py-5 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-white/30 hover:-translate-y-1 transition-all">
                Create Free Account
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
            <div class="mt-8 flex items-center justify-center gap-6 flex-wrap">
                <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm"><svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> No credit card</span>
                <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm"><svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> 100% free</span>
                <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm"><svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Takes 2 minutes</span>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 py-10 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-primary">© 2026 RoomSaathi. Made with <span class="text-red-500">❤️</span> by Shristy | College Project</p>
        </div>
    </footer>

</body>
</html>
