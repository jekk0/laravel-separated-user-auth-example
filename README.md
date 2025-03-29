# Laravel Separated User Auth(JWT) Example

Laravel Separated User Auth is a multi-session authentication system based on JWT, designed for applications with distinct user roles (Admin, Company, User) stored in separate database tables or even different databases.

### 🔹 Features
- Role-Based Authentication – Separate authentication flows for different user types.
- JWT Multi-Session Support – Allows users to have multiple active sessions.
- RESTful API Ready – Can be easily integrated into any frontend application.

### 📌 Installation & Usage
**Clone the repository:**

```shell
git clone https://github.com/jekk0/separated-user-auth-example.git
```

**Install dependencies:**

```shell
cd separated-user-auth-example
composer install
```

**Configure your .env file and run the server:**

Generate certificates and add configuration to your .env file
```shell
$ php artisan jwtauth:generate-certificates

Copy and paste the content below into your .env file:

JWT_AUTH_PUBLIC_KEY=zvZFv5w3DuY3rZK901cnMM8UmV...
JWT_AUTH_PRIVATE_KEY=GaD9g0Xk5QHpzIJOIuEbUEOyJXQSpN...
```

**Create users tables:**

```shell
php artisan migrate
```

**Add default users:**

```shell
php artisan db:seed
```

**Run application**

```shell
php artisan serve
```
