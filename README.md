# TaskFlow — Mini Task Management System
### Swift Tech Solution | Laravel Developer Skill Test

TaskFlow is a clean, modern, and production-ready Mini Task Management application built with Laravel and MySQL. It enables individuals and teams to track task lifecycles, manage priorities, monitor deadlines, and organize workflows through an intuitive, responsive administrative dashboard.

---

## Features

- **Dashboard Summary Cards**: Real-time aggregate counts for Total, Pending, In Progress, and Completed tasks.
- **Task Management (Full CRUD)**:
  - Create new tasks with title, description, priority, status, and optional due date.
  - View individual task details with audit timestamps and overdue indicators.
  - Edit and update existing tasks with pre-populated form fields.
  - Delete tasks securely with JavaScript confirmation dialog and CSRF-protected DELETE method spoofing.
- **Multi-Criteria Search & Filtering**:
  - Instant title keyword search.
  - Filter by Status (`Pending`, `In Progress`, `Completed`).
  - Filter by Priority (`Low`, `Medium`, `High`).
  - Search and both filters work seamlessly together.
  - Dedicated "Clear Filters" action.
  - Safe fallback for invalid filter query parameters.
- **Pagination**:
  - Preserves active search queries and filter parameters across page navigation (`withQueryString()`).
- **Robust Server-Side Validation**:
  - Title: required, string, max:255.
  - Description: nullable, string.
  - Priority: required, in:Low,Medium,High.
  - Status: required, in:Pending,In Progress,Completed.
  - Due Date: nullable, valid date.
  - Clear field-level error messages and preservation of old input (`old()`).
- **Modern Responsive UI**:
  - Built with Laravel Blade and Bootstrap 5.3.3.
  - Clean neutral aesthetic, white cards, subtle badges, and Bootstrap Icons.
  - Fully responsive across desktop, tablet, and mobile viewports.
- **Automated Testing Suite**:
  - Comprehensive feature tests covering all CRUD operations, validation rules, search, and combined filtering.
  - Runs in-memory with SQLite for isolation without affecting production data.

---

## Technology Stack

- **Framework**: [Laravel 12 / 13](https://laravel.com)
- **Language**: PHP 8.4+
- **Database**: MySQL 8.0+ (SQLite in-memory for testing)
- **Frontend / Templating**: Blade, Bootstrap 5.3.3 CDN, Bootstrap Icons 1.11.3
- **ORM**: Eloquent ORM
- **Testing**: PHPUnit 12

---

## Prerequisites

Ensure the following tools are installed on your machine:

- **PHP**: `^8.2` or higher (with `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json` extensions)
- **Composer**: `^2.0` or higher
- **MySQL Server**: `^8.0` or MariaDB

---

## Installation & Setup

### 1. Clone the repository & enter directory
```bash
cd test
```

### 2. Install Composer dependencies
```bash
composer install
```

### 3. Environment configuration
Copy the example environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure MySQL Database
Open `.env` and verify or set your MySQL database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Create the database in MySQL if it doesn't already exist:
```sql
CREATE DATABASE IF NOT EXISTS test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Database Migrations and Seeders
Execute the migrations to set up the database schema and optionally populate sample tasks:
```bash
# Run migrations only
php artisan migrate

# Or run migrations with realistic sample tasks
php artisan migrate --seed
```
*Note: The `TaskSeeder` populates 18 realistic sample tasks across all priorities and statuses.*

### 6. Start Local Development Server
```bash
php artisan serve
```
Visit the application in your browser at:
`http://localhost:8000` or `http://127.0.0.1:8000` (automatically redirects to `/tasks`).

---

## Running Automated Tests

Run the complete test suite:
```bash
php artisan test
```

Or run PHPUnit directly:
```bash
./vendor/bin/phpunit
```

All 11 feature tests will run against an in-memory SQLite database without modifying your MySQL database:
- `test_the_application_redirects_to_tasks_dashboard`
- `test_task_listing_page_loads_successfully`
- `test_valid_task_can_be_created`
- `test_required_title_validation_works`
- `test_invalid_priority_or_status_is_rejected`
- `test_an_existing_task_can_be_updated`
- `test_a_task_can_be_deleted`
- `test_search_by_title_works`
- `test_status_and_priority_filters_work`
- `test_task_details_page_loads_with_all_fields`

---

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── TaskController.php       # Resource controller (CRUD, search, filtering, counts)
│   └── Models/
│       └── Task.php                     # Eloquent model with mass-assignment & badge helpers
├── database/
│   ├── factories/
│   │   └── TaskFactory.php              # Factory for realistic task generation
│   ├── migrations/
│   │   └── *_create_tasks_table.php     # Schema definition for tasks
│   └── seeders/
│       ├── DatabaseSeeder.php           # Master database seeder
│       └── TaskSeeder.php               # Populates 18 diverse sample tasks
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Master responsive layout (Bootstrap 5)
│       ├── partials/
│       │   └── flash.blade.php          # Dismissible alert banners
│       └── tasks/
│           ├── form.blade.php           # Shared form partial for create/edit
│           ├── index.blade.php          # Main dashboard & listing
│           ├── create.blade.php         # Task creation view
│           ├── edit.blade.php           # Task edit view
│           └── show.blade.php           # Task details view
├── routes/
│   └── web.php                          # Web route definitions & root redirect
├── tests/
│   └── Feature/
│       ├── ExampleTest.php              # Root redirect test
│       └── TaskManagementTest.php       # Core feature test suite
└── README.md
```

---

## License

This project is open-sourced software developed for the Swift Tech Solution Laravel Developer Skill Test.
