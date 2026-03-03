# DCS-Manila

Run DCS Manila locally
1. Prerequisites
PHP 7.4+ (with PDO MySQL)
MySQL or MariaDB
On Windows you can use XAMPP, WAMP, Laragon, or install PHP and MySQL separately.

2. Create the database
Start MySQL (e.g. from XAMPP Control Panel or your stack).

Create a database and user (or use root with no password for local dev):

CREATE DATABASE dcs_manila CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
Import the schema and data:

In phpMyAdmin: select dcs_manila → Import → choose scripts/epiz_31524705_dcs_manila.sql → Go.

Or from command line (adjust path and credentials):

mysql -u root -p dcs_manila < scripts/epiz_31524705_dcs_manila.sql
3. Optional: use a .env file
The app defaults to localhost, database dcs_manila, user root, no password. If that matches your setup, you can skip this.

To override (e.g. different DB name or password), copy .env.example to .env and set:

DB_HOST=localhost
DB_NAME=dcs_manila
DB_USER=root
DB_PASSWORD=
4. Create upload folder (for avatars)
Create the folder so image uploads don’t fail:

Windows: mkdir assets\img\upload
Linux/macOS: mkdir -p assets/img/upload
5. Start the app
Open a terminal in the project root (where index.php and login.php are) and run:

php -S localhost:8000
Then in the browser open:

Home / tracking: http://localhost:8000
Login: http://localhost:8000/login.php
Default login from the SQL dump: admin@admin.com / admin (change after first login).

6. If you use Apache/XAMPP instead
Put the project inside the web root (e.g. htdocs/dcsmanila or www/dcsmanila).
Ensure the database (and optional .env) are set as above.
Open: http://localhost/dcsmanila or http://localhost/dcsmanila/login.php.
Summary: Create DB → import scripts/epiz_31524705_dcs_manila.sql → (optional) .env → create assets/img/upload → run php -S localhost:8000 in project root → open http://localhost:8000/login.php
