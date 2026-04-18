# Advanced Web Programming - Task 1 (Graduate Theses Parser)

This is my submission for Exercise 1, which involves parsing graduate theses from the university website and saving them to a database using Object-Oriented PHP principles within the Laravel framework.

## File Overview

To help navigate the project, here are the key components for this assignment:

* **The Interface:** `app/Contracts/iRadio.php` (Contains the `create`, `save`, and `read` methods)
* **The Main Logic:** `app/Classes/GraduateThesis.php` (Implements `iRadio` and handles the data creation and database operations)
* **Scraping Script:** `app/Console/Commands/ScrapeTheses.php` (The Artisan command that crawls `stup.ferit.hr` from page 2 to 6)
* **Database Setup:** `database/migrations/2026_04_18_000000_create_graduate_theses_table.php`

---

## Installation and Setup

Here are the steps to get the project working locally for grading.

**1. Clone the repository:**
```bash
git clone <your-repository-link>
cd laravel-exercise1-thesis
```

**2. Install dependencies:**
```bash
composer install
```

**3. Set up the database:**
Copy the `.env.example` file and rename it to `.env`. Ensure you have an empty local MySQL database created called `thesis`. Then, update the `.env` file with your credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thesis
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generate the app key:**
```bash
php artisan key:generate
```

**5. Run the migration:**
This will dynamically create the `graduate_theses` table with all the required columns.
```bash
php artisan migrate
```

---

## Running and Testing

**1. Scraping the site (Testing `create()` & `save()`):**
I linked the scraper to an Artisan command. When you run this, it will connect to the university site, retrieve the data from pages 2 through 6, parse the HTML, and save the entries into the local database:
```bash
php artisan scrape:theses
```

**2. Viewing the data (Testing `read()`):**
To verify that the data actually saved and can be retrieved properly, start the local server:
```bash
php artisan serve
```
Then, open your browser and navigate to `http://127.0.0.1:8000/`. I set up the home route to output all the parsed theses as raw JSON, fetching it directly via the `GraduateThesis->read()` method.