# Laravel + Vue SaaS Starter Kit

A reusable boilerplate for building SaaS applications, built with Laravel 11 and Vue 3. Includes role-based authentication, a dashboard shell, and a full CRUD module out of the box.

## Features

- 🔐 Authentication via Laravel Breeze (Vue + Inertia)
- 👤 Role-based access control (admin/user) with route middleware
- 📊 Responsive dashboard layout with sidebar navigation
- 📁 Full CRUD example module (Projects) — API + Vue frontend
- ✅ PHPUnit feature tests for core endpoints
- 🎨 Styled with Tailwind CSS

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 |
| Frontend | Vue 3 (Composition API), Inertia.js |
| Styling | Tailwind CSS |
| Auth | Laravel Breeze + Sanctum |
| Database | MySQL |
| Testing | PHPUnit |

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

### Installation

\`\`\`bash
git clone https://github.com/ibraza04/laravel-vue-saas-starter.git
cd laravel-vue-saas-starter

composer install
cp .env.example .env
php artisan key:generate

# set your DB credentials in .env, then:
php artisan migrate --seed

npm install
npm run dev
\`\`\`

Then run the backend:
\`\`\`bash
php artisan serve
\`\`\`

Visit `http://localhost:8000`.

### Default Login
- Admin: `admin@example.com` / `password`
- User: `user@example.com` / `password`

## Project Structure

\`\`\`
app/
  Http/Controllers/    # Project, Auth controllers
  Http/Middleware/      # CheckRole middleware
  Models/               # User, Role, Project
resources/js/
  Pages/                # Vue pages (Dashboard, Projects, Auth)
  Layouts/              # DashboardLayout with sidebar
\`\`\`

## Why I Built This

I've built ERP, CRM, and SaaS platforms professionally with this exact stack (Laravel + Vue) for 6+ years. This starter kit packages the common architecture I find myself rebuilding for every new project — auth, roles, dashboard shell, CRUD patterns — into a clean, reusable foundation.

## License

MIT