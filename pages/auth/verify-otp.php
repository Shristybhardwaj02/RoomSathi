<?php
/**
 * RoomSaathi - OTP Verification Page
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if user needs verification
if (!isset($_SESSION['verify_user_id'])) {
    redirect('/pages/auth/signup.php');
}

$userId = $_SESSION['verify_user_id'];
$error = '';
$demoOtp = isset($_SESSION['verify_otp']) ? $_SESSION['verify_otp'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = sanitize($_POST['otp']);
    
    if (empty($otp)) {
        $error = 'Please enter the OTP';
    } elseif (strlen($otp) !== 6) {
        $error = 'OTP must be 6 digits';
    } else {
        if (verifyOTP($userId, $otp)) {
            // Clear session data
            unset($_SESSION['verify_user_id']);
            unset($_SESSION['verify_otp']);
            
            redirect('/pages/auth/login.php?verified=1');
        } else {
            $error = 'Invalid or expired OTP';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - RoomSaathi</title>
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
        </div>
    </nav>

    <!-- OTP Form -->
    <div class="pt-24 pb-12 px-4 min-h-screen flex items-center">
        <div class="max-w-md mx-auto w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <div class="bg-primary-light w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">📱</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Verify Your Account</h1>
                    <p class="text-gray-600">We've sent a 6-digit OTP to your phone/email</p>
                </div>

                <!-- Demo OTP Display (Remove in production) -->
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-6 text-center">
                    <p class="text-sm">Demo Mode - Your OTP is:</p>
                    <p class="text-2xl font-bold"><?php echo $demoOtp; ?></p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2 text-center">Enter OTP</label>
                        <input type="text" name="otp" required maxlength="6"
                               class="w-full px-4 py-4 border border-gray-300 rounded-lg text-center text-2xl tracking-widest focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="000000">
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-dark transition">
                        Verify OTP
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">Didn't receive the code?</p>
                    <button class="text-primary font-medium hover:underline mt-2">Resend OTP</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
