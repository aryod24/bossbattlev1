<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php" alt="PHP 8.3"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS"></a>
    <a href="https://alpinejs.dev"><img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js"></a>
</p>

# 🎮 CodeBossArena — Adaptive Learning Platform

**CodeBossArena** adalah platform pembelajaran berbasis gamifikasi yang mengubah kuis menjadi pertarungan RPG. Mahasiswa belajar PHP melalui sistem **Pre-Test Adaptive Learning** yang menempatkan mereka di level yang sesuai kemampuan.

## ✨ Fitur Utama

- 🎯 **Pre-Test Placement** — 30 soal campuran menentukan section awal (Easy / Medium / Hard)
- 🏰 **Solo Raid** — Jelajahi map berisi materi dan kuis interaktif
- ⚔️ **Boss Battle** — Jawaban benar = damage ke boss; kalahkan boss untuk menang
- 🏆 **RPG Progression** — Kumpulkan XP, naik level, dan unlock 5+ badge unik
- 📊 **Dosen Dashboard** — Monitor progress mahasiswa di tiap event

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, PHP 8.3 |
| Database | MySQL 8.0 |
| Frontend | Tailwind CSS 3.4, Alpine.js 3.x |
| Editor | EasyMDE, Material Symbols |

## 🚀 Instalasi

```bash
git clone <repository-url>
cd codebossarena

composer install && npm install

cp .env.example .env
php artisan key:generate

php artisan migrate && php artisan db:seed

npm run build
php artisan serve
```

## 🎭 Role Pengguna

| Role | Akses |
|------|-------|
| **Student** | Pre-test, Solo Raid, Boss Battle, Leaderboard |
| **Dosen** | Kelola events, monitor progress, question bank |
| **Admin** | Full system access, manajemen user & badge |

## ⚙️ Requirements

- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js & NPM

---

<p align="center">
  Built with ❤️ for Gamified Education<br>
  <strong>CodeBossArena</strong> — Adaptive Learning Platform<br>
  © 2026
</p>