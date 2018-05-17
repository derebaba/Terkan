# Contribution guidelines

Terkan uses [Laravel](https://laravel.com/) and [Bootstrap](https://getbootstrap.com/) frameworks. Get familiar with them before you get started.

## How to set up environment

### Get code and packages

**Run all commands in root folder**

1. Clone this repository.
2. Install PHP (version >= 7).
3. Install [Composer](https://getcomposer.org/).
4. Run "composer install"<sup>[1](#footnote1)</sup>.

### Create local database

1. Run a local database server of your preference (I use PostgreSQL).
2. Create a file named ".env" in the root directory.
3. Fill it according to your database setup. Example:
```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=
```
4. Run "php artisan migrate"<sup>[1](#footnote1)</sup>. This will create the tables.
5. Run "composer dump-autoload"<sup>[1](#footnote1)</sup>.
6. Run "php artisan db:seed" to fill tables<sup>[1](#footnote1)</sup>.

### Finally

Run "php artisan serve"<sup>[1](#footnote1)</sup>. Go to "localhost:8000" in browser. You should be able to see the website.
#### Footnotes

<a name="footnote1">1</a>: Run these commands under Terkan folder.
