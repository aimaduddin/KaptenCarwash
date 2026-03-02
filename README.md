# KaptenCarwash

A modern car wash booking system built with Laravel 12 and Livewire. Features include customer bookings, admin dashboard, calendar view, and payment processing.

## Features

### Customer-Facing
- **Guest Booking System** - Book appointments without registration
- **Booking Wizard** - Multi-step booking process with service selection
- **Real-time Availability** - Check available slots in real-time
- **Payment Integration** - Dummy payment processing for demo purposes

### Admin Panel
- **Dashboard** - Overview of bookings and statistics
- **Booking Management** - View, update, and cancel bookings
- **Kanban Board** - Visual workflow management
- **Calendar View** - See bookings on a calendar
- **Service Pricing** - Manage service prices
- **Unavailable Dates** - Set days when business is closed
- **Settings** - Configure business details

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Livewire 3
- **Database:** PostgreSQL
- **Styling:** Tailwind CSS
- **Build Tool:** Vite

## Installation

1. Clone the repository:
```bash
git clone https://github.com/aimaduddin/KaptenCarwash.git
cd KaptenCarwash
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure database in `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kaptencarwash
DB_USERNAME=your_postgres_user
DB_PASSWORD=your_postgres_password
```

6. Run migrations and seed database:
```bash
php artisan migrate
php artisan db:seed
```

7. Build assets:
```bash
npm run build
```

8. Start development server:
```bash
php artisan serve
```

## Database Migrations

The following tables are created:
- `users` - Admin users
- `car_types` - Vehicle types (sedan, SUV, etc.)
- `services` - Car wash services
- `bookings` - Booking records
- `booking_services` - Pivot table for bookings and services
- `business_settings` - Business configuration
- `unavailable_dates` - Blocked booking dates
- `sessions` - Session storage

## Project Structure

```
app/
├── Http/Controllers/
│   └── BookingController.php
├── Livewire/
│   ├── Admin/
│   │   ├── Dashboard.php
│   │   ├── BookingList.php
│   │   ├── KanbanBoard.php
│   │   ├── Calendar.php
│   │   ├── ServicePricingTable.php
│   │   ├── UnavailableDatesManager.php
│   │   └── Settings.php
│   └── BookingWizard.php
├── Models/
│   ├── User.php
│   ├── Booking.php
│   ├── CarType.php
│   ├── Service.php
│   └── BusinessSettings.php
├── Services/
│   └── AvailabilityService.php
└── Notifications/
    └── BookingConfirmed.php
```

## Admin Access

Seed the database to create an admin user:
```bash
php artisan db:seed
```

Default admin credentials (check `DatabaseSeeder.php` for actual values):
- Email: admin@example.com
- Password: password

**Important:** Change the default password in production!

## Development

Run tests:
```bash
php artisan test
```

Format code:
```bash
php artisan pint
```

Build assets for production:
```bash
npm run build
```

Watch assets during development:
```bash
npm run dev
```

## Deployment

See `DEPLOYMENT-CHECKLIST.md` for deployment preparation.

### Deploy on Coolify (Nixpacks)

This repository includes `nixpacks.toml` configured for Laravel + Vite builds.

Use these settings in Coolify:

- **Build Pack:** Nixpacks
- **Port:** `8080` (or keep default and let Coolify map it)
- **Start Command:** leave empty (already defined in `nixpacks.toml`)
- **Base Directory:** repository root

Recommended environment variables in Coolify:

```env
APP_NAME=KaptenCarwash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_KEY=base64:your_generated_app_key

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

Generate `APP_KEY` locally if needed:

```bash
php artisan key:generate --show
```

Recommended post-deploy command in Coolify:

```bash
php artisan migrate --force && php artisan optimize:clear && php artisan optimize && php artisan storage:link
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
