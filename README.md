# ✈️ Airline Reservation System

A full-featured **online flight booking web application** built with PHP and MySQL, running on XAMPP. The system supports passenger registration, flight search, multi-passenger bookings, and a complete admin panel for managing flights, airlines, airports, and users.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Getting Started](#getting-started)
- [Default Credentials](#default-credentials)
- [Screenshots](#screenshots)
- [Known Limitations](#known-limitations)
- [License](#license)

---

## ✨ Features

### 👤 Customer Side
- **User Registration & Login** – Secure sign-up with MD5-hashed passwords; email used as username
- **Flight Search** – Filter flights by departure airport, destination airport, date, and trip type (one-way / round trip)
- **Flight Listing** – Browse all available upcoming flights with prices, routes, and departure times
- **Multi-Passenger Booking** – Book for multiple passengers in a single transaction with real-time seat availability checks
- **My Bookings** – Authenticated users can view all their past and upcoming bookings
- **Partner Airlines** – Homepage showcases all partner airlines with logo display
- **Responsive UI** – Clean, mobile-friendly layout using Tailwind CSS utility classes

### 🛠️ Admin Panel
- **Secure Admin Login** – Separate login portal for administrators
- **Dashboard** – Central hub for all management tasks
- **Flight Management** – Create, edit, and delete flights with airline, route, datetime, seat count, price, and banner image
- **Airline Management** – Add/edit/delete partner airlines with logo upload
- **Airport Management** – Add/edit/delete airports and their locations
- **Booking Management** – View all booked flights and manage individual bookings
- **User Management** – View and manage registered customer accounts
- **Site Settings** – Configure site name, contact email, phone number, cover image, and about content

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 7.4+ (Procedural + OOP) |
| Database | MySQL / MariaDB (via MySQLi) |
| Frontend | HTML5, Tailwind CSS (CDN), Vanilla JavaScript |
| Admin UI | Bootstrap 4, jQuery, Select2, datetimepicker |
| Server | Apache (XAMPP) |
| DB Tool | phpMyAdmin |

---

## 📁 Project Structure

```
Airline-Reservation-System-PHP-Project-Source-Code/
└── flight/
    ├── index.php               # Main entry point — handles routing & navbar
    ├── home.php                # Landing page (hero, search, flights, partners)
    ├── flights.php             # Flight search results listing
    ├── book.php                # Flight booking form (multi-passenger)
    ├── book_flight.php         # Booking confirmation handler
    ├── my_bookings.php         # Customer's booking history
    ├── login.php               # Customer login page
    ├── signup.php              # Customer registration page
    ├── logout.php              # Session destroy & redirect
    ├── about.php               # About page
    ├── auth.php                # Auth guard helper
    ├── get_fields.php          # AJAX helper for dynamic fields
    ├── header.php              # HTML <head> includes (meta, CSS links)
    ├── footer.php              # Footer HTML + JS includes
    ├── css/
    │   └── styles.css          # Compiled Tailwind CSS
    ├── js/
    │   └── scripts.js          # Custom JavaScript
    ├── assets/
    │   └── img/                # Site images (hero, about, airline logos, route banners)
    ├── database/
    │   └── flight_booking_db.sql   # Full database dump (import this to set up)
    └── admin/
        ├── index.php           # Admin dashboard shell + JS utilities
        ├── login.php           # Admin login page
        ├── admin_class.php     # Core Action class (all CRUD methods)
        ├── ajax.php            # AJAX endpoint dispatcher
        ├── db_connect.php      # Database connection
        ├── home.php            # Admin dashboard home
        ├── airlines.php        # Manage airlines
        ├── airport.php         # Manage airports
        ├── flights.php         # Manage flights
        ├── booked.php          # View all bookings
        ├── manage_flight.php   # Add/edit flight modal form
        ├── manage_booked.php   # Manage individual booking
        ├── manage_user.php     # Manage user modal form
        ├── users.php           # View registered users
        ├── site_settings.php   # Site configuration form
        ├── header.php          # Admin HTML <head> includes
        ├── navbar.php          # Admin sidebar navigation
        └── topbar.php          # Admin top bar
```

---

## 🗄️ Database Schema

The database is named **`flight_booking_db`** and contains the following tables:

| Table | Description |
|---|---|
| `users` | Stores admin and customer accounts (`type`: 1 = Admin, 3 = Customer) |
| `airlines_list` | Partner airlines with name and logo path |
| `airport_list` | Airports with name and city/location |
| `flight_list` | Flights with airline, route, schedule, seat count, and price |
| `booked_flight` | Passenger bookings linked to a flight and optional customer ID |
| `system_settings` | Single-row table for site-wide configuration |

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (PHP 7.4+ & MySQL)
- A web browser

### Installation Steps

1. **Clone or download** this repository into your XAMPP `htdocs` folder:
   ```
   C:\xampp\htdocs\Airline-Reservation-System-PHP-Project-Source-Code\
   ```

2. **Start XAMPP** — make sure **Apache** and **MySQL** are running.

3. **Import the database:**
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Create a new database named `flight_booking_db`
   - Click **Import**, select the file:
     ```
     flight/database/flight_booking_db.sql
     ```
   - Click **Go**

4. **Verify the DB connection** (optional):
   - Open `flight/admin/db_connect.php`
   - Default settings: `host=localhost`, `user=root`, `password=` *(empty)*, `db=flight_booking_db`
   - Adjust if your MySQL credentials differ

5. **Open the application** in your browser:
   ```
   http://localhost/Airline-Reservation-System-PHP-Project-Source-Code/flight/
   ```

6. **Open the admin panel:**
   ```
   http://localhost/Airline-Reservation-System-PHP-Project-Source-Code/flight/admin/
   ```

---

## 🔑 Default Credentials

### Admin Account
| Field | Value |
|---|---|
| Username | `admin` |
| Password | `admin123` |

### Sample Customer Account
| Field | Value |
|---|---|
| Email | `gwilson@sample.com` |
| Password | *(MD5 hashed — register a new account via Sign Up)* |

> **Tip:** It is recommended to register a fresh customer account through the Sign Up page for testing.

---

## ⚠️ Known Limitations

> This is an academic/demo project. Before deploying to a production environment, address the following:

- **Password hashing** — Admin passwords are stored in plain text; customer passwords use MD5 (not bcrypt). Should be upgraded to `password_hash()` / `password_verify()`.
- **SQL Injection** — Some queries use `real_escape_string()` which provides basic protection, but prepared statements are recommended throughout.
- **No HTTPS enforcement** — Add SSL/TLS for production use.
- **File upload validation** — Image uploads should include stricter MIME type and size checks.
- **No email verification** — User registration does not verify email addresses.

---

## 📸 Screenshots

> *(Add screenshots of the homepage, flight search, booking form, and admin dashboard here)*

---

## 👥 Contributors

This project was built and maintained by:

- **[Sunvir Shakib](https://github.com/sunvir-shakib)** (Your GitHub link or name)
- **[Nissan Barua](https://github.com/Nissanbarua)**

We developed this system using a blend of **Vibe Coding** (AI-assisted development) and **Raw Code** to ensure both efficiency and high-quality implementation.

---

## 📄 License

This project is open source and available for educational and personal use.  
Free images used in this project are sourced from Pixelstalk and public image repositories — see `flight/admin/readme.txt` for original links.

---

*Built with PHP · MySQL · Bootstrap · Tailwind CSS · 🚀 Vibe Coding*
