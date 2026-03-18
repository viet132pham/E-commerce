# Ecommerce (Laravel + Next.js)

## Structure
- backend/  Laravel API
- frontend/ Next.js app

## Local dev (later)
- Backend: php artisan serve
- Frontend: npm run dev

## Deploy
Deployment is automated via GitHub Actions to a VPS using SSH and Docker Compose.

Required GitHub secrets:
- SSH_HOST
- SSH_USER
- SSH_KEY
- SSH_PORT (optional, default 22)
- APP_PATH (absolute path on server, e.g. /opt/ecommerce)

## Backend auth setup
The API auth uses Laravel Sanctum. After installing dependencies, run:
- php artisan sanctum:install
- php artisan migrate

## Admin access
Set a user as admin by updating the users table:
- UPDATE users SET is_admin = true WHERE email = 'you@example.com';
