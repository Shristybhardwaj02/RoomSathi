<?php
/**
 * RoomSaathi - Terms & Conditions Page
 */
$pageTitle = 'Terms & Conditions';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">Terms & Conditions</h1>
            <p class="text-blue-100">Last updated: February 2026</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="prose max-w-none">
                <h2 class="text-xl font-bold text-gray-800 mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-600 mb-6">
                    By accessing and using RoomSaathi ("the Platform"), you accept and agree to be bound by the terms 
                    and provisions of this agreement. If you do not agree to abide by these terms, please do not use this service.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">2. Description of Service</h2>
                <p class="text-gray-600 mb-6">
                    RoomSaathi is an online platform that connects individuals looking for roommates and shared accommodations. 
                    We provide tools to post listings, browse available rooms, and communicate with potential matches.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">3. User Accounts</h2>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>You must provide accurate and complete information when creating an account</li>
                    <li>You are responsible for maintaining the confidentiality of your password</li>
                    <li>You must be at least 18 years old to use this service</li>
                    <li>One person may not maintain multiple accounts</li>
                    <li>You agree to notify us immediately of any unauthorized use of your account</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">4. User Conduct</h2>
                <p class="text-gray-600 mb-4">You agree NOT to:</p>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>Post false, misleading, or fraudulent content</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Use the platform for any illegal activities</li>
                    <li>Post spam, promotional content, or advertisements</li>
                    <li>Collect or harvest user information without consent</li>
                    <li>Impersonate another person or entity</li>
                    <li>Upload viruses or malicious code</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">5. Listings & Content</h2>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>You are solely responsible for the accuracy of your listings</li>
                    <li>Listings must represent genuine accommodations</li>
                    <li>Photos must be accurate representations of the property</li>
                    <li>We reserve the right to remove any listing without notice</li>
                    <li>You retain ownership of content you post, but grant us a license to display it</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">6. Safety & Meetings</h2>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <p class="text-yellow-800 font-medium mb-2">⚠️ Important Safety Notice</p>
                    <ul class="text-yellow-700 list-disc pl-6 space-y-1 text-sm">
                        <li>Always meet potential roommates in public places first</li>
                        <li>Never share financial information before meeting in person</li>
                        <li>Verify property details before making any payments</li>
                        <li>Trust your instincts - if something feels wrong, report it</li>
                    </ul>
                </div>

                <h2 class="text-xl font-bold text-gray-800 mb-4">7. Limitation of Liability</h2>
                <p class="text-gray-600 mb-6">
                    RoomSaathi acts only as a platform to connect users. We do not verify listings, conduct background checks, 
                    or guarantee the accuracy of information provided by users. We are not responsible for any disputes, damages, 
                    or losses arising from interactions between users.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">8. Privacy</h2>
                <p class="text-gray-600 mb-6">
                    Your use of RoomSaathi is also governed by our <a href="<?php echo SITE_URL; ?>/pages/privacy.php" class="text-primary hover:underline">Privacy Policy</a>.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">9. Termination</h2>
                <p class="text-gray-600 mb-6">
                    We reserve the right to terminate or suspend your account at any time for violations of these terms, 
                    without prior notice. You may also delete your account at any time through the settings page.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">10. Changes to Terms</h2>
                <p class="text-gray-600 mb-6">
                    We may update these terms from time to time. Continued use of the platform after changes constitutes 
                    acceptance of the new terms. We will notify users of significant changes via email or platform notification.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">11. Contact</h2>
                <p class="text-gray-600 mb-6">
                    For questions about these terms, please contact us at 
                    <a href="mailto:legal@roomsaathi.com" class="text-primary hover:underline">legal@roomsaathi.com</a>.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
