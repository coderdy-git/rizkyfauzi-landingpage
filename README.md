# 👨‍💼 Rizky Fauzi - Professional CV & Portfolio

Website portofolio dan Curriculum Vitae (CV) interaktif milik Rizky Fauzi. Dibangun menggunakan teknologi web modern dengan antarmuka yang bersih, responsif, dan elegan. Website ini menggunakan pendekatan **Flat-file CMS**, yang berarti seluruh data teks dan konten disimpan di dalam file JSON, sehingga sangat ringan dan tidak memerlukan konfigurasi *database* (MySQL/dsb).

## ✨ Fitur Utama

- **🌍 Multi-bahasa (Bilingual):** Mendukung Bahasa Indonesia (default) dan Bahasa Inggris secara dinamis tanpa perlu me-reload halaman (berkat fungsionalitas JavaScript dan `data-i18n`).
- **🚀 Super Ringan & Cepat:** Semua konten portofolio, teks, dan pengaturan dibaca secara instan dari `data/content.json`.
- **🤖 Terintegrasi AI Chatbot:** Dilengkapi dengan asisten virtual AI cerdas (menggunakan integrasi Gemini API) yang siap menjawab pertanyaan perekrut seputar pengalaman dan keahlian secara otomatis.
- **📱 Desain Responsif:** Tampil sempurna di perangkat *desktop*, *tablet*, maupun *smartphone*.
- **🔍 SEO Optimized:** Sudah dilengkapi dengan meta tags, Open Graph, dan struktur HTML5 semantik untuk hasil pencarian terbaik di Google.
- **📊 Statistik Pengunjung:** Memiliki skrip pencatat jumlah tayangan (views) dan deteksi IP yang tersimpan secara lokal di `data/stats.json`.

## 🛠️ Teknologi yang Digunakan

- **Frontend:** HTML5, Vanilla CSS3 (dengan sistem CSS Variables), Vanilla JavaScript.
- **Backend:** PHP 8+ (hanya untuk *proxy* keamanan ke Gemini API dan perekaman statistik).
- **Data Storage:** JSON.
- **Icons:** FontAwesome 6.5.1.
- **Fonts:** Google Fonts (Poppins).

## 🚀 Panduan Instalasi (Menjalankan di Lokal)

Jika Anda ingin menjalankan atau memodifikasi *project* ini di komputer lokal, ikuti langkah-langkah berikut:

### Prasyarat:
- PHP 8.0 atau versi yang lebih baru.
- Node.js & NPM (Opsional, hanya untuk *development server*).

### Langkah-langkah:

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/coderdy-git/rizkyfauzi-landingpage.git
   cd rizkyfauzi-landingpage
   ```

2. **Atur API Key untuk Chatbot (Wajib jika ingin fitur AI berfungsi):**
   - Buat file baru bernama `.env` di direktori utama (sejajar dengan `index.php`).
   - Masukkan API Key dari Google Gemini seperti berikut:
     ```env
     GEMINI_API_KEY=masukkan_api_key_gemini_anda_disini
     ```

3. **Jalankan Server Lokal:**
   Anda bisa menggunakan dua cara:
   
   **Cara 1 (Menggunakan NPM):**
   ```bash
   npm install
   npm start
   ```
   *(Perintah ini akan menjalankan development server di `localhost:8000`)*

   **Cara 2 (Menggunakan PHP murni):**
   ```bash
   php -S localhost:8000
   ```

4. **Buka di Browser:**
   Kunjungi [http://localhost:8000](http://localhost:8000).

## 📂 Struktur Direktori

```text
├── assets/
│   └── img/                 # Folder untuk gambar (profil, ikon)
├── data/
│   ├── .htaccess            # Mengamankan file JSON dari akses luar
│   ├── content.json         # Pusat data seluruh teks dan konten website
│   └── stats.json           # Log pengunjung otomatis (tergenerate oleh sistem)
├── .env                     # File rahasia berisi GEMINI_API_KEY (tidak di-commit)
├── .gitignore               # Daftar file yang diabaikan Git
├── chat.php                 # Skrip backend (PHP) penghubung ke Gemini API
├── index.php                # Halaman utama & kerangka HTML
├── package.json             # Konfigurasi npm scripts
├── robots.txt               # Aturan keamanan untuk web crawler/SEO
└── style.css                # File CSS untuk mempercantik antarmuka
```

## 🌐 Deployment (Cara Hosting ke cPanel)

Website ini sangat praktis untuk di-deploy ke *Shared Hosting* mana pun yang mendukung PHP:
1. Blok seluruh isi folder proyek ini, lalu kompres menjadi arsip **.ZIP**.
2. Masuk ke **cPanel** hosting Anda, buka **File Manager**, dan arahkan ke direktori `public_html`.
3. *Upload* file ZIP tersebut, lalu *Extract*.
4. **PENTING:** Buat file `.env` di dalam `public_html` dan isikan `GEMINI_API_KEY` agar fitur chatbot bekerja dengan baik.
5. Selesai! Website portofolio CV Anda sudah bisa diakses oleh seluruh dunia.

---
*Dibuat untuk merepresentasikan portofolio profesional dan efisiensi teknologi web.*
