# TechDaily Blog

A modern, feature-rich blogging platform built with Laravel, designed specifically for technical content creators. TechDaily provides a comprehensive admin dashboard, RESTful API, and responsive user interface optimized for tech-focused publications.

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Database Architecture](#database-architecture)
- [API Reference](#api-reference)
- [Security Features](#security-features)
- [Contributing](#contributing)
- [License](#license)

## Overview

TechDaily Blog is a production-ready blogging platform that combines the power of Laravel's backend architecture with modern frontend technologies. Built with scalability and performance in mind, it offers content creators and developers a robust foundation for technical blogging and content management.

## Key Features

### Content Management
- **Advanced Editor**: Integrated TinyMCE WYSIWYG editor with code syntax highlighting
- **Content Organization**: Hierarchical categories and flexible tagging system
- **Draft Management**: Save drafts and schedule publication dates
- **SEO Optimization**: Built-in meta tags, Open Graph, and schema markup
- **Media Management**: Secure image upload with automatic optimization
- **Featured Content**: Highlight important posts with featured status

### Reader Experience
- **Responsive Design**: Mobile-first approach with Bootstrap 5 and TailwindCSS
- **Advanced Search**: Full-text search across titles, content, and tags
- **Reading Analytics**: Automatic reading time estimation and view tracking
- **Navigation**: Dynamic table of contents generation
- **Content Discovery**: Popular posts tracking and tag cloud visualization

### Administrative Tools
- **Secure Dashboard**: Role-based access control with Laravel Breeze
- **Analytics Integration**: Post performance tracking and user engagement metrics
- **Content Workflow**: Draft, review, and publish workflow management
- **User Management**: Multi-author support with permission controls

### Developer Features
- **RESTful API**: Comprehensive API endpoints for headless implementations
- **Documentation**: Auto-generated API documentation
- **Extensibility**: Plugin-friendly architecture with Laravel hooks
- **Performance**: Optimized database queries and caching strategies

## Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| **Backend Framework** | Laravel | 10.x |
| **Database** | MySQL | 5.7+ |
| **Frontend Framework** | Bootstrap | 5.x |
| **CSS Framework** | TailwindCSS | 3.x |
| **Rich Text Editor** | TinyMCE | Latest |
| **Icons** | Bootstrap Icons | Latest |
| **Authentication** | Laravel Breeze | Latest |
| **Package Manager** | Composer | 2.x |
| **Asset Management** | Vite | 4.x |

## System Requirements

### Minimum Requirements
- **PHP**: 8.1 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: 512MB RAM (1GB+ recommended)
- **Storage**: 1GB available disk space

### Development Requirements
- **Node.js**: 16.x or higher
- **NPM**: 8.x or higher
- **Composer**: 2.x or higher
- **Git**: For version control

### Recommended Production Environment
- **PHP**: 8.2+
- **Memory**: 2GB+ RAM
- **Database**: MySQL 8.0+ with InnoDB storage engine
- **Caching**: Redis or Memcached
- **Web Server**: Nginx with PHP-FPM

## Installation Guide

### Quick Start

1. **Clone the Repository**
   ```bash
   git clone https://github.com/hanif-alkahfy/techdaily-blog.git
   cd techdaily-blog
   ```

2. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install --optimize-autoloader --no-dev
   
   # Install Node.js dependencies
   npm ci
   ```

3. **Environment Configuration**
   ```bash
   # Copy environment configuration
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Edit your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=techdaily_blog
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Setup**
   ```bash
   # Run migrations and seed data
   php artisan migrate --seed
   
   # Link storage directory
   php artisan storage:link
   ```

6. **Asset Compilation**
   ```bash
   # Development build
   npm run dev
   
   # Production build
   npm run build
   ```

7. **Launch Application**
   ```bash
   # Start development server
   php artisan serve
   
   # Application will be available at http://localhost:8000
   ```

### Production Deployment

For production deployment, additional steps are recommended:

```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper file permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Database Architecture

### Entity Relationship Overview

The application uses a normalized database structure with the following core entities:

#### Users Table
| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key, auto-increment |
| `name` | VARCHAR(255) | User's display name |
| `email` | VARCHAR(255) | Unique email address |
| `email_verified_at` | TIMESTAMP | Email verification timestamp |
| `password` | VARCHAR(255) | Hashed password |
| `remember_token` | VARCHAR(100) | Remember me token |
| `created_at` | TIMESTAMP | Record creation time |
| `updated_at` | TIMESTAMP | Last update time |

#### Categories Table
| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key, auto-increment |
| `name` | VARCHAR(255) | Category display name |
| `slug` | VARCHAR(255) | URL-friendly identifier |
| `description` | TEXT | Category description |
| `created_at` | TIMESTAMP | Record creation time |
| `updated_at` | TIMESTAMP | Last update time |

#### Posts Table
| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT | Primary key, auto-increment |
| `user_id` | BIGINT | Foreign key to users table |
| `category_id` | BIGINT | Foreign key to categories table |
| `title` | VARCHAR(255) | Post title |
| `slug` | VARCHAR(255) | URL-friendly identifier |
| `excerpt` | TEXT | Post summary |
| `content` | LONGTEXT | Full post content |
| `featured_image` | VARCHAR(255) | Featured image path |
| `status` | ENUM | Publication status (draft/published) |
| `is_featured` | BOOLEAN | Featured post flag |
| `views` | BIGINT | View count |
| `meta_title` | VARCHAR(255) | SEO title |
| `meta_description` | TEXT | SEO description |
| `meta_keywords` | VARCHAR(500) | SEO keywords |
| `published_at` | TIMESTAMP | Publication timestamp |
| `created_at` | TIMESTAMP | Record creation time |
| `updated_at` | TIMESTAMP | Last update time |

### Database Relationships

- **Users → Posts**: One-to-Many (An author can have multiple posts)
- **Categories → Posts**: One-to-Many (A category can contain multiple posts)
- **Posts → Tags**: Many-to-Many (Posts can have multiple tags, tags can be on multiple posts)

## API Reference

### Authentication

All API endpoints are publicly accessible for read operations. Write operations require authentication via Laravel Sanctum tokens.

### Base URL
```
https://your-domain.com/api
```

### Endpoints Overview

#### Posts API

##### List All Posts
```http
GET /api/posts
```

**Query Parameters:**

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `category` | string | Filter by category slug | `?category=tutorials` |
| `search` | string | Search in title and content | `?search=laravel` |
| `page` | integer | Page number for pagination | `?page=2` |
| `per_page` | integer | Items per page (max: 50) | `?per_page=20` |
| `featured` | boolean | Filter featured posts | `?featured=true` |
| `sort` | string | Sort order (latest, popular, oldest) | `?sort=popular` |

**Response Format:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Getting Started with Laravel 10",
      "slug": "getting-started-with-laravel-10",
      "excerpt": "A comprehensive guide to Laravel 10 features and improvements...",
      "content": "Full article content here...",
      "featured_image": "https://your-domain.com/storage/images/laravel-10.jpg",
      "status": "published",
      "is_featured": true,
      "views": 1547,
      "reading_time": 8,
      "published_at": "2025-08-23T10:00:00.000000Z",
      "created_at": "2025-08-20T15:30:00.000000Z",
      "updated_at": "2025-08-23T10:00:00.000000Z",
      "category": {
        "id": 1,
        "name": "Tutorials",
        "slug": "tutorials",
        "description": "Step-by-step tutorials and guides"
      },
      "author": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "tags": [
        {
          "id": 1,
          "name": "Laravel",
          "slug": "laravel"
        },
        {
          "id": 2,
          "name": "PHP",
          "slug": "php"
        }
      ],
      "meta": {
        "title": "Getting Started with Laravel 10 - Complete Guide",
        "description": "Learn Laravel 10 from scratch with this comprehensive tutorial...",
        "keywords": "laravel, php, web development, tutorial"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 47,
    "path": "https://your-domain.com/api/posts",
    "prev_page_url": null,
    "next_page_url": "https://your-domain.com/api/posts?page=2"
  }
}
```

##### Get Single Post
```http
GET /api/posts/{slug}
```

**Path Parameters:**
- `slug` (string, required): The post slug identifier

**Response Format:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Getting Started with Laravel 10",
    // ... same structure as list endpoint
    "related_posts": [
      {
        "id": 2,
        "title": "Advanced Laravel Features",
        "slug": "advanced-laravel-features",
        "excerpt": "Explore advanced Laravel concepts..."
      }
    ]
  }
}
```

##### Get Categories
```http
GET /api/posts/categories
```

**Response Format:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Tutorials",
      "slug": "tutorials",
      "description": "Step-by-step tutorials and guides",
      "post_count": 15,
      "latest_post": {
        "title": "Latest Tutorial",
        "published_at": "2025-08-23T10:00:00.000000Z"
      }
    }
  ]
}
```

##### Get Popular Posts
```http
GET /api/posts/popular
```

**Query Parameters:**
- `limit` (integer, optional): Number of posts to return (default: 10, max: 50)
- `period` (string, optional): Time period (week, month, year, all) (default: month)

### Error Handling

API responses follow consistent error formatting:

```json
{
  "success": false,
  "message": "Resource not found",
  "errors": {
    "slug": [
      "The specified post does not exist."
    ]
  },
  "code": 404
}
```

### Rate Limiting

API endpoints are rate-limited to prevent abuse:
- **Public endpoints**: 1000 requests per hour per IP
- **Authenticated endpoints**: 5000 requests per hour per user

## Security Features

### Built-in Security Measures

- **Authentication**: Laravel Breeze with session-based authentication
- **Authorization**: Role-based access control (RBAC)
- **CSRF Protection**: Cross-site request forgery protection on all forms
- **XSS Prevention**: Automatic output escaping and input sanitization
- **SQL Injection Protection**: Eloquent ORM with prepared statements
- **File Upload Security**: MIME type validation and file size limits
- **Rate Limiting**: API and form submission rate limiting
- **Security Headers**: HSTS, X-Frame-Options, and CSP headers

### Recommended Security Practices

1. **Environment Configuration**
   - Use strong, unique `APP_KEY`
   - Set `APP_DEBUG=false` in production
   - Configure proper database credentials
   - Use HTTPS in production

2. **File Permissions**
   ```bash
   # Set proper directory permissions
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   chmod -R 775 storage bootstrap/cache
   ```

3. **Regular Updates**
   - Keep Laravel and dependencies updated
   - Monitor security advisories
   - Regular security audits

## Contributing

We welcome contributions to TechDaily Blog! Please read our contributing guidelines before submitting pull requests.

### Development Workflow

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`php artisan test`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for API changes
- Use meaningful commit messages

## License

This project is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Support & Community

- **Documentation**: [Full Documentation](https://github.com/hanif-alkahfy/techdaily-blog/wiki)
- **Issues**: [GitHub Issues](https://github.com/hanif-alkahfy/techdaily-blog/issues)
- **Discussions**: [GitHub Discussions](https://github.com/hanif-alkahfy/techdaily-blog/discussions)

## Author

**Hanif Alkahfy**
- GitHub: [@hanif-alkahfy](https://github.com/hanif-alkahfy)
- Email: hanif.alkahfy@example.com
- LinkedIn: [Hanif Al-Kahfi](https://linkedin.com/in/hanif-alkahfy)

## Acknowledgments

Special thanks to the following projects and communities:

- [Laravel Framework](https://laravel.com) - The PHP framework for web artisans
- [Bootstrap](https://getbootstrap.com) - The world's most popular CSS framework
- [TailwindCSS](https://tailwindcss.com) - A utility-first CSS framework
- [TinyMCE](https://www.tiny.cloud) - The world's most advanced rich text editor
- [Laravel Community](https://laravel.io) - For continuous inspiration and support

---

**Version**: 1.0.0  
**Last Updated**: August 23, 2025  
**Minimum Laravel Version**: 10.0
