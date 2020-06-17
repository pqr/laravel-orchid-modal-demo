# Setup

```
composer install
php artisan key:generate
```

Create a new database and update the `.env` file with credentials and add the URL of your application to the variable `APP_URL`.

## Orchid - Laravel Admin Panel
https://orchid.software/en/docs/installation/

Run the installation process by running the command:
```
php artisan orchid:install
```

### Create user
To create a user with maximum permissions you can run the following command with a username, email and password:
```
php artisan orchid:admin admin admin@admin.com password
```

