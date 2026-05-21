# RoomSathi

🏠 Find Your Perfect RoomSathi — property & roommate matching web app

Project summary
----------------
RoomSathi is a full‑stack property listing and roommate-matching web application built with PHP and MySQL. It supports user authentication, posting and browsing listings, a compatibility-based roommate matching flow, private messaging, and an admin panel for managing users and listings.

Quick setup (local)
-------------------
Requirements: PHP (7.4+), MySQL, Apache (XAMPP recommended)

1. Clone the repo to your local webroot (e.g., `C:\xampp\htdocs`)
2. Create a MySQL database and import `sql/roomsaathi.sql`
3. Copy `includes/config.php.example` to `includes/config.php` and update DB credentials
4. Start Apache + MySQL, open `http://localhost/RoomSathi-1/` in your browser

Files of interest
-----------------
- `includes/config.php` — DB configuration
- `listings/` — browse/post/edit listing pages
- `auth/` — login, signup, reset-password
- `chat/` — messaging endpoints
- `sql/roomsaathi.sql` — database schema + sample data

Key features
--
- Browse listings with images
- Post and edit listings (with image uploads)
- User authentication (signup/login/reset password)
- Messaging between users (chat)
- Simple matching algorithm for roommate compatibility

Tech stack
--
- PHP (vanilla)
- MySQL (import `sql/roomsaathi.sql`)
- HTML/CSS/JavaScript
- XAMPP / LAMP for local development

Quick start (local)
--
1. Install XAMPP and start Apache + MySQL.
2. Place the project in your web root, e.g. `C:\xampp\htdocs\RoomSathi-1`.
3. Create a MySQL database and import `sql/roomsaathi.sql`.
4. Update database credentials in `includes/config.php`.
5. Open `http://localhost/RoomSathi-1` in your browser.

Contributing
--
Feel free to open issues or pull requests. For major changes, please open an issue first to discuss what you'd like to change.

License
--
This project is available under the MIT License. See `LICENSE` for details.

Contact
--
Project maintained by the author. Link: https://github.com/Shristybhardwaj02/RoomSathi
>>>>>>> b975654 (Improve README, add LICENSE (MIT) and .gitignore)
