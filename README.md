# 💬 Application Chat

Aplikasi web chat real-time yang dibangun dengan Laravel dan Vue.js, mendukung percakapan personal maupun grup dengan fitur live presence dan typing indicator.

## 🛠️ Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Vue.js + Vite
- **Database**: SQLite
- **Styling**: Tailwind CSS

## ✨ Fitur

- 🔐 Autentikasi user (register & login)
- 💬 Personal chat (1-on-1)
- 👥 Grup chat
- ⚡ Real-time messaging
- 🟢 User presence tracking (online/offline)
- ✍️ Typing indicator

## ⚙️ Persyaratan

Pastikan sudah terinstal:

- PHP >= 8.1
- Composer
- Node.js & npm
- SQLite

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/zaskiaazzura/application-chat.git
cd application-chat
```

### 2. Install Dependensi

```bash
# Backend
composer install

# Frontend
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, lalu pastikan konfigurasi database SQLite sudah seperti ini:

```env
DB_CONNECTION=sqlite
```

Kemudian buat file database SQLite:

```bash
touch database/database.sqlite
```

### 4. Migrasi Database

```bash
php artisan migrate
```

### 5. Jalankan Aplikasi

Buka **dua terminal** secara bersamaan:

```bash
# Terminal 1 - Backend
php artisan serve
```

```bash
# Terminal 2 - Frontend
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

## 📁 Struktur Folder

```
application-chat/
├── app/              # Logic backend Laravel
├── database/         # Migrasi & seeder
├── resources/
│   └── js/           # Komponen Vue.js
├── routes/           # Definisi route
└── public/           # Aset publik
```

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran. [MIT License](LICENSE)