# UbuntuTrade – C2C E-Commerce for South Africa
## Author
Yomelela Nkuna EDUV4945640


**Live Demo:** https://ubuntutrade.page.gd

## Project Overview
UbuntuTrade is a Consumer-to-Consumer (C2C) platform designed for South Africa's informal economy. It allows buyers and sellers to trade directly with features like:

- Product listings (add, edit, delete)
- Shopping cart & checkout
- Secure payment via PayFast Sandbox
- User reviews and ratings
- Seller verification (ID number approval)
- Seller dashboard with order management
- Admin panel with Role-Based Access Control (RBAC)

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP 
- **Database:** MySQL 
- **Payment:** PayFast Sandbox API
- **Hosting:** InfinityFree

## Project Structure
ubuntutrade/
├── admin/ # Admin panel pages
├── css/ # Stylesheets
├── js/ # JavaScript files
├── php/ # Backend PHP scripts
├── seller/ # Seller dashboard pages
├── sql/ # Database schema
├── uploads/ # Product images (not included)
├── index.html # Homepage
├── product.html # Product details
├── cart.html # Shopping cart
├── checkout.html # Checkout page
├── login.html # Login page
└── register.html # Registration page


## Installation (for local development)
1. Clone this repository
2. Import `sql/ubuntutrade.sql` into your MySQL database
3. Copy `php/payfast_config.sample.php` to `php/payfast_config.php` and fill in your PayFast credentials
4. Update `php/db_connect.php` with your database details
5. Run on a PHP-enabled server (XAMPP, WAMP, or live host)

## Default Admin Credentials
- **Email:** admin@ubuntutrade.co.za
- **Password:** admin123

## Features
- User registration (buyer / seller)
- Secure login with session management
- Product creation, editing, deletion (sellers only)
- Shopping cart (session-based)
- Checkout with PayFast payment simulation (Sandbox)
- User reviews & ratings
- Seller dashboard (view orders, mark shipped)
- Seller verification (request & admin approval)
- Admin dashboard with RBAC:
  - Manage users (change roles, delete)
  - Manage roles (add, edit, delete)
  - Manage listings (update status, delete)
  - Review verification requests


