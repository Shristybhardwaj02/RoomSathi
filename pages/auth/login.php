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
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?php echo SITE_URL; ?>" class="flex items-center">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-10">
            </a>
            <div class="flex gap-4 items-center">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="signup.php" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-dark">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="pt-24 pb-12 px-4 min-h-screen flex items-center">
        <div class="max-w-md mx-auto w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                    <p class="text-gray-600">Login to find your perfect roommate</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['verified'])): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                    Account verified successfully! Please login.
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email or Phone</label>
                        <input type="text" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Enter your email or phone"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Enter your password">
                    </div>

                    <div class="flex justify-between items-center">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-600 text-sm">Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="text-primary text-sm hover:underline">Forgot Password?</a>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition">
                        Login
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-600">New to RoomSaathi?</p>
                    <a href="signup.php" class="text-primary font-medium hover:underline">Create an account</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
