# Kazakh Hotels - Hotel Booking Web Application

A production-ready hotel booking web application for Kazakhstan, built with Laravel 12 and inspired by Otello.ru.

## 🛠 Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Database**: MySQL 8.4
- **ORM**: Eloquent
- **Frontend**: Blade + Tailwind CSS 4.0
- **Auth**: Custom session-based authentication
- **Storage**: Laravel Filesystem (local/S3-compatible)

## ✨ Features

### Public Features
- 🏠 Home page with search panel and infinite scroll
- 🏨 Hotels catalog with filters (city, price, rating) and sorting
- 🏨 Hotel detail pages with gallery, rooms table, and reviews
- ❤️ Favorites system
- 📅 Booking flow with date selection and price calculation
- 💬 Reviews system (requires admin approval)
- 🌙 Global dark mode support

### User Features (Authenticated)
- 📖 Bookings management with tabs (Active, Past, Cancelled)
- 👤 User profile with booking history
- ❤️ Favorites management
- 💬 Write reviews

### Admin Features
- 🛡️ Admin panel with role-based access
- 🏨 Hotels CRUD with image upload
- 🛏️ Rooms CRUD
- 👥 User management (ban/unban)
- 📅 Bookings management and status updates
- 💬 Reviews moderation (approve/reject)
- 📊 Statistics dashboard

## 📋 Requirements

- PHP 8.3+
- Composer
- MySQL 8.4+
- Node.js & NPM
- Laravel 12

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd kazakh-hotels
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update `.env` file with your database credentials**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kazakh_hotels
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```

   This will create:
   - 1 admin user (admin@example.com / password)
   - 10 regular users
   - 24 hotels across 8 cities
   - Sample rooms, bookings, favorites, and reviews

9. **Build assets**
   ```bash
   npm run build
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```

    And in another terminal:
    ```bash
    npm run dev
    ```

## 👤 Default Admin Account

After seeding:
- **Email**: admin@example.com
- **Password**: password

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── ...             # Main controllers
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form requests
├── Models/                 # Eloquent models
└── Policies/               # Authorization policies

database/
├── migrations/             # Database migrations
└── seeders/                # Database seeders

resources/
├── css/                    # Tailwind CSS
├── js/                     # JavaScript
└── views/                  # Blade templates
    ├── admin/              # Admin views
    ├── auth/               # Auth views
    ├── bookings/           # Booking views
    ├── hotels/             # Hotel views
    └── layouts/            # Layout templates

routes/
└── web.php                 # Web routes
```

## 🎨 UI/UX Features

- **Dark Mode**: Global dark theme with localStorage persistence
- **Responsive Design**: Mobile, tablet, and desktop support
- **Modern UI**: Clean design with rounded cards and smooth animations
- **Accent Color**: #38b000 (green)
- **Fonts**: Inter / Nunito

## 🔐 Authentication

The application uses custom session-based authentication. Routes are protected with:
- `auth` middleware for authenticated users
- `admin` middleware for admin-only routes

## 📝 Database Schema

- **users**: User accounts with role and ban status
- **hotels**: Hotel information
- **rooms**: Room details per hotel
- **bookings**: User bookings
- **favorites**: User favorite hotels
- **reviews**: Hotel reviews (pending/approved/rejected)

## 🧪 Testing

```bash
php artisan test
```

## 📦 Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Build assets: `npm run build`
6. Ensure storage link exists: `php artisan storage:link`

## 🤝 Contributing

This is an educational project. Feel free to fork and modify as needed.

## 📄 License

MIT License

## 🎯 Features Checklist

- ✅ Global dark theme
- ✅ Home page with search
- ✅ Hotels catalog with filters
- ✅ Hotel detail pages
- ✅ Booking system
- ✅ Favorites
- ✅ Reviews with moderation
- ✅ User profile
- ✅ Admin panel
- ✅ Role-based access
- ✅ Image uploads
- ✅ Responsive design

---

Built with ❤️ using Laravel 12
