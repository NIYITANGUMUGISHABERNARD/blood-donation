# Blood Donation Platform 🩸

A modern, full-featured blood donation management system built with PHP, MySQL, and Tailwind CSS.

## ✨ Features

### For Donors

- 🔐 User registration and authentication
- 💉 Submit blood donation records
- 📊 Track donation history and status
- 📈 View personal statistics
- ✅ Real-time approval notifications

### For Recipients

- 🩸 Request blood based on type and quantity
- 📋 Track request status
- 📊 View request history
- ✅ Get notified when requests are approved

### For Administrators

- 👥 Manage all donations and requests
- ✅ Approve or reject submissions
- 📊 View comprehensive statistics
- 🏥 Monitor blood inventory levels
- 📝 Access system-wide analytics

### General Features

- 🌐 Public blood inventory viewer
- 📱 Fully responsive design (mobile, tablet, desktop)
- 🎨 Modern dark-themed UI
- ⚡ Fast and lightweight
- 🔒 Secure authentication system

## 🎨 Design

The platform features a modern, professional design with:

- Dark theme (gray-900/gray-800 palette)
- Gradient buttons and accents
- Smooth animations and transitions
- Responsive sidebar navigation
- Beautiful data visualization
- Font Awesome icons
- Tailwind CSS utilities

## 🚀 Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server (XAMPP recommended)
- Modern web browser

### Installation

1. **Clone or download the project**

   ```bash
   cd c:\xampp\htdocs\projects\
   # Project should be in blood-donation folder
   ```

2. **Set up the database**

   - Create a new MySQL database named `blood_donation_db`
   - Import the schema from `db/schema.sql`

   ```sql
   CREATE DATABASE blood_donation_db;
   USE blood_donation_db;
   SOURCE db/schema.sql;
   ```

   To load optional demo data after importing the schema:

   ```sql
   SOURCE db/seed.sql;
   ```

   Demo logins:

   - Admin: `admin@example.com` / `admin123`
   - Staff: `staff@example.com` / `staff123`
   - Donor: `donor@example.com` / `donor123`
   - Recipient: `recipient@example.com` / `recipient123`

3. **Configure database connection**

   - Open `db.php`
   - Update credentials if needed (default: root with no password)

   ```php
   $db_host = 'localhost';
   $db_user = 'root';
   $db_pass = '';
   $db_name = 'blood_donation_db';
   ```

4. **Start your server**

   - For XAMPP: Start Apache and MySQL
   - Navigate to `http://localhost/projects/blood-donation/`

5. **Create admin account**
   - Register a new user via the registration page
   - Manually update the user's `user_type` to `Admin` in the database

## 📁 Project Structure

```
blood-donation/
├── assets/
│   ├── css/
│   │   └── custom.css          # Custom styles and animations
│   └── js/
│       ├── main.js             # Main JavaScript
│       └── dashboard.js        # Dashboard functionality
├── db/
│   └── schema.sql              # Database schema
├── image/                      # Uploaded images directory
├── admin_actions.php           # Admin management panel
├── dashboard.php               # User dashboard
├── db.php                      # Database configuration
├── donate.php                  # Donation submission form
├── footer.php                  # Footer template
├── header.php                  # Header and navigation
├── index.php                   # Landing page
├── inventory.php               # Blood inventory viewer
├── login.php                   # Login page
├── logout.php                  # Logout handler
├── my_donations.php            # Donor's donation history
├── my_requests.php             # Recipient's request history
├── register.php                # Registration page
├── requests.php                # Blood request form
├── README.md                   # This file
├── STYLING_UPDATES.md          # Design documentation
└── STYLING_REFERENCE.md        # Styling guide for developers
```

## 👥 User Roles

### Donor

- Submit blood donations
- View personal donation history
- Access blood inventory
- Track donation status (Pending/Approved/Rejected)

### Recipient

- Submit blood requests
- View personal request history
- Access blood inventory
- Track request status

### Admin

- Manage user accounts and assign roles
- View system reports and operational activity

### Staff

- Approve/reject donations (updates inventory automatically)
- Approve/reject blood requests (checks inventory availability)
- Monitor pending actions and blood inventory

## 🎯 Key Pages

| Page         | Route               | Access        | Description                              |
| ------------ | ------------------- | ------------- | ---------------------------------------- |
| Landing      | `index.php`         | Public        | Homepage with features and statistics    |
| Login        | `login.php`         | Public        | User authentication                      |
| Register     | `register.php`      | Public        | New user registration                    |
| Dashboard    | `dashboard.php`     | Authenticated | Role-based dashboard with statistics     |
| Donate       | `donate.php`        | Donor         | Blood donation submission form           |
| Request      | `requests.php`      | Recipient     | Blood request submission form            |
| My Donations | `my_donations.php`  | Donor         | Personal donation history with stats     |
| My Requests  | `my_requests.php`   | Recipient     | Personal request history with stats      |
| Inventory    | `inventory.php`     | All           | Public blood inventory with stock levels |
| Admin Panel  | `admin_actions.php` | Admin         | Complete admin management interface      |

## 🗄️ Database Schema

### Users Table

- `id` - Primary key (auto increment)
- `full_name` - User's full name
- `email` - Login email (unique)
- `password` - Hashed password
- `phone` - Contact number
- `gender` - User gender (Male/Female/Other)
- `blood_type` - User's blood type (A+, A-, B+, B-, AB+, AB-, O+, O-)
- `address` - User address
- `user_type` - Role (Donor/Recipient/Admin)
- `created_at` - Registration timestamp

### Donations Table

- `id` - Primary key
- `donor_id` - Foreign key to users table
- `blood_type` - Donated blood type
- `quantity_ml` - Quantity in milliliters
- `date_donated` - Date of donation
- `status` - Status (Pending/Approved/Rejected)
- `created_at` - Submission timestamp
- `updated_at` - Last update timestamp

### Requests Table

- `id` - Primary key
- `recipient_id` - Foreign key to users table
- `blood_type` - Requested blood type
- `quantity_ml` - Quantity needed in milliliters
- `status` - Status (Pending/Approved/Rejected)
- `fulfilled_by_donation_id` - Optional link to donation
- `created_at` - Request timestamp
- `updated_at` - Last update timestamp

### Blood Inventory Table

- `id` - Primary key
- `blood_type` - Blood type (A+, A-, B+, B-, AB+, AB-, O+, O-)
- `quantity_ml` - Current stock level in milliliters
- `updated_at` - Last inventory update

### Admin Logs Table

- `id` - Primary key
- `admin_id` - Foreign key to users table
- `action` - Action description/log message
- `created_at` - Log timestamp

## 🔒 Security Features

- ✅ Password hashing using `password_hash()` with bcrypt
- ✅ SQL injection prevention with `real_escape_string()`
- ✅ Session-based authentication
- ✅ Role-based access control (RBAC)
- ✅ Protected admin routes
- ✅ Input validation on forms
- ⚠️ Recommended: Add CSRF tokens for production

## 🎨 Styling System

The platform uses:

- **Tailwind CSS 3.x** via CDN for utility-first styling
- **Font Awesome 6.4.0** for comprehensive icon library
- **Custom CSS** (`assets/css/custom.css`) for animations and effects
- **Responsive design** with mobile-first approach
- **Dark theme** with gray-950/900/800 color palette

### Color Scheme

- **Background**: Gray-950 (darkest)
- **Cards**: Gray-800 with Gray-700 borders
- **Primary**: Red gradient (600-700)
- **Success**: Green (600-700)
- **Warning**: Yellow (600-700)
- **Error**: Red (600-700)
- **Info**: Blue (600-700)

See `STYLING_REFERENCE.md` for detailed styling guidelines and component examples.

## 💡 Usage Examples

### As a Donor

1. Register with email and password
2. Select "Donor" as user type
3. Login to your dashboard
4. Click "Donate Blood" to submit a donation
5. View your donation history in "My Donations"
6. Wait for admin approval

### As a Recipient

1. Register with email and password
2. Select "Recipient" as user type
3. Login to your dashboard
4. Click "Request Blood" to submit a request
5. View your request history in "My Requests"
6. Get notified when request is approved

### As an Admin

1. Get admin access (manual database update required)
2. Login to admin dashboard
3. View pending donations and requests
4. Approve donations (automatically updates inventory)
5. Approve requests (checks inventory availability)
6. Monitor blood stock levels

## 🐛 Known Issues & Future Improvements

### Current Limitations

- Admin accounts must be created manually in database
- No email notifications system
- No file upload validation for profile pictures
- Basic error handling

### Planned Features

- [ ] Email/SMS notifications for status updates
- [ ] Advanced search and filtering
- [ ] Data export (CSV/PDF)
- [ ] Charts and visual analytics
- [ ] User profile editing
- [ ] Password reset functionality
- [ ] Blood donation appointment scheduling
- [ ] Donation reminders for eligible donors
- [ ] Mobile app version
- [ ] Multi-language support
- [ ] CSRF protection
- [ ] Rate limiting

## 📝 Development

### Adding New Features

1. Follow the existing code structure and patterns
2. Use consistent styling from `STYLING_REFERENCE.md`
3. Test across different user roles (Donor/Recipient/Admin)
4. Ensure mobile responsiveness
5. Add appropriate comments and documentation

### Code Style Guidelines

- Use consistent indentation (2 or 4 spaces)
- Add comments for complex logic
- Follow PHP PSR standards where applicable
- Keep files organized and modular
- Use meaningful variable and function names
- Sanitize all user inputs
- Use prepared statements for complex queries (recommended upgrade)

### Testing Checklist

- ✅ Test registration and login flows
- ✅ Test each user role separately
- ✅ Verify form validation
- ✅ Check responsive design on mobile/tablet
- ✅ Test approval/rejection workflows
- ✅ Verify inventory updates correctly
- ✅ Check flash messages display
- ✅ Test logout functionality

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📧 Support & Documentation

- **Styling Guide**: See `STYLING_REFERENCE.md`
- **Design Updates**: See `STYLING_UPDATES.md`
- **Issues**: Open an issue in the repository
- **Questions**: Contact the development team

## 📜 License

This project is open source and available for educational purposes.

## 🙏 Acknowledgments

- Tailwind CSS for the amazing utility-first CSS framework
- Font Awesome for the comprehensive icon library
- The blood donation community for inspiration

---

**Built with ❤️ for saving lives through blood donation**

_One donation can save up to three lives. Be a hero today!_

Last Updated: November 2026
