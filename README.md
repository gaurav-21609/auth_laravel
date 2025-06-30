# Laravel Authentication System with Role-Based Redirection

This project implements a basic authentication system using Laravel, featuring:

* User login & registration
* Password reset
* CSRF protection
* Role-based redirection (e.g., admin/user dashboards)

---

## Features

* Login form with CSRF protection
* Redirect users based on role (`admin`, `user`, etc.)
* Remember Me functionality
* Password reset support
* Clean UI using Blade components

---

## Requirements

* PHP >= 8.1
* Composer
* Laravel = 11
* MySQL or compatible database
* Node.js & NPM 
---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-auth-app.git
cd laravel-auth-app
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your database credentials.

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Seed Roles (Optional)

If you're using a `roles` table:

```bash
php artisan db:seed --class=RoleSeeder
```

---

## Authentication Setup

### Login Form

Found in `resources/views/auth/login.blade.php`:

```blade
<form method="POST" action="{{ route('login.store') }}">
    @csrf
    <!-- Email, Password, Remember Me, Submit -->
</form>
```

### Login Controller

Create a controller: `LoginController.php`

```bash
php artisan make:controller Auth/LoginController
```

Add the logic:

```php
public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role->name === 'admin') {
            return redirect()->route('admin_dashboard');
        }

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}
```

---

## Route Definitions

Update `routes/web.php`:

```php
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin_dashboard');
});
```

---

## Role Relationship in User Model

In `App\Models\User.php`:

```php
public function role()
{
    return $this->belongsTo(Role::class);
}
```

---

## Testing

You can log in using tinker:

```bash
php artisan tinker

// Create user
$user = new \App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('password');
$user->role_id = 1; // assume 1 = admin
$user->save();
```

---
