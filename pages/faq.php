<?php
/**
 * RoomSaathi - FAQ Page
 */
$pageTitle = 'Frequently Asked Questions';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

$faqs = [
    [
        'category' => 'Getting Started',
        'questions' => [
            [
                'q' => 'What is RoomSaathi?',
                'a' => 'RoomSaathi is a roommate matching platform that helps you find compatible roommates based on your lifestyle preferences. Think of it as Tinder, but for finding the perfect roommate!'
            ],
            [
                'q' => 'Is RoomSaathi free to use?',
                'a' => 'Yes! Creating a profile, browsing listings, and messaging matches is completely free. We may introduce premium features in the future.'
            ],
            [
                'q' => 'How do I create an account?',
                'a' => 'Click "Sign Up" on the homepage, enter your details, verify your phone via OTP, and complete your profile with your lifestyle preferences.'
            ],
        ]
    ],
    [
        'category' => 'Finding Roommates',
        'questions' => [
            [
                'q' => 'How does the matching work?',
                'a' => 'Our algorithm compares 8 lifestyle factors (smoking, drinking, food preference, pets, sleep schedule, cleanliness, guests, personality) between users. The more similar your preferences, the higher your compatibility score!'
            ],
            [
                'q' => 'What happens when I "like" a listing?',
                'a' => 'When you like a listing, the listing owner is notified. If they also express interest in you (mutual match), you can start chatting directly.'
            ],
            [
                'q' => 'Can I filter listings by location?',
                'a' => 'Yes! You can filter listings by city, locality, rent range, room type, and gender preference.'
            ],
        ]
    ],
    [
        'category' => 'Posting Listings',
        'questions' => [
            [
                'q' => 'How do I post a room listing?',
                'a' => 'Go to Dashboard → Post a Listing. Fill in details like title, rent, location, amenities, and upload photos. Your listing will be visible to all users.'
            ],
            [
                'q' => 'Can I edit or delete my listing?',
                'a' => 'Yes! Go to "My Listings" to edit details, change status (active/inactive/rented), or delete your listing.'
            ],
            [
                'q' => 'How many listings can I post?',
                'a' => 'Currently, there\'s no limit on the number of listings you can post.'
            ],
        ]
    ],
    [
        'category' => 'Safety & Privacy',
        'questions' => [
            [
                'q' => 'Is my information safe?',
                'a' => 'We take privacy seriously. Your contact details are only shared after a mutual match. All passwords are encrypted and we never share data with third parties.'
            ],
            [
                'q' => 'How do I report suspicious activity?',
                'a' => 'Use the "Report" button on any profile or listing. Our team reviews reports within 24 hours.'
            ],
            [
                'q' => 'Are profiles verified?',
                'a' => 'All users verify their phone number via OTP. We recommend meeting in public places first before visiting any room.'
            ],
        ]
    ],
    [
        'category' => 'Account',
        'questions' => [
            [
                'q' => 'How do I reset my password?',
                'a' => 'Click "Forgot Password" on the login page. You\'ll receive a reset link via email.'
            ],
            [
                'q' => 'Can I delete my account?',
                'a' => 'Yes. Go to Settings → Danger Zone → Delete Account. This will permanently remove all your data.'
            ],
            [
                'q' => 'How do I change my email or phone?',
                'a' => 'Currently, email cannot be changed. Phone can be updated from Edit Profile page. Re-verification may be required.'
            ],
        ]
    ]
];
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Frequently Asked Questions</h1>
            <p class="text-xl text-blue-100">Find answers to common questions about RoomSaathi</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-12">
        <!-- Search -->
        <div class="bg-white rounded-2xl shadow-lg p-4 mb-8">
            <div class="relative">
                <input type="text" id="faqSearch" placeholder="Search FAQs..."
                       class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <?php foreach ($faqs as $category): ?>
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4"><?php echo $category['category']; ?></h2>
            <div class="space-y-3">
                <?php foreach ($category['questions'] as $index => $faq): ?>
                <div class="faq-item bg-white rounded-xl shadow">
                    <button class="faq-trigger w-full text-left p-4 flex items-center justify-between" 
                            onclick="toggleFaq(this)">
                        <span class="font-medium text-gray-800 faq-question"><?php echo $faq['q']; ?></span>
                        <svg class="w-5 h-5 text-gray-400 faq-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content hidden px-4 pb-4">
                        <p class="text-gray-600 faq-answer"><?php echo $faq['a']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Still Have Questions -->
        <div class="bg-primary-light rounded-2xl p-8 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Still Have Questions?</h3>
            <p class="text-gray-600 mb-4">We're here to help! Reach out to our support team.</p>
            <a href="<?php echo SITE_URL; ?>/pages/contact.php" 
               class="inline-block bg-primary text-white px-6 py-3 rounded-xl hover:bg-primary-dark transition-all">
                Contact Us
            </a>
        </div>
    </div>
</div>

<script>
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Search functionality
document.getElementById('faqSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const items = document.querySelectorAll('.faq-item');
    
    items.forEach(item => {
        const question = item.querySelector('.faq-question').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = searchTerm === '' ? 'block' : 'none';
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
