# Helpdesk Application

A web-based Helpdesk System for a maritime shipping company, allowing users to manage support tickets, organizations, and vessels. Built with **Laravel 10** (Backend) and **Vue 3 + Tailwind CSS** (Frontend).

## 🚀 Tech Stack

- **Backend:** PHP 8.2, Laravel 10, MySQL
- **Frontend:** Vue.js 3 (Composition API), Vite, Tailwind CSS, Axios
- **Deployment:** Docker Compose (Optional)

## 📂 Project Structure

```text
Helpdesk-Project/
├── helpdesk-backend/   # Laravel API & Database Migrations
├── helpdesk-frontend/  # Vue.js User Interface
├── docker-compose.yml  # Container orchestration config
└── README.md           # This file

🛠️ Setup Instructions (Manual)
If you prefer running the application manually on your local machine.

1. Backend Setup (Laravel)
Requirements: PHP >= 8.1, Composer, MySQL.

cd helpdesk-backend

# 1. Install PHP dependencies
composer install

# 2. Copy .env file and configure database settings
cp .env.example .env
# (Ensure you create a database named 'helpdesk_db' in your MySQL)

# 3. Generate App Key
php artisan key:generate

# 4. Run Migrations & Seeders (Mock Data)
php artisan migrate:fresh --seed

# 5. Start the Server
php artisan serve

Backend will run at: http://127.0.0.1:8000

2. Frontend Setup (Vue.js)
Requirements: Node.js >= 20.

cd helpdesk-frontend

# 1. Install Node dependencies
npm install

# 2. Start the Development Server
npm run dev

Frontend will run at: http://127.0.0.1:5173

🐳 Setup Instructions (Docker) - Recommended
If you have Docker installed, you can run the entire stack with a single command.

# 1. Build and Start Containers
docker-compose up -d --build

# 2. Setup Database (Run inside the container)
docker-compose exec app php artisan migrate:fresh --seed

🔑 Login Credentials
The system comes with pre-seeded users for testing:
  - Email: admin@helpdesk.com
  - Password: password

(Other users are also created with the same password)

✅ Features
  - Authentication: Secure Login/Logout with Sanctum Tokens.
  - Ticket Management: Create, Read, Update, Delete (CRUD) tickets.
  - Rich UI: Ticket Details page designed to match the specific mockup.
  - Dynamic Data: SLA Timer calculation based on priority and real-time form updates.
  - Dependency Dropdowns: Auto-fetch Vessels based on selected Organizat

- **Dependency Dropdowns:** Auto-fetch Vessels based on selected Organization.

---

**Author:** Mr. Rattapon Hongtanarat (Safe)
**Date:** 2025-01-03