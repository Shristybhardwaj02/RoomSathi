# RoomSathi

🏠 Find Your Perfect RoomSathi — property & roommate matching web app

Project summary
----------------
RoomSathi is a full‑stack property listing and roommate-matching web application built with PHP and MySQL. It supports user authentication, posting and browsing listings, a compatibility-based roommate matching flow, private messaging, and an admin panel for managing users and listings.

Resume-friendly bullets
----------------------
- **Role:** Full-stack developer — designed and implemented frontend, backend, and database.
- **Tech stack:** PHP, MySQL, JavaScript, HTML/CSS, Bootstrap, XAMPP
- **Key features:** user signup/login, create/edit/listings, search & filters, roommate matching algorithm, messaging, admin dashboard, image uploads
- **What I delivered:** implemented end-to-end CRUD flows, integrated secure file uploads, designed normalized database schema, and built a simple matching algorithm for lifestyle compatibility.

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

Contributing / Resume usage
---------------------------
Feel free to link this repository on your resume as: https://github.com/Shristybhardwaj02/RoomSathi
Use the **Resume-friendly bullets** above directly on your CV under this project entry.

License
-------
This project is released under the MIT License. See `LICENSE` for details.

Screenshot
----------
Add a project screenshot to showcase the UI. Place an image at `assets/images/screenshot.png` and add or replace the line below:

![RoomSathi screenshot](assets/images/screenshot.png)

Live demo / Deployment notes
---------------------------
- GitHub Pages only serves static sites (HTML/CSS/JS). Since RoomSathi is PHP/MySQL, GitHub Pages cannot run the dynamic app.
- For a live demo consider deploying to a PHP-capable host (shared hosting, Render, Railway, or a VPS). Example quick hosts:
	- Render (free tier for static / paid web services) — supports Docker or static only for free tier
	- Deploy to a LAMP-compatible shared hosting or use a small VPS and install XAMPP/LAMP

Quick deploy alternative (static demo):
- If you can extract the frontend pages (HTML/CSS/JS) into a static preview, push them to a `gh-pages` branch and enable GitHub Pages to show a static UI preview. This will not include backend functionality like signup or messaging.

Enable GitHub Pages (static preview only)
--------------------------------------
1. Create a branch `gh-pages` containing only the static assets (or a `docs/` folder) and push it.
2. On GitHub: `Settings` → `Pages` → choose `gh-pages` or `main/docs` and save.
3. GitHub will provide a URL like `https://Shristybhardwaj02.github.io/RoomSathi/`.

Example code snippet (DB connection)
----------------------------------
Add this as a short example in `includes/config.php.example` to show credential usage on your resume (don't commit real credentials):

```php
<?php
// includes/config.php.example
$db_host = 'localhost';
$db_user = 'db_user';
$db_pass = 'db_pass';
$db_name = 'roomsaathi';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
}
?>
```

If you'd like, I can prepare a `gh-pages` static preview (extract frontend HTML/CSS) and push it to a `gh-pages` branch for a hosted UI snapshot.
--
RoomSathi is a PHP/MySQL web application for browsing and posting room/listing ads and matching users based on preferences.

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

Notes
--
- Ensure the `uploads/` folders are writable by the web server.
- Add any environment secrets (API keys) to a local `.env` and do NOT commit it.

Contributing
--
Feel free to open issues or pull requests. For major changes, please open an issue first to discuss what you'd like to change.

License
--
This project is available under the MIT License. See `LICENSE` for details.

Contact
--
Project maintained by the author. Link: https://github.com/Shristybhardwaj02/RoomSathi
