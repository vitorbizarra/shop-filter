# Shop Filter

A simple Laravel project demonstrating shop item filtering — built to run easily using **Laravel Sail** (Docker).

---

## Requirements
Before you start, make sure you have:
- **Docker** & **Docker Compose** (installed and running)
- **Git** (to clone the repository)

---

## Quick Start (Recommended)

1. **Clone the repository and enter the project folder**
    ```bash
    git clone git@github.com:vitorbizarra/shop-filter.git
    cd shop-filter
    ```

2. **Copy the environment file**
   ```bash
    cp .env.example .env
   ```

   > The app key will be generated later (after dependencies are installed).

3. **Install PHP dependencies using Composer inside a temporary container**

   ```bash
   docker run --rm \
     -u "$(id -u):$(id -g)" \
     -v $(pwd):/var/www/html \
     -w /var/www/html \
     laravelsail/php84-composer:latest \
     composer install --ignore-platform-reqs
   ```

4. **Install Node dependencies**

   ```bash
   ./vendor/bin/sail npm install
   ```

5. **Start Laravel Sail (Docker containers)**

   ```bash
   ./vendor/bin/sail up -d
   ```

6. **Initialize the application**

   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ./vendor/bin/sail npm run dev
   ```

7. **Visit the app**

   * [http://localhost](http://localhost)
     (or the port configured in your `.env` file)

---

## Common Commands

| Task                         | Command                                          |
| ---------------------------- | ------------------------------------------------ |
| Stop Sail containers         | `./vendor/bin/sail down`                         |
| Run an Artisan command       | `./vendor/bin/sail artisan <command>`            |
| Fresh migrate with seed data | `./vendor/bin/sail artisan migrate:fresh --seed` |
| Run automated tests          | `./vendor/bin/sail test`                         |
| Start Vite dev server        | `./vendor/bin/sail npm run dev`                  |

---

## Notes & Troubleshooting

* **Sail not found?**
  Ensure Composer dependencies are installed:

  ```bash
  composer install
  ```

* **Port conflicts?**
  Adjust the port mapping in `docker-compose.yml` or stop the conflicting service.

* **File permission issues?**
  Run the following inside Sail:

  ```bash
  ./vendor/bin/sail artisan storage:link
  ./vendor/bin/sail artisan cache:clear
  sudo chown -R $USER:www-data storage bootstrap/cache
  ```

* **Need to explore the code?**
  Check out:

  * [`routes/web.php`](routes/web.php) → app routes
  * [`vite.config.js`](vite.config.js) → Vite setup
  * [`database/seeders`](database/seeders) → initial data seeds
