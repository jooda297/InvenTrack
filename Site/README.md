# InvenTrack

InvenTrack is an inventory and e-commerce management web application developed as a university project. The system provides separate functionality for customers, sellers, and administrators, allowing products, inventory, orders, and user interactions to be managed through a web-based platform.

## Features

### Customer
- Browse available products
- Search and filter products
- View product details
- Add products to a shopping cart
- Add products to favorites
- Place and view orders
- Rate and review products
- View seller information
- Manage account information

### Seller
- Seller dashboard
- Add and manage products
- Manage product inventory and stock quantities
- View and manage orders
- Update seller profile information
- Monitor product availability

### Administrator
- Administrative dashboard
- Manage users and sellers
- Manage products and categories
- Manage application data and platform activity

## Technologies Used

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Bootstrap
- XAMPP
- Git & GitHub

## Database

InvenTrack uses a MySQL relational database to manage application data including:

- Users
- Products
- Categories and subcategories
- Shopping carts
- Favorites
- Orders and order items
- Product images and options
- Product ratings and feedback
- Seller feedback
- Seller subscriptions
- Advertisements

A database export is included in the repository as:

```text
inventtrack.sql
```

## Local Setup

### Requirements

To run InvenTrack locally, install:

- XAMPP
- PHP
- MySQL
- A modern web browser

### Installation

1. Clone or download this repository.

2. Place the project folder inside the XAMPP `htdocs` directory.

For example on macOS:

```text
/Applications/XAMPP/xamppfiles/htdocs/InvenTrack
```

3. Start **Apache** and **MySQL** from XAMPP.

4. Open phpMyAdmin.

5. Create a database named:

```text
inventtrack
```

6. Import:

```text
inventtrack.sql
```

7. Verify the database configuration in `Connect.php`.

The default local configuration expects a MySQL server running on localhost.

8. Open the application in your browser:

```text
http://localhost/InvenTrack/
```

Depending on your local folder structure, you may need to navigate to the appropriate application page.

## Security

The application uses PHP's built-in password hashing functionality for account passwords.

Passwords are stored using:

```php
password_hash()
```

and verified during authentication using:

```php
password_verify()
```

The database included in this repository contains demonstration data intended for development and portfolio use.

## Project Background

InvenTrack was developed as a university project to apply concepts from web development, database design, information systems, and inventory management.

The project provided practical experience working with PHP and MySQL, designing relational database structures, implementing role-based functionality, and building an interactive web application.

## Future Improvements

Potential improvements include:

- Improved responsive design
- Additional input validation
- Enhanced application security
- Improved inventory analytics
- Order status notifications
- Deployment to a production hosting environment
- Automated testing

## Author

Developed by Jood as a university project.