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
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>/index.php" class="flex items-center">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="flex gap-6 items-center">
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>/pages/dashboard/index.php" class="text-gray-600 hover:text-primary">Dashboard</a>
                    <a href="<?php echo SITE_URL; ?>/pages/listings/browse.php" class="text-gray-600 hover:text-primary">Browse</a>
                    <a href="<?php echo SITE_URL; ?>/pages/matching/matches.php" class="text-gray-600 hover:text-primary">Matches</a>
                    <a href="<?php echo SITE_URL; ?>/pages/profile/my-profile.php" class="text-gray-600 hover:text-primary">Profile</a>
                    <a href="<?php echo SITE_URL; ?>/pages/auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600">Logout</a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/#features" class="text-gray-600 hover:text-primary">Features</a>
                    <a href="<?php echo SITE_URL; ?>/#how-it-works" class="text-gray-600 hover:text-primary">How It Works</a>
                    <a href="<?php echo SITE_URL; ?>/pages/auth/login.php" class="text-gray-600 hover:text-primary">Login</a>
                    <a href="<?php echo SITE_URL; ?>/pages/auth/signup.php" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-dark">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($flash = getFlash()): ?>
    <div class="fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg <?php echo $flash['type'] === 'success' ? 'bg-green-500' : 'bg-red-500'; ?> text-white">
        <?php echo $flash['message']; ?>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="pt-16">
