<?php
/**
 * RoomSaathi - Login Page
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/pages/dashboard/index.php');
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        $result = loginUser($email, $password);
        
        if ($result['success']) {
            // Check if profile is complete
            if ($result['user']['profile_complete'] == 0) {
                redirect('/pages/profile/setup-step1.php');
            } else {
                redirect('/pages/dashboard/index.php');
            }
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RoomSaathi</title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .card-material { background: white; border-radius: 1.5rem; box-shadow: 0 10px 40px -10px rgba(5,15,145,0.15); }
        .input-material { border-radius: 0.75rem; border: 2px solid #e5e7eb; transition: all 0.3s ease; }
        .input-material:focus { border-color: #050f91; box-shadow: 0 0 0 4px rgba(5,15,145,0.1); outline: none; }
        .btn-gradient { background: linear-gradient(135deg, #050f91 0%, #3b5bdb 100%); transition: all 0.3s ease; }
        .btn-gradient:hover { box-shadow: 0 8px 25px -5px rgba(5,15,145,0.4); transform: translateY(-2px); }
        .decoration-blur { position: absolute; border-radius: 9999px; filter: blur(100px); opacity: 0.4; }
        .nav-item { position: relative; padding: 10px 18px; font-weight: 500; color: #4b5563; border-radius: 12px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-size: 0.95rem; }
        .nav-item:hover { color: #050f91; background: linear-gradient(135deg, rgba(5, 15, 145, 0.08) 0%, rgba(59, 91, 219, 0.06) 100%); }
        .nav-item::before { content: ''; position: absolute; bottom: 4px; left: 50%; width: 0; height: 3px; background: linear-gradient(90deg, #050f91, #3b5bdb); border-radius: 3px; transform: translateX(-50%); transition: width 0.3s ease; }
        .nav-item:hover::before { width: 60%; }
        .nav-icon { width: 20px; height: 20px; margin-right: 6px; opacity: 0.7; transition: all 0.2s; }
        .nav-item:hover .nav-icon { opacity: 1; transform: scale(1.1); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">

    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-1">
            <div class="flex justify-between items-center">
                <a href="<?php echo SITE_URL; ?>" class="flex items-center gap-2 group">
                    <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-16 w-16 -my-2 group-hover:scale-105 transition-transform" style="max-height:48px;">
                    <span class="text-lg font-semibold text-primary hidden sm:inline">RoomSathi</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="<?php echo SITE_URL; ?>" class="nav-item">
                        <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Home
                    </a>
                    <a href="<?php echo SITE_URL; ?>/#features" class="nav-item">
                        <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        Features
                    </a>
                    <a href="login.php" class="nav-item font-semibold">
                        <svg class="nav-icon inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Login
                    </a>
                    <a href="signup.php" class="ml-2 bg-gradient-to-r from-primary to-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5 transition-all">
                        Sign Up Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="pt-24 pb-12 px-4 min-h-screen flex items-center relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="decoration-blur w-96 h-96 bg-primary -top-48 -right-48"></div>
        <div class="decoration-blur w-64 h-64 bg-blue-400 -bottom-32 -left-32"></div>
        
        <div class="max-w-md mx-auto w-full relative z-10">
            <div class="card-material p-8">
                <div class="text-center mb-8">
                    <div class="mb-4">
                        <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi Logo" class="w-20 h-20 mx-auto">
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                    <p class="text-gray-500">Login to find your perfect roommate</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['verified']) || isset($_GET['registered'])): ?>
                <div class="bg-green-50 border border-green-100 text-green-600 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?php echo isset($_GET['registered']) ? 'Account created successfully! Please login.' : 'Account verified successfully! Please login.'; ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email or Phone</label>
                        <input type="text" name="email" required
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Enter your email or phone"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Enter your password">
                    </div>

                    <div class="flex justify-between items-center">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-600 text-sm">Remember me</span>
                        </label>
                        <span class="text-gray-400 text-sm">Forgot Password?</span>
                    </div>

                    <button type="submit" 
                            class="w-full btn-gradient text-white py-4 rounded-xl font-bold text-lg">
                        Login
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-500">New to RoomSaathi?</p>
                    <a href="signup.php" class="text-primary font-semibold hover:underline">Create an account</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
