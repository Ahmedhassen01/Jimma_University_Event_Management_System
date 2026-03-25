# Jimma University Event Management System (JU-EMS)

JU-EMS is a role-based Laravel 12 web application for managing university events, announcements, notifications and feedback.

## 1) Core Business Logic

### Authentication and Roles
- New users register from `/register`.
- Every new registration is automatically assigned the `guest` role.
- Super Administrator / Administrator can later assign the final role (Student, Faculty, Event Manager, etc.) from user management.
- Inactive users are blocked from login until reactivated by admin.

### Event Request and Approval Workflow
- Users submit event requests.
- Event Manager reviews/schedules requests.
- Event Manager can send eligible requests for admin approval.
- Super Admin / Admin can approve or reject.
- Approved requests can become published events.

### Event Lifecycle
- Users can browse and register for events.
- Event Manager can update or cancel events.
- Participant-focused notifications are sent for event updates/cancellations.

### Notifications
- Notifications appear in navbar dropdown.
- Recent items are shown first, with controls like show more and clear all.
- Notifications link to related resources (event/request/announcement).

### Feedback Management
- Users can submit feedback.
- Admin-level users can review, assign, respond, and resolve feedback.
- Visibility and featured/public controls are supported.

### Account Deactivation (Data Retention)
- Profile "delete" behavior is implemented as account deactivation.
- User data (feedback, event requests, registrations) remains in DB for audit/history.
- Admins can reactivate accounts.

## 2) Tech Stack
- PHP 8.2+
- Laravel 12
- MySQL
- Blade + Bootstrap
- Vite (frontend assets)
- Pest/PHPUnit (tests)

## 3) Prerequisites
- PHP 8.2 or newer
- Composer 2+
- Node.js 18+ and npm
- MySQL 8+ (or compatible)

## 4) Fresh Setup (Team Onboarding)

After clone or ZIP extract:

```bash
composer install
php artisan key:generate
```

Create `.env` (Linux/macOS):

```bash
cp .env.example .env
```

Create `.env` (Windows CMD/PowerShell):

```bash
copy .env.example .env
```

Configure database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Run DB migration and seed sample data:

```bash
php artisan migrate --seed
```

Create storage symlink:

```bash
php artisan storage:link
```

Install frontend deps:

```bash
npm install
```

Build frontend assets:

```bash
npm run build
```

If PowerShell blocks `npm`, use:

```bash
npm.cmd run build
```

Start app:

```bash
php artisan serve
```

Optional (recommended in separate terminals):

```bash
php artisan queue:work
npm run dev
```

## 5) Seeded Demo Accounts, also we seeded some sample events

After `php artisan migrate --seed`, default users include:

- Super Admin: `superadmin@ju.edu.et` / `password123`
- Admin: `admin@ju.edu.et` / `password123`
- Event Manager: `events@ju.edu.et` / `password123`
- Faculty: `alemayehu@ju.edu.et` / `password123`
- Student: `student@ju.edu.et` / `password123`
