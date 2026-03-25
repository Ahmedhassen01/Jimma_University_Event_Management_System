# Jimma University Event Management System (JU-EMS)

JU-EMS is a role-based Laravel 12 web application for managing university events, requests, announcements, notifications, registrations, and feedback.

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
DB_DATABASE=intern_ems
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

## 5) Seeded Demo Accounts

After `php artisan migrate --seed`, default users include:

- Super Admin: `superadmin@ju.edu.et` / `password123`
- Admin: `admin@ju.edu.et` / `password123`
- Event Manager: `events@ju.edu.et` / `password123`
- Faculty: `alemayehu@ju.edu.et` / `password123`
- Student: `student@ju.edu.et` / `password123`

## 6) Regression / Smoke Test Commands

Run these before sharing with team:

```bash
php artisan about
php artisan migrate:status
php artisan route:list
php artisan test
```

Expected now:
- `php artisan test` passes (basic smoke tests).
- Routes and migrations load successfully.

## 7) What To Check Manually (High Value)

1. Register a new user and verify role is `guest`.
2. Login/logout across roles.
3. Event request create -> manager review -> admin approve/reject.
4. Event registration and participant flow.
5. Notification dropdown behavior and links.
6. Feedback submit/review/respond flow.
7. Deactivate account and verify login is blocked.
8. Reactivate inactive user from admin side and verify login works again.

## 8) Important Files

- Auth logic: `app/Http/Controllers/AuthController.php`
- User account management: `app/Http/Controllers/UserController.php`
- Profile security/deactivation: `app/Http/Controllers/ProfileController.php`
- Event request workflow: `app/Http/Controllers/EventRequestController.php`
- Notifications: `app/Http/Controllers/NotificationController.php`
- Routes: `routes/web.php`
- Login/Register UI: `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`

## 9) Share to Team (GitHub)

If this folder is not yet a git repo, initialize and push:

```bash
git init
git add .
git commit -m "Initial JU-EMS project handoff"
git branch -M main
git remote add origin <YOUR_TEAM_REPO_URL>
git push -u origin main
```

If already connected to a repo:

```bash
git add .
git commit -m "Project updates + README + regression checks"
git push
```

Your team can then:
- Clone: `git clone <YOUR_TEAM_REPO_URL>`
- Or download ZIP from GitHub and follow section 4.

## 10) Troubleshooting

### `npm` blocked in PowerShell
Use `npm.cmd` commands or run terminal as Administrator.

### `Error: spawn EPERM` during Vite build
Usually Windows permission/AV policy issue. Try:
- Close editors/terminals locking files.
- Run terminal as Administrator.
- Exclude project directory from antivirus scanning.
- Reinstall dependencies (`rm -r node_modules`, `npm install`) and rebuild.

### 403 authorization pages for valid role
Re-check role assignment and permission mapping in seeded roles/permissions.

---

If you want, I can next add a `DEPLOYMENT.md` for production server setup (Nginx/Apache, queue supervisor, env hardening, and backup steps).
