<?php
/**
 * RoomSaathi - Privacy Policy Page
 */
$pageTitle = 'Privacy Policy';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">Privacy Policy</h1>
            <p class="text-blue-100">Last updated: February 2026</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-6">
                    At RoomSaathi, we take your privacy seriously. This Privacy Policy explains how we collect, 
                    use, disclose, and safeguard your information when you use our platform.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">1. Information We Collect</h2>
                
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Personal Information</h3>
                <ul class="text-gray-600 mb-4 list-disc pl-6 space-y-2">
                    <li>Name, email address, phone number</li>
                    <li>Age, gender, occupation, city</li>
                    <li>Profile photo</li>
                    <li>Lifestyle preferences (smoking, drinking, etc.)</li>
                </ul>

                <h3 class="text-lg font-semibold text-gray-700 mb-2">Listing Information</h3>
                <ul class="text-gray-600 mb-4 list-disc pl-6 space-y-2">
                    <li>Property details, address, rent</li>
                    <li>Photos of the property</li>
                    <li>Amenities and preferences</li>
                </ul>

                <h3 class="text-lg font-semibold text-gray-700 mb-2">Usage Data</h3>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>Browser type, IP address, device information</li>
                    <li>Pages visited, time spent, interactions</li>
                    <li>Messages sent within the platform</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">2. How We Use Your Information</h2>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>To create and manage your account</li>
                    <li>To display your profile and listings to other users</li>
                    <li>To calculate compatibility scores between users</li>
                    <li>To facilitate communication between matches</li>
                    <li>To send notifications about matches and messages</li>
                    <li>To improve our services and user experience</li>
                    <li>To ensure safety and prevent fraud</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">3. Information Sharing</h2>
                
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <p class="text-green-800 font-medium">✓ We DO share:</p>
                    <ul class="text-green-700 list-disc pl-6 mt-2 text-sm">
                        <li>Your profile information with other users on the platform</li>
                        <li>Your listings publicly for discovery</li>
                        <li>Contact details only with mutual matches (after both users express interest)</li>
                    </ul>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="text-red-800 font-medium">✗ We DO NOT:</p>
                    <ul class="text-red-700 list-disc pl-6 mt-2 text-sm">
                        <li>Sell your personal data to third parties</li>
                        <li>Share your exact address before matching</li>
                        <li>Use your data for unrelated marketing</li>
                        <li>Share your messages with anyone outside the conversation</li>
                    </ul>
                </div>

                <h2 class="text-xl font-bold text-gray-800 mb-4">4. Data Security</h2>
                <p class="text-gray-600 mb-6">
                    We implement appropriate security measures to protect your personal information:
                </p>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li>Passwords are encrypted using industry-standard hashing</li>
                    <li>All data transmission is encrypted using HTTPS</li>
                    <li>Regular security audits and updates</li>
                    <li>Limited employee access to personal data</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">5. Your Rights</h2>
                <p class="text-gray-600 mb-4">You have the right to:</p>
                <ul class="text-gray-600 mb-6 list-disc pl-6 space-y-2">
                    <li><strong>Access:</strong> Request a copy of your personal data</li>
                    <li><strong>Correct:</strong> Update inaccurate information via your profile</li>
                    <li><strong>Delete:</strong> Request deletion of your account and data</li>
                    <li><strong>Restrict:</strong> Limit how we process your data</li>
                    <li><strong>Object:</strong> Object to certain types of processing</li>
                </ul>

                <h2 class="text-xl font-bold text-gray-800 mb-4">6. Cookies</h2>
                <p class="text-gray-600 mb-6">
                    We use cookies and similar technologies to enhance your experience, analyze usage, 
                    and remember your preferences. You can control cookies through your browser settings.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">7. Data Retention</h2>
                <p class="text-gray-600 mb-6">
                    We retain your data as long as your account is active. When you delete your account, 
                    we will delete your personal information within 30 days, except where we need to 
                    retain it for legal purposes.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">8. Children's Privacy</h2>
                <p class="text-gray-600 mb-6">
                    RoomSaathi is not intended for users under 18 years of age. We do not knowingly 
                    collect information from minors.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">9. Changes to This Policy</h2>
                <p class="text-gray-600 mb-6">
                    We may update this policy periodically. We will notify you of significant changes 
                    via email or platform notification.
                </p>

                <h2 class="text-xl font-bold text-gray-800 mb-4">10. Contact Us</h2>
                <p class="text-gray-600 mb-6">
                    For privacy-related questions or to exercise your rights, contact us at:<br>
                    <a href="mailto:privacy@roomsaathi.com" class="text-primary hover:underline">privacy@roomsaathi.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
