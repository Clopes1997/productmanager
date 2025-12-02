# Product Manager

A Laravel-based product management application with a frontend that can be deployed to GitHub Pages.

**Repository**: https://github.com/Clopes1997/productmanager

## Project Structure

- **Backend**: Laravel API (PHP) - **Must be hosted separately** (GitHub Pages cannot run PHP)
- **Frontend**: Static HTML/CSS/JS in `public/front/` - **Can be hosted on GitHub Pages**

## ⚠️ Important: GitHub Pages Limitation

**GitHub Pages can only host static files** (HTML, CSS, JavaScript). It **cannot** run PHP or Laravel applications.

You need to:
1. **Host the frontend** on GitHub Pages (already configured ✅)
2. **Host the Laravel API** separately on a PHP hosting service (see [API_HOSTING.md](API_HOSTING.md))

## GitHub Pages Deployment (Frontend Only)

This application is configured to deploy the frontend to GitHub Pages automatically.

### Prerequisites

1. Your repository must be public (or you need GitHub Pro/Team for private repos with Pages)
2. GitHub Pages must be enabled in your repository settings

### Setup Instructions

1. **Enable GitHub Pages**:
   - Go to your repository Settings → Pages
   - Under "Source", select "GitHub Actions"
   - Save the settings

2. **Configure API Base URL (Required)**:
   - Since GitHub Pages cannot host your Laravel API, you **must** host it separately
   - See [API_HOSTING.md](API_HOSTING.md) for hosting options (Render, Railway, Fly.io, etc.)
   - Once your API is deployed, go to your repository Settings → Secrets and variables → Actions
   - Add a new secret named `API_BASE_URL` with your API URL (e.g., `https://productmanager-api.onrender.com`)
   - The frontend will automatically use this API URL when deployed

3. **Deploy**:
   - The workflow will automatically run on pushes to the `main` branch
   - You can also manually trigger it from the Actions tab → "Deploy to GitHub Pages" → "Run workflow"
   - After deployment, your site will be available at: `https://[username].github.io/[repository-name]/`

### Manual Deployment

If you prefer to deploy manually:

1. Copy the contents of `public/front/` to a `docs/` folder in your repository root
2. Enable GitHub Pages to serve from the `docs/` folder
3. Update the API base URL in `docs/index.html` if needed

### API Configuration

The frontend uses a configurable API base URL system:

- **For GitHub Pages with separate API**: Set the `API_BASE_URL` secret in GitHub Actions
- **For same-domain deployment**: Leave `API_BASE_URL` empty to use relative URLs
- **For local development**: The frontend will use relative URLs by default

The API base URL can be configured in:
- `public/front/config.js` - Configuration file
- `public/front/index.html` - Can be set via `window.API_BASE_URL`

### CORS Configuration

Since your API will be on a different domain than GitHub Pages, CORS is already configured:

- `config/cors.php` is set up to allow `*.github.io` domains
- Your GitHub Pages site will be: `https://clopes1997.github.io/productmanager`
- The API will automatically allow requests from this domain

If you need to add more domains, edit `config/cors.php`.

### Local Development

1. **Backend**:
   ```bash
   composer install
   php artisan serve
   ```

2. **Frontend**:
   - Open `public/front/index.html` in a browser
   - Or serve it with a local server:
     ```bash
     cd public/front
     python -m http.server 8000
     ```

## Quick Start

**🚀 See [QUICK_START.md](QUICK_START.md) for step-by-step instructions!**

### Summary:
1. **Deploy API** to Render.com (free) - see [QUICK_START.md](QUICK_START.md)
2. **Enable GitHub Pages** and add `API_BASE_URL` secret
3. **Initialize database** with migrations
4. **Access**: https://clopes1997.github.io/productmanager

## Features

- Product management (CRUD operations)
- Filter products by value, city, and brand
- Calculate average and sum of product values
- Stock management

## Documentation

- [API_HOSTING.md](API_HOSTING.md) - Guide for hosting the Laravel API
- [DEPLOYMENT.md](DEPLOYMENT.md) - Detailed deployment instructions

## License

[Your License Here]
