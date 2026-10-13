# Think Ocean

A production-ready Laravel 12 application for Think Ocean public website and its protected content-management system.

## Requirements

- PHP 8.2 or newer
- MySQL 8 or MariaDB 10.6+
- Composer 2
- Node.js 20+ and npm

## Local installation

1. Clone the repository and enter the project folder.
2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Copy `.env.example` to `.env` and generate an application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

4. Create an empty MySQL database named `omney_safari`. Update the `DB_*` values in `.env` if your credentials differ.
5. Create the schema and initial content:

   ```bash
   php artisan migrate --seed
   ```

6. Create or promote the Think Ocean super administrator:

   ```bash
   php artisan think-ocean:create-super-admin ogeof49@gmail.com
   ```

7. Build the frontend and start Laravel:

   ```bash
   npm run build
   php artisan serve
   ```

Open `http://127.0.0.1:8000/think-ocean`. The CMS is at `http://127.0.0.1:8000/think-ocean/admin`.

## Development

```bash
composer run dev
```

## Production deployment

Set `APP_ENV=production`, `APP_DEBUG=false`, a secure `APP_KEY`, and production database and mail credentials. Then run:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

Point the web server document root to `public`. Never commit `.env`, payment credentials, or other secrets.

## Tests and formatting

```bash
php artisan test --compact
vendor/bin/pint --format agent
```

## Think Ocean defaults

- Public page: `/think-ocean`
- CMS: `/think-ocean/admin`
- Contact email: `thinkocean@gmail.com`
- Super-admin email: `ogeof49@gmail.com`
