# CISD INSTITUTE — PHP/MySQL Website

A complete, responsive educational institute website using **HTML, CSS, JavaScript, PHP & MySQL**.

## 🚀 Run on XAMPP (localhost)

1. **Install XAMPP** (https://www.apachefriends.org/) and start **Apache** + **MySQL**.
2. **Copy the project** into your XAMPP `htdocs` folder:
   ```
   C:\xampp\htdocs\novaskills\
   ```
3. **Create the database**:
   - Open http://localhost/phpmyadmin
   - Click **Import** → choose `database.sql` → **Go**.
   - This creates the `novaskills` database with `admissions` and `contact_messages` tables.
4. **Open the website**:
   ```
   http://localhost/novaskills/
   ```

## ⚙️ Configuration

Edit `config.php` to change DB credentials or institute details:
```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';        // default for XAMPP
$DB_NAME = 'novaskills';
```

## 📁 File Structure
```
novaskills/
├── index.php          # Home
├── about.php          # About / team
├── courses.php        # All courses
├── admissions.php     # Admission form (saves to DB)
├── gallery.php        # Campus gallery + lightbox
├── contact.php        # Contact form (saves to DB)
├── config.php         # DB connection + site settings
├── database.sql       # Database schema
├── css/style.css      # Styling (Trust Navy + Gold theme)
├── js/script.js       # Mobile nav, scroll reveal, lightbox
├── includes/
│   ├── header.php
│   └── footer.php
├── data/courses.php   # Course catalog
└── images/            # All images
```

## 📬 View submitted admissions & messages
Open phpMyAdmin → `novaskills` database → tables `admissions` / `contact_messages`.

## ✨ Features
- Fully responsive (mobile, tablet, desktop)
- Premium Navy + Gold design system
- Smooth scroll-reveal animations
- Mobile hamburger menu
- WhatsApp floating button
- Gallery lightbox
- Working admission + contact forms (saved to MySQL)
- SEO-friendly meta tags
- Clean, organized, well-commented code
