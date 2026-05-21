<?php
/**
 * Create Admin User - One-time script
 */

require_once 'includes/config.php';

try {
    // Check if admin already exists
    $checkQuery = "SELECT id FROM users WHERE email = 'admin@roomsaathi.com'";
    $checkResult = mysqli_query($conn, $checkQuery);
    $existing = mysqli_fetch_assoc($checkResult);
    
    if ($existing) {
        echo "Admin user already exists!";
        exit;
    }
    
    // Create admin user
    $email = 'admin@roomsaathi.com';
    $password = password_hash('admin@0910', PASSWORD_DEFAULT);
    $name = 'Admin';
    $phone = '0000000000';
    $is_verified = 1;
    
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, phone, password, is_verified) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $password, $is_verified);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Admin account created successfully!\n";
        echo "Email: admin@roomsaathi.com\n";
        echo "Password: admin@0910\n";
        echo "\nYou can now login to the admin dashboard.";
    } else {
        echo "❌ Error creating admin account: " . mysqli_error($conn);
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
