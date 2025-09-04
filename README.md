# My Images & Gallery App

A Laravel & Filament application to upload, view, favorite, and download images. Integrates the Art Institute of Chicago API for a gallery. Supports search, pagination, click-to-enlarge images with Alpine.js, and file validation up to 6MB.

---

## Features

- Upload personal images with title and description  
- View uploaded images in a responsive grid  
- Favorite and unfavorite images  
- Download images  
- Search and pagination for easy navigation  
- Gallery page fetching images from Art Institute of Chicago API  
- Click-to-enlarge images using Alpine.js  

---

## Installation (Laragon)

1. **Clone the repository**  

```bash
git clone https://github.com/your-username/Laravel-API-Gallery.git
cd Laravel-API-Gallery
```

2. Install dependencies

```bash
composer install
npm install
npm run build
```


3. Set up environment file

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database


Open Laragon > Menu > MySQL > Adminer or phpMyAdmin

Create a new database (e.g., my_images_db)

Update .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_images_db
DB_USERNAME=root
DB_PASSWORD=   # leave empty if Laragon default


5. Run migrations
```bash
php artisan migrate
```

6. Link storage
```bash
php artisan storage:link
```

## Running the App

Make sure Apache/Nginx and MySQL are running in Laragon

Start the Laravel server:

```bash
php artisan serve
```


Open your browser and go to: http://127.0.0.1:8000/admin, initializing a user helps to log in directly, you can use: php artisan make:filament-user command.


## Usage

- Upload images via My Images page

- Favorite/unfavorite images

- Download any image using the Download link

- Browse gallery images fetched from the Art Institute of Chicago API

- Click images to enlarge them

## Notes

Max upload file size: 6 MB

Gallery images from the API are automatically displayed

Responsive design with Alpine.js animations
