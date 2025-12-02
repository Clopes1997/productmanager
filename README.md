# Product Manager

A Laravel-based product management application with a frontend interface.

**Repository**: https://github.com/Clopes1997/productmanager

## Project Structure

- **Backend**: Laravel API (PHP) - RESTful API for product management
- **Frontend**: Static HTML/CSS/JS in `public/front/` - Single-page application

## How It Works

### Architecture

The application follows a **decoupled architecture**:

1. **Backend (Laravel API)**: 
   - Provides RESTful endpoints for product, brand, and city management
   - Handles business logic, data validation, and database operations
   - Located in `app/Http/Controllers/`
   - API routes defined in `routes/api.php`

2. **Frontend (Static SPA)**:
   - Single-page application using jQuery and Bootstrap
   - Communicates with the backend via AJAX requests
   - Located in `public/front/`
   - Uses configurable API base URL for flexibility

### API Endpoints

- `GET /api/produtos` - List all products
- `GET /api/produtos/{id}` - Get single product
- `POST /api/produtos` - Create product
- `PUT /api/produtos/{id}` - Update product
- `DELETE /api/produtos/{id}` - Delete product (only if stock is 0)
- `GET /api/produtos/filter` - Filter by price range
- `GET /api/produtos/filter/cidade` - Filter by city
- `GET /api/produtos/filter/marca` - Filter by brand
- `GET /api/marcas` - List all brands
- `GET /api/cidades` - List all cities

### Frontend Configuration

The frontend uses a configurable API base URL system:

- Configuration file: `public/front/config.js`
- Can be set via `window.API_BASE_URL` in `index.html`
- Defaults to relative URLs for same-domain deployment

### Database Schema

- **produtos**: Products table (id, nome, valor, estoque, marca_id, cidade_id)
- **marcas**: Brands table (id, nome)
- **cidades**: Cities table (id, nome)

Relationships:
- Product belongs to one Brand (marca_id)
- Product belongs to one City (cidade_id)

## Features

- **Product Management**: Full CRUD operations for products
- **Filtering**: Filter products by:
  - Price range (min/max value)
  - City
  - Brand
- **Statistics**: Automatic calculation of:
  - Total sum of product values
  - Average product value
- **Stock Management**: 
  - Products can only be deleted when stock is 0
  - Stock tracking for inventory management

## Local Development

### Backend Setup

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start development server
php artisan serve
```

The API will be available at `http://localhost:8000`

### Frontend Setup

The frontend can be accessed directly or served locally:

**Option 1: Direct access**
- Open `public/front/index.html` in a browser
- Make sure the backend is running on `http://localhost:8000`

**Option 2: Local server**
```bash
cd public/front
python -m http.server 8000
# Or use PHP: php -S localhost:8000
```

Then open `http://localhost:8000` in your browser.

### CORS Configuration

For cross-origin requests, CORS is configured in `config/cors.php`:
- Allows requests from `*.github.io` domains
- Can be customized for additional domains

## Technology Stack

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: jQuery, Bootstrap 5, Vanilla JavaScript
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Deployment**: Docker-ready (Dockerfile included)

## License

[Your License Here]
