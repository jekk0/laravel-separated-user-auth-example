# Laravel Separated User Auth(JWT) Example

![Build Status](https://github.com/jekk0/laravel-separated-user-auth-example/actions/workflows/pipeline.yml/badge.svg?branch=main)

### This is an example implementation of Separated User Auth using the [JWT package](https://github.com/jekk0/jwt-auth).

It demonstrates a role-based authentication system where different user types (User, Admin, Company) are stored separately in the database. The implementation includes JWT-based authentication, token validation, and multi-session support

### 🔹 Features
- Role-Based Authentication – Separate authentication flows for different user types.
- JWT Multi-Session Support – Allows users to have multiple active sessions.
- RESTful API Ready – Can be easily integrated into any frontend application.

### 📌 Installation & Usage
**Clone the repository:**

```shell
git clone https://github.com/jekk0/laravel-separated-user-auth-example.git
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

**Generate OpenApi docs**

```shell
php artisan l5-swagger:generate
```

**Run application**

```shell
php artisan serve
```

**API Documentation**

[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

