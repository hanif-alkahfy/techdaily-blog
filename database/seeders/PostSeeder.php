<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have at least one user
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@techdaily.com',
            ]);
        }

        // Get admin user
        $admin = User::where('email', 'admin@techdaily.com')->first();

        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@techdaily.com',
                'is_admin' => true
            ]);
        }

        // Create posts with real content
        $this->createRealPosts($admin);

        $this->command->info('Created ' . Post::count() . ' posts with real content');
    }

    /**
     * Create posts based on real content from various platforms
     */
    /**
     * Get relevant tags from content
     */
    private function getRelevantTags(string $content, string $category): array
    {
        $commonTags = [
            'Tutorial' => ['tutorial', 'guide', 'howto', 'learn', 'step-by-step'],
            'Web Development' => ['web', 'development', 'frontend', 'backend', 'fullstack'],
            'Opinion' => ['opinion', 'thoughts', 'perspective', 'analysis', 'insights'],
            'Review' => ['review', 'comparison', 'versus', 'pros-cons', 'benchmark'],
            'News' => ['news', 'announcement', 'release', 'update', 'latest'],
            'Tips' => ['tips', 'tricks', 'best-practices', 'optimization', 'productivity']
        ];

        // Get common tags for the category
        $tags = $commonTags[$category] ?? [];

        // Extract technology names mentioned in content
        $techKeywords = [
            'Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React', 'TypeScript',
            'Docker', 'MySQL', 'Redis', 'Git', 'AWS', 'DevOps',
            'API', 'REST', 'GraphQL', 'Node.js', 'Python', 'Java',
            'HTML', 'CSS', 'Tailwind', 'Bootstrap', 'jQuery', 'Angular'
        ];

        foreach ($techKeywords as $tech) {
            if (stripos($content, $tech) !== false) {
                $tags[] = strtolower(str_replace(['.', ' '], ['', '-'], $tech));
            }
        }

        // Add programming-related terms if found
        $programmerTerms = ['code', 'programming', 'software', 'developer', 'engineer'];
        foreach ($programmerTerms as $term) {
            if (stripos($content, $term) !== false) {
                $tags[] = $term;
            }
        }

        // Remove duplicates and return
        return array_unique(array_filter($tags));
    }

    /**
     * Get featured image path from storage based on content
     */
    private function getFeaturedImage(array $postData): string
    {
        // Map specific titles to featured images
        $imageMap = [
            'Building a REST API with Laravel 10: Complete Guide' => 'posts/featured/laravel-api.png',
            'Docker for PHP Development: Complete Setup Guide' => 'posts/featured/docker-setup.png',
            'Why I Still Choose Vue.js Over React in 2024' => 'posts/featured/vue-vs-react.jpeg',
            'The Death of jQuery: Why It\'s Finally Time to Move On' => 'posts/featured/jquery-end.png',
            'Tailwind CSS vs Bootstrap 5: A Comprehensive Comparison' => 'posts/featured/tailwind-bootstrap.png',
            'Reviewing the Top 5 PHP IDEs in 2024' => 'posts/featured/php-ides.png',
            'PHP 8.3 Released: New Features and Improvements' => 'posts/featured/php-ides.png',
            'GitHub Copilot Chat Now Available in IDE' => 'posts/featured/copilot-chat.png',
            '10 Laravel Performance Tips Every Developer Should Know' => 'posts/featured/laravel-api.png',
            '5 Git Commands Every Developer Should Master' => 'posts/featured/git-commands.png'
        ];

        $title = $postData['title'];
        $storagePath = storage_path('app/public');

        // Jika ada mapping spesifik untuk judul ini, gunakan gambar yang sesuai
        if (isset($imageMap[$title])) {
            // Pastikan direktori ada
            $dir = dirname($storagePath . '/' . $imageMap[$title]);
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            // Jika file gambar belum ada, buat placeholder
            $imagePath = $storagePath . '/' . $imageMap[$title];
            if (!file_exists($imagePath)) {
                // Salin placeholder jika ada
                $placeholder = public_path('images/post-placeholder.jpg');
                if (file_exists($placeholder)) {
                    copy($placeholder, $imagePath);
                } else {
                    // Jika tidak ada placeholder, buat file kosong
                    file_put_contents($imagePath, 'Placeholder Image');
                }
            }

            return $imageMap[$title];
        }

        // Fallback ke default featured image jika tidak ada mapping
        $defaultPath = 'posts/featured/default.png';
        $defaultFullPath = $storagePath . '/' . $defaultPath;

        // Buat direktori jika belum ada
        $dir = dirname($defaultFullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Buat default image jika belum ada
        if (!file_exists($defaultFullPath)) {
            $placeholder = public_path('images/post-placeholder.jpg');
            if (file_exists($placeholder)) {
                copy($placeholder, $defaultFullPath);
            } else {
                file_put_contents($defaultFullPath, 'Placeholder Image');
            }
        }

        return $defaultPath;
    }    private function createRealPosts($admin): void
    {
        $baseDate = now()->subMonths(2);
        $dayIncrement = 0;
        $realPosts = [
            // Tutorial Category
            [
                'title' => 'Building a REST API with Laravel 10: Complete Guide',
                'category' => 'Tutorial',
                'status' => 'published',
                'excerpt' => 'Learn how to build a complete REST API using Laravel 10 with authentication, validation, and proper error handling.',
                'content' => 'Laravel has long been a favorite among PHP developers for building modern web applications, and with the release of Laravel 10, creating REST APIs has become even more efficient and developer-friendly. A REST API allows different applications to communicate by exposing endpoints for data exchange, and Laravel provides powerful tools such as routing, controllers, and Eloquent ORM to make this process straightforward.<br><br>

The first step in building a REST API with Laravel 10 is setting up routes and controllers. Laravel’s routing system makes it simple to define endpoints like /api/posts or /api/users, which map to specific controller methods. Controllers then handle logic such as fetching data from the database or returning JSON responses. With Laravel’s expressive syntax, developers can create clean and maintainable code that adheres to RESTful principles.<br><br>

Another key element is data handling with Eloquent ORM, which simplifies database interactions by allowing developers to work with models instead of raw SQL queries. This makes it easy to perform operations such as creating, updating, and retrieving data. To ensure security and consistency, Laravel also includes built-in features like validation, middleware for authentication, and resource classes for formatting API responses in a standardized way.<br><br>

By leveraging these tools, developers can quickly build scalable and maintainable REST APIs with Laravel 10. Whether powering a mobile app, connecting with third-party services, or driving a single-page application, Laravel provides everything needed to create robust APIs. With its elegant syntax, strong ecosystem, and commitment to modern PHP practices, Laravel 10 continues to be one of the best frameworks for API development in 2024 and beyond.',
                'source' => 'Inspired by Dev.to community posts',
            ],
            [
                'title' => 'Docker for PHP Development: Complete Setup Guide',
                'category' => 'Tutorial',
                'status' => 'published',
                'excerpt' => 'Set up a complete Docker development environment for PHP applications with MySQL, Redis, and proper volume mounting.',
                'content' => 'In modern web development, containerization has become a standard practice for building consistent and scalable environments. Docker plays a crucial role in this shift, allowing developers to run applications inside lightweight containers that work the same way across different systems. For PHP developers, Docker provides an easy way to set up and manage environments without worrying about local configuration issues or system dependencies.,<br><br>

The main advantage of using Docker for PHP development is consistency. With Docker, developers can define their entire stack—including PHP, web servers like Nginx or Apache, and databases such as MySQL—inside configuration files. This eliminates the “it works on my machine” problem and ensures that every team member works with the same setup. It also makes deployment to production environments smoother, as the containerized setup mirrors the local development environment.<br><br>

Setting up PHP with Docker typically involves creating a Dockerfile for defining the PHP environment and a docker-compose.yml file to orchestrate services like databases, caching systems, and web servers. Developers can also mount project files directly into containers, making it easy to test changes instantly. This modular and scalable approach is especially useful for projects using modern PHP frameworks such as Laravel or Symfony, which often require multiple services working together seamlessly.<br><br>

By adopting Docker, PHP developers can streamline their workflows, improve collaboration, and reduce time spent on configuration. The ability to spin up environments quickly, replicate setups across teams, and deploy applications with confidence makes Docker an essential tool in today’s PHP ecosystem. With a well-structured setup, Docker not only simplifies development but also paves the way for smoother testing, staging, and production pipelines.',
                'source' => 'Based on Docker Hub documentation and community best practices',
            ],

            // Opinion Category
            [
                'title' => 'Why I Still Choose Vue.js Over React in 2024',
                'category' => 'Opinion',
                'status' => 'published',
                'excerpt' => 'After working with both frameworks extensively, here\'s why Vue.js continues to be my go-to choice for frontend development.',
                'content' => 'In the ever-evolving world of front-end frameworks, React and Vue.js continue to dominate discussions among developers. React, backed by Meta, remains the most widely adopted, while Vue.js has steadily grown into a powerful and beloved framework. Despite React’s popularity and vast ecosystem, many developers—including myself—still prefer Vue.js in 2024 for its simplicity, flexibility, and developer experience.<br><br>

One of the main reasons I choose Vue.js is its gentle learning curve. Vue provides a clean, intuitive syntax that feels natural even to beginners, while still offering advanced features for complex applications. Unlike React, which often requires additional setup and decisions around state management or routing, Vue offers a more integrated ecosystem. Tools like Vue Router and Pinia work seamlessly, reducing the time spent configuring and allowing developers to focus on building features.<br><br>

Another advantage of Vue.js is its balance between structure and flexibility. React emphasizes a "JavaScript-first" approach, which can feel unstructured at times, while Vue’s single-file components strike a balance by combining template, logic, and styles in one place. This structure makes collaboration easier for teams and improves maintainability in the long run. Moreover, Vue’s reactivity system, especially in Vue 3, feels more intuitive and efficient compared to React’s hooks, offering a smoother developer experience.<br><br>

Ultimately, while React’s massive community and job market make it an attractive choice, Vue.js shines for developers who prioritize simplicity, productivity, and elegance. In 2024, I continue to choose Vue.js because it helps me build applications faster without compromising scalability. Both frameworks are excellent in their own right, but for many projects, Vue offers the perfect balance of power and developer happiness.',
                'source' => 'Personal opinion piece inspired by Medium discussions',
            ],
            [
                'title' => 'The Death of jQuery: Why It\'s Finally Time to Move On',
                'category' => 'Opinion',
                'status' => 'published',
                'excerpt' => 'jQuery served us well for over a decade, but modern JavaScript and frameworks have made it obsolete. Here\'s why it\'s time to let go.',
                'content' => 'For over a decade, jQuery was the go-to JavaScript library that revolutionized web development. It simplified DOM manipulation, event handling, and AJAX requests at a time when browser inconsistencies made JavaScript difficult to work with. Nearly every major website relied on it, and it became a standard inclusion in countless projects. However, as browsers evolved and modern JavaScript gained powerful new features, the once-essential jQuery has begun to lose its relevance.<br><br>

Modern JavaScript now offers many of the conveniences that jQuery once provided. Features like querySelector, fetch, template literals, and ES6+ syntax have eliminated the need for jQuery in most cases. Frameworks such as React, Vue, and Angular also provide structured solutions for building dynamic interfaces, reducing the role of jQuery to a legacy dependency rather than an active tool in modern development. This shift has led many developers to reconsider whether including jQuery is worth the performance cost.<br><br>

Another factor in jQuery’s decline is performance and maintainability. Including the entire jQuery library just to simplify a few DOM operations adds unnecessary weight to modern web applications. In an era where performance optimization and bundle size are critical, relying on jQuery often creates more problems than it solves. Developers are now encouraged to adopt native JavaScript or lightweight utilities that offer the same functionality with a fraction of the overhead.<br><br>

While jQuery deserves recognition for its role in shaping the modern web, it’s clear that its time has passed. The developer community has largely moved on to newer tools and frameworks that are faster, more efficient, and better suited for today’s development needs. For developers still relying on jQuery, now is the perfect time to embrace modern JavaScript and frameworks that will ensure future-ready, scalable applications.',
                'source' => 'Inspired by discussions on Hacker News and dev communities',
            ],

            // Review Category
            [
                'title' => 'Tailwind CSS vs Bootstrap 5: A Comprehensive Comparison',
                'category' => 'Review',
                'status' => 'published',
                'excerpt' => 'An in-depth comparison of the two most popular CSS frameworks, covering everything from design philosophy to performance.',
                'content' => 'When it comes to front-end development, choosing the right CSS framework can save developers time and ensure consistency across projects. Two of the most widely used frameworks today are Tailwind CSS and Bootstrap 5. While both aim to accelerate the styling process, they take very different approaches. Bootstrap provides a ready-to-use component library with pre-designed elements, while Tailwind focuses on utility-first classes that give developers granular control over design.<br><br>

Bootstrap 5 is a tried-and-true framework that offers a comprehensive set of responsive components like navbars, modals, grids, and forms. It’s beginner-friendly and ideal for developers who want to get projects up and running quickly without worrying too much about design details. With built-in JavaScript components and a strong ecosystem, Bootstrap remains a popular choice for rapid prototyping and enterprise applications.<br><br>

On the other hand, Tailwind CSS gives developers more flexibility by providing low-level utility classes that can be combined to create custom designs without writing custom CSS. This approach results in highly customizable and unique user interfaces, making Tailwind a favorite among developers who want design freedom. Although it has a steeper learning curve, Tailwind reduces the need to override predefined styles, which can often be a pain point when using Bootstrap.<br><br>

In the end, the choice between Tailwind CSS and Bootstrap 5 depends on the project’s goals. For fast, standardized, and consistent designs, Bootstrap offers a straightforward solution. For developers seeking customization, scalability, and a modern workflow, Tailwind CSS is often the better fit. Both frameworks are powerful in their own right, and understanding their strengths will help developers choose the best tool for their specific needs.',
                'source' => 'Based on extensive usage and community feedback from Reddit and dev forums',
            ],
            [
                'title' => 'Reviewing the Top 5 PHP IDEs in 2024',
                'category' => 'Review',
                'status' => 'published',
                'excerpt' => 'A detailed review of the best IDEs and editors for PHP development, comparing features, performance, and value.',
                'content' => 'Choosing the right Integrated Development Environment (IDE) can have a huge impact on a developer’s productivity, efficiency, and overall coding experience. In 2024, PHP remains one of the most widely used languages for web development, powering frameworks like Laravel, Symfony, and CodeIgniter. With so many IDE options available, selecting the best one often comes down to balancing performance, features, and ease of use.<br><br>

One of the most popular choices is PhpStorm, known for its powerful debugging, smart code completion, and seamless integration with modern frameworks. For developers who prefer a lightweight yet versatile tool, Visual Studio Code continues to dominate with its rich ecosystem of extensions and community support. On the other hand, NetBeans remains a reliable option with built-in PHP support and strong project management capabilities, making it a solid choice for beginners and professionals alike.<br><br>

Other notable IDEs include Eclipse PDT, which provides a flexible and open-source environment for PHP development, and Aptana Studio, tailored for full-stack developers who want PHP alongside JavaScript, HTML, and CSS support. Each IDE has its strengths—while PhpStorm excels at professional-grade features, VS Code offers unmatched flexibility, and NetBeans, Eclipse, and Aptana provide free and open-source alternatives.<br><br>

Ultimately, the “best” IDE depends on a developer’s personal preferences and project requirements. For those who want premium features and don’t mind investing, PhpStorm leads the pack. For developers seeking a free, customizable, and lightweight environment, VS Code stands out. Meanwhile, open-source enthusiasts can rely on NetBeans, Eclipse, or Aptana for robust support. In 2024, PHP developers are spoiled for choice, with IDEs that cater to every workflow and coding style.

Do you want me to also make a detailed blog version with each IDE broken down into pros, cons, and ideal use cases (plus screenshots/code snippets), so it’s more practical for readers comparing tools?',
                'source' => 'Personal testing and community feedback from Stack Overflow surveys',
            ],

            // News Category
            [
                'title' => 'PHP 8.3 Released: New Features and Improvements',
                'category' => 'News',
                'status' => 'published',
                'excerpt' => 'PHP 8.3 brings exciting new features including typed class constants, readonly amendments, and performance improvements.',
                'content' => 'The PHP development team has officially announced the release of PHP 8.3, introducing a range of new features, performance enhancements, and improvements that make the language even more powerful and developer-friendly. As the backbone of countless web applications and frameworks, including Laravel, Symfony, and WordPress, PHP continues to evolve to meet the needs of modern development. This release builds on the strong foundation of PHP 8.0, 8.1, and 8.2, further enhancing both speed and usability.<br><br>

One of the most notable additions in PHP 8.3 is the introduction of typed class constants, which allow developers to enforce type declarations on class constants just as they can with properties and function parameters. This improves code reliability and reduces the chances of type-related bugs. Another highlight is the expansion of the readonly properties feature, now supporting dynamic and more flexible use cases that give developers greater control over immutability in their applications.<br><br>

Beyond new language features, PHP 8.3 brings important performance improvements under the hood. The Just-In-Time (JIT) compiler and engine optimizations have been refined, ensuring faster execution of PHP code across a variety of workloads. Additionally, enhancements to error handling and deprecation notices provide clearer guidance for developers, making it easier to maintain and upgrade codebases without unexpected issues.<br><br>

With these updates, PHP 8.3 reinforces its role as a modern, efficient, and robust programming language. Developers can look forward to cleaner syntax, better performance, and stronger tools for building scalable web applications. As adoption grows, the new features and improvements in PHP 8.3 will play a crucial role in shaping the next generation of PHP-powered projects.',
                'source' => 'Official PHP release notes and community discussions',
            ],
            [
                'title' => 'GitHub Copilot Chat Now Available in IDE',
                'category' => 'News',
                'status' => 'published',
                'excerpt' => 'GitHub announces the general availability of Copilot Chat, bringing conversational AI assistance directly to your development environment.',
                'content' => 'GitHub has officially released Copilot Chat, now available directly within popular Integrated Development Environments (IDEs) such as Visual Studio Code and JetBrains. This feature allows developers to interact with GitHub Copilot through a fully integrated chat interface inside the IDE. With Copilot Chat, developers can go beyond code suggestions — they can ask questions, request explanations, and even debug issues faster without leaving their development environment.<br><br>

One of the main advantages of Copilot Chat is its ability to understand project context in real time. The AI can provide answers based on the specific code being worked on, making its suggestions more accurate and relevant. For example, when a developer encounters an error, Copilot Chat can analyze the related snippet and propose concrete solutions rather than giving generic responses. This significantly speeds up the development process and reduces the time spent searching for fixes online.<br><br>

Additionally, GitHub Copilot Chat supports a variety of productivity commands, such as generating documentation automatically, creating unit tests, and explaining complex functions or algorithms. This feature encourages more efficient collaboration, especially for teams that want to maintain consistency in both code and documentation. With easier access through IDEs, developers can rely on Copilot as a “pair programmer” that is always ready to assist.<br><br>

The arrival of GitHub Copilot Chat in IDEs marks a major step toward smarter and more efficient coding experiences. This integration not only extends Copilot’s functionality but also reinforces the role of AI as an essential partner in modern software development. As more developers adopt this feature, GitHub aims to boost productivity, code quality, and the overall learning experience in programming.',
                'source' => 'GitHub official announcements and developer community feedback',
            ],

            // Tips Category
            [
                'title' => '10 Laravel Performance Tips Every Developer Should Know',
                'category' => 'Tips',
                'status' => 'published',
                'excerpt' => 'Essential performance optimization techniques to make your Laravel applications faster and more efficient.',
                'content' => 'Laravel is one of the most popular PHP frameworks, known for its elegant syntax and developer-friendly ecosystem. However, as applications grow, performance optimization becomes a key factor to ensure fast and scalable systems. Understanding how to fine-tune Laravel can make a big difference in both user experience and resource management. That’s why every developer should be aware of the best practices to optimize Laravel applications effectively.<br><br>

Some of the most effective performance tips include using caching wherever possible, such as query caching and route caching, which significantly reduce load times. Developers should also take advantage of config caching and optimized autoloading to speed up application bootstrapping. Minimizing database queries through eager loading and indexing also ensures that data retrieval remains efficient as the project scales. These small changes can lead to noticeable improvements in speed and responsiveness.<br><br>

In addition to caching and query optimization, developers should consider using queues for tasks like sending emails or processing jobs in the background. This prevents heavy operations from slowing down the user-facing side of the application. Tools like Redis or Horizon can be integrated to handle queues efficiently. Another useful practice is implementing content delivery networks (CDNs) for static assets, reducing server load and ensuring faster delivery of resources to users worldwide.<br><br>

By applying these performance tips, Laravel developers can create applications that are not only powerful but also highly optimized. A well-performing app enhances user satisfaction, improves SEO rankings, and reduces server costs. While Laravel provides a solid foundation out of the box, it’s up to developers to apply these best practices to unlock its full potential. Ultimately, consistent monitoring and optimization will ensure that Laravel applications remain fast, scalable, and reliable over time.',
                'source' => 'Laravel community best practices and official documentation',
            ],
            [
                'title' => '5 Git Commands Every Developer Should Master',
                'category' => 'Tips',
                'status' => 'published',
                'excerpt' => 'Beyond the basics: advanced Git commands that will improve your development workflow and save you time.',
                'content' => 'Git has become an essential tool for modern software development, serving as the backbone of version control and collaboration. Whether you’re working solo or as part of a large team, mastering Git commands can dramatically improve your workflow and confidence when managing code. While there are many commands available, some are fundamental and should be second nature to every developer.<br><br>

The first command every developer must know is git clone, which allows you to copy a remote repository to your local machine and start contributing right away. Another key command is git commit, used to save changes to the local repository with a descriptive message that tracks the history of your project. These two commands lay the foundation for effective version control by ensuring your codebase is both accessible and well-documented.<br><br>

Equally important is git branch, which lets you create and manage branches for new features, bug fixes, or experiments without disrupting the main codebase. Alongside it, git merge enables you to combine changes from different branches, making collaboration smooth and efficient. Finally, git push is the command that shares your local changes with the remote repository, ensuring that your work is integrated with your team’s contributions.<br><br>

By mastering these five essential Git commands, developers can handle most daily tasks confidently and efficiently. They form the foundation of collaborative development, allowing teams to experiment, iterate, and ship code without fear of losing work. While Git has many advanced features worth exploring, starting with these basics ensures that every developer can participate effectively in any project.',
                'source' => 'Git documentation and developer community best practices',
            ],
        ];

        foreach ($realPosts as $postData) {
            $created_at = $baseDate->copy()->addDays($dayIncrement);
            $dayIncrement += 2; // Add 2 days between posts for chronological spacing

            // Get or create category
            $category = Category::firstOrCreate(
                ['name' => $postData['category']],
                ['slug' => \Illuminate\Support\Str::slug($postData['category'])]
            );

            // Get featured image based on content
            $featuredImage = $this->getFeaturedImage($postData);

            // Prepare meta data
            $slug = Str::slug($postData['title']);

            // Generate meta title (use original title or enhance it)
            $metaTitle = $postData['title'];
            if (strlen($metaTitle) < 40) {
                $metaTitle .= ' - TechDaily Blog';
            }

            // Generate meta description from excerpt
            $metaDescription = strip_tags($postData['excerpt']);
            if (strlen($metaDescription) > 140) {
                $metaDescription = substr($metaDescription, 0, 157) . '...';
            }

            // Generate meta keywords
            $keywords = array_unique([
                $category->name,  // Category as primary tag
                ...array_filter(explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $postData['title'])))),
                ...$this->getRelevantTags($postData['content'], $category->name)
            ]);

            Post::factory()->create([
                'title' => $postData['title'],
                'slug' => $slug,
                'category_id' => $category->id,
                'status' => $postData['status'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'user_id' => $admin->id,
                'created_at' => $created_at,
                'updated_at' => $created_at,
                'published_at' => $created_at,
                'featured_image' => $featuredImage,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'meta_keywords' => implode(', ', array_slice($keywords, 0, 10)),
                'is_featured' => true,
            ]);
        }

        $this->command->info('Created ' . count($realPosts) . ' posts from real online sources');
    }
}
