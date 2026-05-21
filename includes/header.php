<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - RoomSaathi' : 'RoomSaathi - Find Your Perfect Roommate'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#050f91',
                        'primary-dark': '#03085c',
                        'primary-light': '#E8EAFF',
                    },
                    boxShadow: {
                        'material': '0 2px 8px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04)',
                        'material-lg': '0 8px 24px rgba(5, 15, 145, 0.15), 0 4px 12px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(5, 15, 145, 0.3)',
                        'glow-lg': '0 0 40px rgba(5, 15, 145, 0.4)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <link href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap\" rel=\"stylesheet\">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Premium Navbar Styles */
        .navbar-premium {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(5, 15, 145, 0.08);
        }
        
        .nav-item {
            position: relative;
            padding: 10px 18px;
            font-weight: 500;
            color: #4b5563;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }
        
        .nav-item:hover {
            color: #050f91;
            background: linear-gradient(135deg, rgba(5, 15, 145, 0.08) 0%, rgba(59, 91, 219, 0.06) 100%);
        }
        
        .nav-item.active {
            color: #050f91;
            background: linear-gradient(135deg, rgba(5, 15, 145, 0.12) 0%, rgba(59, 91, 219, 0.08) 100%);
            font-weight: 600;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #050f91, #3b5bdb);
            border-radius: 3px;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            width: 60%;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 6px;
            opacity: 0.7;
            transition: all 0.2s;
        }
        
        .nav-item:hover .nav-icon {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .btn-premium {
            background: linear-gradient(135deg, #050f91 0%, #1a237e 50%, #3b5bdb 100%);
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
            box-shadow: 0 4px 15px rgba(5, 15, 145, 0.4);
        }
        
        .btn-premium:hover {
            box-shadow: 0 8px 30px rgba(5, 15, 145, 0.5);
            transform: translateY(-2px);
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s infinite;
        }
        
        .avatar-nav {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            border: 2px solid rgba(5, 15, 145, 0.2);
            transition: all 0.3s;
        }
        
        .avatar-nav:hover {
            border-color: #050f91;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen">

    <!-- PREMIUM NAVIGATION BAR -->
    <nav class="navbar-premium fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-1">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="<?php echo SITE_URL; ?>/index.php" class="flex items-center gap-2 group">
                    <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-12 group-hover:scale-105 transition-transform">
                    <span class="text-lg font-semibold text-primary hidden sm:inline">RoomSathi</span>
                </a>
                
                <!-- Navigation Items -->
                <div class="flex items-center gap-2">
                    <?php if (isLoggedIn()): ?>
                        <?php 
                        $currentPage = basename($_SERVER['PHP_SELF'], '.php');
                        $currentDir = basename(dirname($_SERVER['PHP_SELF']));
                        ?>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/dashboard/index.php" class="nav-item <?php echo ($currentDir == 'dashboard') ? 'active' : ''; ?>">
                            <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" class="nav-item <?php echo ($currentPage == 'browse') ? 'active' : ''; ?>">
                            <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Browse
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/matching/matches.php" class="nav-item relative <?php echo ($currentPage == 'matches') ? 'active' : ''; ?>">
                            <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            Matches
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/chat/index.php" class="nav-item relative <?php echo ($currentDir == 'chat') ? 'active' : ''; ?>">
                            <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Messages
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/profile/my-profile.php" class="nav-item <?php echo ($currentPage == 'my-profile') ? 'active' : ''; ?>">
                            <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/auth/logout.php" class="ml-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium hover:from-red-50 hover:to-red-100 hover:text-red-600 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/#features" class="nav-item">Features</a>
                        <a href="<?php echo SITE_URL; ?>/#how-it-works" class="nav-item">How It Works</a>
                        
                        <div class="w-px h-8 bg-gray-200 mx-2"></div>
                        
                        <a href="<?php echo SITE_URL; ?>/pages/auth/login.php" class="nav-item font-semibold">Login</a>
                        <a href="<?php echo SITE_URL; ?>/pages/auth/signup.php" class="ml-2 btn-premium text-white px-6 py-2.5 rounded-xl font-semibold transition-all">
                            Sign Up Free
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($flash = getFlash()): ?>
    <div class="fixed top-20 right-4 z-50 animate-fade-in-up">
        <div class="p-4 rounded-2xl shadow-2xl <?php echo $flash['type'] === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600'; ?> text-white flex items-center gap-3">
            <span class="text-xl"><?php echo $flash['type'] === 'success' ? '✅' : '⚠️'; ?></span>
            <?php echo $flash['message']; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="pt-20">
