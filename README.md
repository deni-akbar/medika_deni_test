# How to Run Project

1.  Clone this project

2.  Run Composer Install

`composer install`

3. Setup db pada file .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT={port_db_anda}
DB_DATABASE={nama_db_anda}
DB_USERNAME={user_db_anda}
DB_PASSWORD=
```

4. Generate app key

`php artisan key:generate`

5. Run Migration and Seed

`php artisan migrate --seed`

6. Run Project

`php artisan serve`

User Admin Login:

```
email: admin@example.com
password: admin
```
