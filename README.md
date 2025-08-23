# TechDaily Blog

A modern blogging platform built with Laravel, featuring a robust admin dashboard and RESTful API endpoints. Perfect for tech-focused content management with a clean, responsive interface.

## 🌟 Features

### Blog Features

-   📱 Responsive blog layout
-   🔍 Article search functionality
-   🏷️ Category-based organization
-   📊 Popular posts tracking
-   🏷️ Tag cloud functionality
-   📖 Table of contents generation
-   ⏱️ Reading time estimation

### Admin Dashboard

-   🔐 Secure authentication system
-   📝 WYSIWYG editor (TinyMCE) integration
-   📊 Post analytics (views tracking)
-   🖼️ Image upload functionality
-   📋 Draft & published states
-   🎯 Featured posts system
-   🔍 SEO meta management

### API Endpoints

-   `GET /api/posts` - List published posts with pagination
-   `GET /api/posts/{slug}` - Get post details
-   `GET /api/posts/categories` - Get categories with post counts

## 🚀 Tech Stack

-   **Framework:** Laravel 10.x
-   **Database:** MySQL
-   **Frontend:** Bootstrap 5, TailwindCSS
-   **Editor:** TinyMCE
-   **Icons:** Bootstrap Icons
-   **Authentication:** Laravel Breeze

## 📋 Requirements

-   PHP >= 8.1
-   Composer
-   MySQL >= 5.7
-   Node.js & NPM

## 🛠️ Installation

1. Clone the repository

    ```bash
    git clone https://github.com/hanif-alkahfy/techdaily-blog.git
    cd techdaily-blog
    ```

2. Install PHP dependencies

    ```bash
    composer install
    ```

3. Install NPM packages

    ```bash
    npm install
    ```

4. Create and configure .env file

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Configure database in .env

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=techdaily
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Run database migrations and seeders

    ```bash
    php artisan migrate --seed
    ```

7. Link storage directory

    ```bash
    php artisan storage:link
    ```

8. Build assets

    ```bash
    npm run build
    ```

9. Start the development server
    ```bash
    php artisan serve
    ```

## 🗄️ Database Structure

### Users Table

-   id (primary key)
-   name
-   email
-   email_verified_at
-   password
-   remember_token
-   created_at
-   updated_at

### Categories Table

-   id (primary key)
-   name
-   slug
-   description
-   created_at
-   updated_at

### Posts Table

-   id (primary key)
-   user_id (foreign key)
-   category_id (foreign key)
-   title
-   slug
-   excerpt
-   content
-   featured_image
-   status (draft/published)
-   is_featured
-   views
-   meta_title
-   meta_description
-   meta_keywords
-   published_at
-   created_at
-   updated_at

## 🔑 API Documentation

### List Posts

```http
GET /api/posts
```

Query Parameters:

-   `category` (string, optional) - Filter by category slug
-   `search` (string, optional) - Search in title and content
-   `page` (integer, optional) - Page number for pagination

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Sample Post",
            "slug": "sample-post",
            "excerpt": "Post excerpt...",
            "content": "Full post content...",
            "featured_image": "url/to/image.jpg",
            "status": "published",
            "published_at": "2025-08-23T10:00:00Z",
            "created_at": "2025-08-23T10:00:00Z",
            "updated_at": "2025-08-23T10:00:00Z",
            "category": {
                "id": 1,
                "name": "Tutorial",
                "slug": "tutorial"
            },
            "author": {
                "id": 1,
                "name": "John Doe"
            },
            "meta": {
                "title": "SEO Title",
                "description": "Meta description",
                "keywords": "keywords"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50
    }
}
```

### Get Single Post

```http
GET /api/posts/{slug}
```

Response:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Sample Post"
        // ... same structure as above
    }
}
```

### Get Categories

```http
GET /api/posts/categories
```

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Tutorial",
            "slug": "tutorial",
            "post_count": 15
        }
    ]
}
```

## 🔒 Security

-   Authentication using Laravel's built-in mechanisms
-   CSRF protection for all forms
-   XSS protection
-   SQL injection prevention
-   File upload validation and sanitization

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Hanif Al-Kahfi**

-   GitHub: [@hanif-alkahfy](https://github.com/hanif-alkahfy)

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com)
-   [Bootstrap](https://getbootstrap.com)
-   [TinyMCE](https://www.tiny.cloud)
