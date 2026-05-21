<?php
/**
 * RoomSaathi - Contact Us Page
 */
$pageTitle = 'Contact Us';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // In production, you would send an email here
        // For demo, we'll just show success message
        $success = true;
    }
}

require_once '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl text-blue-100">We'd love to hear from you!</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Email Us</h3>
                    <a href="mailto:support@roomsaathi.com" class="text-primary hover:underline">
                        support@roomsaathi.com
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Call Us</h3>
                    <a href="tel:+911234567890" class="text-primary hover:underline">
                        +91 12345 67890
                    </a>
                    <p class="text-gray-500 text-sm mt-1">Mon-Fri, 10am-6pm IST</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Office</h3>
                    <p class="text-gray-600 text-sm">
                        123 Tech Park, HSR Layout<br>
                        Bengaluru, Karnataka 560102
                    </p>
                </div>

                <!-- Social Links -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Send us a Message</h2>

                    <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                        <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-green-800">Message Sent!</h3>
                        <p class="text-green-600 mt-2">Thank you for contacting us. We'll get back to you within 24 hours.</p>
                    </div>
                    <?php else: ?>

                    <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Your Name *</label>
                                <input type="text" name="name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                       placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                                <input type="email" name="email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                       placeholder="john@example.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Subject</label>
                            <select name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feedback">Feedback/Suggestion</option>
                                <option value="report">Report an Issue</option>
                                <option value="partnership">Partnership/Business</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Message *</label>
                            <textarea name="message" rows="5" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="How can we help you?"></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary text-white py-4 rounded-xl font-semibold hover:bg-primary-dark transition-all">
                            Send Message
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
