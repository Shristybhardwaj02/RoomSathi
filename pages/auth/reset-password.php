<?php
/**
 * RoomSaathi - Reset Password Page
 */
$pageTitle = 'Reset Password';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/pages/dashboard/');
    exit;
}

$token = $_GET['token'] ?? '';
$validToken = false;
$success = false;
$error = '';
$userId = null;

// Verify token
if (!empty($token)) {
    global $conn;
    $sql = "SELECT id FROM users WHERE otp_code = ? AND otp_expiry > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($result)) {
        $validToken = true;
        $userId = $user['id'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($password)) {
        $error = 'Please enter a new password';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Update password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "si", $hashedPassword, $userId);
        
        if (mysqli_stmt_execute($updateStmt)) {
            $success = true;
            // Clear session tokens
            unset($_SESSION['reset_token']);
            unset($_SESSION['reset_user_id']);
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}

require_once '../../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?php echo SITE_URL; ?>" class="inline-block">
                <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi" class="h-16 mx-auto mb-2">
            </a>
            <h1 class="text-2xl font-bold text-primary">RoomSathi</h1>
        </div>

        <?php if ($success): ?>
            <!-- Success Message -->
            <div class="text-center">
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                    <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-green-800">Password Reset Successful!</h3>
                    <p class="text-green-600 mt-2">Your password has been updated. You can now login with your new password.</p>
                </div>
                
                <a href="<?php echo SITE_URL; ?>/pages/auth/login.php" 
                   class="inline-block w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all text-center">
                    Login Now
                </a>
            </div>
        <?php elseif (!$validToken): ?>
            <!-- Invalid/Expired Token -->
            <div class="text-center">
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-red-800">Invalid or Expired Link</h3>
                    <p class="text-red-600 mt-2">This password reset link is invalid or has expired. Please request a new one.</p>
                </div>
                
                <a href="<?php echo SITE_URL; ?>/pages/auth/forgot-password.php" 
                   class="inline-block w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all text-center">
                    Request New Link
                </a>
            </div>
        <?php else: ?>
            <!-- Reset Password Form -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Set New Password</h2>
            <p class="text-gray-500 text-center mb-8">Create a strong password for your account</p>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">New Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required minlength="8"
                               class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Enter new password">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strengthBar" class="h-full w-0 transition-all duration-300"></div>
                        </div>
                        <p id="strengthText" class="text-sm mt-1 text-gray-500">Enter password to check strength</p>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        <input type="password" name="confirm_password" id="confirm_password" required
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Confirm new password">
                    </div>
                    <p id="matchText" class="text-sm mt-1 hidden"></p>
                </div>

                <button type="submit" 
                        class="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-primary-dark transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Reset Password
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}

// Password strength checker
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
    const colors = ['bg-gray-300', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-lime-500', 'bg-green-500'];
    const texts = ['Enter password', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
    
    strengthBar.style.width = widths[strength];
    strengthBar.className = 'h-full transition-all duration-300 ' + colors[strength];
    strengthText.textContent = texts[strength];
});

// Password match checker
document.getElementById('confirm_password')?.addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const matchText = document.getElementById('matchText');
    
    if (confirm.length > 0) {
        matchText.classList.remove('hidden');
        if (password === confirm) {
            matchText.textContent = '✓ Passwords match';
            matchText.className = 'text-sm mt-1 text-green-600';
        } else {
            matchText.textContent = '✗ Passwords do not match';
            matchText.className = 'text-sm mt-1 text-red-600';
        }
    } else {
        matchText.classList.add('hidden');
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
