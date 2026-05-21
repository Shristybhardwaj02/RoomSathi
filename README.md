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
