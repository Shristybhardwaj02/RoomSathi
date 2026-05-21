<?php
/**
 * RoomSaathi - Signup Page
 */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/pages/dashboard/index.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $error = 'Name should only contain letters and spaces';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Phone number must be exactly 10 digits';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = 'Password must contain uppercase, lowercase, number and special character (@$!%*?&)';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        // Check if email/phone exists
        $checkSql = "SELECT id FROM users WHERE email = ? OR phone = ?";
        $stmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
            $error = 'Email or phone already registered';
        } else {
            $result = registerUser($name, $email, $phone, $password);
            
            if ($result['success']) {
                // Skip OTP, go directly to login
                redirect('/pages/auth/login.php?registered=1');
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - RoomSaathi</title>
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

    <!-- Signup Form -->
    <div class="pt-24 pb-12 px-4 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="decoration-blur w-96 h-96 bg-primary -top-48 -left-48 fixed"></div>
        <div class="decoration-blur w-64 h-64 bg-blue-400 -bottom-32 -right-32 fixed"></div>
        
        <div class="max-w-md mx-auto relative z-10">
            <div class="card-material p-8">
                <div class="text-center mb-8">
                    <div class="mb-4">
                        <img src="<?php echo SITE_URL; ?>/assets/images/Logo.svg" alt="RoomSaathi Logo" class="w-20 h-20 mx-auto">
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h1>
                    <p class="text-gray-500">Join RoomSaathi and find your perfect roommate</p>
                </div>

                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5" id="signupForm">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" name="name" id="name" required
                               pattern="^[A-Za-z\s]+$"
                               title="Name should only contain letters and spaces"
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Enter your full name"
                               onkeypress="return /[a-zA-Z\s]/.test(event.key)"
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        <p class="text-xs text-gray-400 mt-1">Only letters allowed</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Enter your email"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" required
                               pattern="[0-9]{10}"
                               maxlength="10"
                               title="Phone number must be 10 digits"
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Enter 10-digit phone number"
                               onkeypress="return /[0-9]/.test(event.key)"
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        <p class="text-xs text-gray-400 mt-1">Only 10 digits allowed</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                               minlength="8"
                               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                               title="Password must be at least 8 characters with uppercase, lowercase, number and special character"
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Create a strong password"
                               onkeyup="checkPasswordStrength(this.value)">
                        <div id="passwordStrength" class="mt-2">
                            <div class="flex gap-1">
                                <div id="str1" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="str2" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="str3" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="str4" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                            </div>
                            <p id="strengthText" class="text-xs mt-1 text-gray-500">Min 8 chars: uppercase, lowercase, number, special (@$!%*?&)</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required
                               class="w-full px-4 py-3.5 input-material"
                               placeholder="Confirm your password"
                               onkeyup="checkPasswordMatch()">
                        <p id="matchText" class="text-xs mt-1 text-gray-400"></p>
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full btn-gradient text-white py-4 rounded-xl font-bold text-lg">
                        Create Account
                    </button>
                </form>

                <script>
                function checkPasswordStrength(password) {
                    let strength = 0;
                    if (password.length >= 8) strength++;
                    if (/[a-z]/.test(password)) strength++;
                    if (/[A-Z]/.test(password)) strength++;
                    if (/[0-9]/.test(password)) strength++;
                    if (/[@$!%*?&]/.test(password)) strength++;

                    const widths = ['0%', '20%', '40%', '70%', '100%'];
                    const texts = ['', 'Weak', 'Fair', 'Good', 'Strong'];
                    const textColors = ['text-gray-400', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500'];

                    document.getElementById('strengthBar').style.width = widths[strength];
                    document.getElementById('strengthText').textContent = strength > 0 ? 'Password Strength: ' + texts[strength] : 'Min 8 chars: uppercase, lowercase, number, special (@$!%*?&)';
                    document.getElementById('strengthText').className = 'text-xs mt-2 ' + textColors[strength];
                }

                function checkPasswordMatch() {
                    const password = document.getElementById('password').value;
                    const confirm = document.getElementById('confirm_password').value;
                    const matchText = document.getElementById('matchText');
                    
                    if (confirm.length > 0) {
                        if (password === confirm) {
                            matchText.textContent = '✓ Passwords match';
                            matchText.className = 'text-xs mt-1 text-green-500';
                        } else {
                            matchText.textContent = '✗ Passwords do not match';
                            matchText.className = 'text-xs mt-1 text-red-500';
                        }
                    } else {
                        matchText.textContent = '';
                    }
                }
                </script>

                <p class="text-center text-gray-400 mt-6 text-sm">
                    By signing up, you agree to our Terms of Service and Privacy Policy
                </p>
            </div>
        </div>
    </div>

</body>
</html>
