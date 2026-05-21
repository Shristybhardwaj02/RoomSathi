# RoomSathi - Smart Roommate & Property Matching Web App

Find your perfect roommate and property match with RoomSathi, a full-stack property listing and compatibility-based roommate matching web application.

## Project Overview

RoomSathi is a comprehensive web application that connects seekers (looking for rooms) with property owners. The platform includes:

- **User Authentication:** Secure signup, login, and password reset with bcrypt hashing
- **Listing Management:** Browse, post, edit, and search properties with image uploads
- **Smart Matching:** Compatibility algorithm based on 8 lifestyle factors (smoking, drinking, pets, cleanliness, etc.)
- **Messaging System:** Private chat between matched users
- **Admin Dashboard:** Manage users and listings
- **Security:** SQL injection prevention, XSS protection, and secure session management

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3, JavaScript, Tailwind CSS |
| **Backend** | PHP 7.4+ |
| **Database** | MySQL 5.7+ |
| **Server** | Apache (XAMPP for local development) |

## Key Features

✅ User registration and secure authentication  
✅ Post and edit property listings with image uploads  
✅ Browse listings with advanced filtering (city, rent range)  
✅ Compatibility matching algorithm based on lifestyle preferences  
✅ Real-time messaging between matched users  
✅ Admin panel for user and listing management  
✅ Responsive design with modern UI/UX  
✅ Input validation and security hardening  

## Quick Start (Local Setup)

### Prerequisites
- **PHP:** 7.4 or higher
- **MySQL:** 5.7 or higher
- **Apache:** Included with XAMPP (recommended)
- **XAMPP:** Download from [xampp.com](https://www.apachefriends.org/)

### Installation Steps

1. **Clone the repository** to your XAMPP webroot:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/Shristybhardwaj02/RoomSathi.git RoomSathi-1
   cd RoomSathi-1
   ```

2. **Create database:**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create new database named `roomsaathi`
   - Import `sql/roomsaathi.sql` into the database

3. **Configure database credentials:**
   - Copy `includes/config.php.example` to `includes/config.php`
   - Update database credentials:
     ```php
     $db_host = 'localhost';
     $db_user = 'root';
     $db_pass = '';  // Leave blank if no password
     $db_name = 'roomsaathi';
     ```

4. **Start servers:**
   - Open XAMPP Control Panel
   - Click "Start" for Apache and MySQL
   - Open `http://localhost/RoomSathi-1/` in your browser

## Project Structure

```
RoomSathi-1/
├── includes/          # Core PHP files
│   ├── config.php     # Database configuration
│   ├── functions.php  # Business logic & utility functions
│   ├── header.php     # Navigation bar
│   └── footer.php     # Footer template
├── pages/             # Application pages
│   ├── auth/          # Login, signup, password reset
│   ├── listings/      # Browse, post, edit listings
│   ├── matching/      # Roommate matching
│   ├── chat/          # Messaging system
│   ├── admin/         # Admin dashboards
│   └── profile/       # User profile management
├── css/               # Stylesheets
├── js/                # JavaScript files
├── assets/            # Images, icons, logos
├── uploads/           # User-uploaded files
├── sql/               # Database schema
├── index.php          # Homepage
└── README.md          # This file
```

## Database Schema

The application uses a normalized MySQL database with 6 core tables:

- **Users:** User accounts, authentication, profile information
- **Listings:** Property details, rent, amenities, photos
- **Preferences:** Lifestyle preferences for compatibility matching
- **Matches:** Matched listings with compatibility scores
- **Messages:** Chat messages between users
- **Amenities:** Available amenities for listings

## Usage

### For Seekers (Looking for Rooms)
1. Sign up with email and password
2. Complete profile with lifestyle preferences
3. Browse available listings
4. View matched listings based on compatibility
5. Message property owners or matched seekers

### For Property Owners
1. Sign up and select "Property Owner" role
2. Create a new listing with property details
3. Upload property photos
4. View matched seekers
5. Chat with interested users

### Admin Functions
1. Login as admin
2. View all users and listings
3. Approve/reject listings
4. Manage user accounts
5. Generate statistics and reports

## Security Features

- **Password Security:** Passwords hashed with bcrypt (`password_hash()`, `password_verify()`)
- **SQL Injection Prevention:** Parameterized queries with prepared statements
- **XSS Protection:** Input sanitization with `htmlspecialchars()`
- **Session Management:** Secure PHP sessions with timeout
- **File Upload Validation:** Type and size restrictions on images

## Code Examples

### User Registration (Secure Password Handling)
```php
function registerUser($name, $email, $phone, $password) {
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password_hash);
    return $stmt->execute();
}
```

### Sanitization
```php
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
```

## Testing

Recommended testing approach:
- **Unit Tests:** Test individual functions
- **Integration Tests:** Test database operations
- **System Tests:** Test complete user workflows
- **Security Tests:** SQL injection, XSS attack prevention

## Future Enhancements

- Mobile app (React Native / Flutter)
- Video call integration for virtual tours
- Payment gateway integration
- Advanced analytics and reporting
- Machine learning for better matching algorithm
- Two-factor authentication (2FA)

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file for details.

## Author

**Shristy Bhardwaj**

- GitHub: [@Shristybhardwaj02](https://github.com/Shristybhardwaj02)
- Email: 23csma42@kristujayanti.com
- Repository: [RoomSathi](https://github.com/Shristybhardwaj02/RoomSathi)

## Support

For issues, questions, or suggestions, please open an [issue](https://github.com/Shristybhardwaj02/RoomSathi/issues) on GitHub.

---

**Last Updated:** May 2026  
**Status:** Complete - Production Ready

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
>>>>>>> b975654 (Improve README, add LICENSE (MIT) and .gitignore)
