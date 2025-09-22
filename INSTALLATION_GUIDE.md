# Espee E-Commerce Installation Guide

## Requirements
- PHP 8.2 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM

## Installation Steps

### 1. Clone/Download the Project
```bash
cd /path/to/your/project
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Setup
The `.env` file is already configured. Update the database credentials if needed:
```
DB_DATABASE=espee_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Create Database
Create a MySQL database named `espee_ecommerce`

### 6. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

This will create all tables and populate them with dummy data.

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Install NPM Dependencies (Optional)
```bash
npm install
npm run build
```

### 9. Start the Development Server
```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Admin Credentials
- **Email:** admin@espee.com
- **Password:** password

## Features Implemented

### Frontend
- ✅ Homepage with featured products and categories
- ✅ Product listing with AJAX filtering (category, shape, color, price)
- ✅ Product detail page with SKU selection
- ✅ Shopping cart with session storage
- ✅ Checkout process
- ✅ Order confirmation page
- ✅ Responsive design (Bootstrap 5)
- ✅ Black & white theme

### Admin Panel
- ✅ Dashboard with statistics
- ✅ Categories management
- ✅ Subcategories management  
- ✅ Shapes management
- ✅ Colors management
- ✅ Products management
- ✅ Product variants (SKUs) management
- ✅ Orders management with status updates
- ✅ Low stock alerts

### Database Structure
- ✅ Categories & Subcategories
- ✅ Shapes & Colors
- ✅ Products with multiple variants (SKUs)
- ✅ Orders & Order Items
- ✅ Proper relationships and indexes

### Dummy Data
- 5 Categories with subcategories
- 6 Shapes
- 6 Colors  
- 20 Products (10 Sunglasses + 10 Frames)
- Each product has 5-10 SKUs with different colors
- Random stock levels and price variations

## Key URLs
- **Homepage:** http://localhost:8000
- **All Products:** http://localhost:8000/products
- **Sunglasses:** http://localhost:8000/products?category=sunglasses
- **Frames:** http://localhost:8000/products?category=frames
- **Admin Login:** http://localhost:8000/admin/login
- **Admin Dashboard:** http://localhost:8000/admin/dashboard

## Testing the Application

1. **Browse Products:** Visit the homepage and browse products
2. **Filter Products:** Use the filters on the products page
3. **View Product Details:** Click on any product to see variants
4. **Add to Cart:** Select a color variant and add to cart
5. **Checkout:** Complete a test order
6. **Admin Panel:** Login with admin credentials and manage products

## Notes
- Product images are placeholders from placeholder.com
- This is a demo application - no real payment processing
- All data is stored in session/database
- The application follows Laravel best practices