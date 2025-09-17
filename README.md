# Backend Technical Test - Todo List API

Proyek ini adalah implementasi **REST API** untuk aplikasi **Todo List** sebagai bagian dari _Technical Test Backend Developer_ di **PT. Dynamic Talenta Navigator (Talenavi)**.

Aplikasi ini dibangun menggunakan **Laravel Framework** dan menyediakan serangkaian _endpoint_ untuk mengelola tugas, menghasilkan laporan dalam format Excel yang detail, serta menyediakan data agregat untuk kebutuhan visualisasi grafik.

Arsitektur yang digunakan mengimplementasikan _best practice_ seperti **Service Layer** untuk memisahkan logika bisnis dari _controller_ dan **Form Request** untuk validasi yang terpusat dan rapi. Dengan begitu, kode tetap bersih, mudah dikelola, dan tangguh.

---

## ✨ Fitur Utama

-   **Create Todo:** Menambahkan data tugas baru dengan validasi input yang ketat.
-   **Excel Report:** Mengunduh daftar tugas dalam format `.xlsx` dengan dukungan filter dinamis serta baris ringkasan di bagian akhir.
-   **Chart Data:** Menyediakan data ringkasan (agregat) untuk visualisasi berdasarkan status, prioritas, dan _assignee_.

---

## 📦 Prasyarat

-   PHP >= 8.2
-   Composer
-   MySQL (beserta Database Explorer seperti HeidiSQL)
-   Postman (untuk pengujian API)

---

## 🚀 Instalasi & Setup

### 1. Clone Repositori

```bash
git clone https://github.com/OnlyHen/todo-list.git
cd todo-list
```

### 2. Install Dependensi

Gunakan Composer untuk menginstal semua dependensi PHP yang dibutuhkan.

```bash
composer install
```

### 3. Konfigurasi Lingkungan

Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasinya.

```bash
cp .env.example .env
```

Buka file `.env` dan atur koneksi database Anda. Pastikan sudah membuat database kosong untuk proyek ini.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 #localhost
DB_PORT=3306
DB_DATABASE=talenavi_todolist #nama database anda
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

Jalankan perintah Artisan berikut untuk menghasilkan kunci aplikasi yang unik.

```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database

Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi.

```bash
php artisan migrate
```

### 6. Jalankan Server Lokal

```bash
php artisan serve
```

Untuk simulasi, API dapat diakses melalui localhost:  
👉 `http://127.0.0.1:8000`

---

## 📖 Dokumentasi API

**Base URL:** `http://127.0.0.1:8000/api`

---

### 1. Create a New Todo

**Endpoint:** `POST /todos`

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Body (raw JSON):**

```json
{
    "title": "Selesaikan Technical Test",
    "assignee": "Nama Anda",
    "due_date": "2025-12-31",
    "time_tracked": 180,
    "status": "In Progress",
    "priority": "High"
}
```

**Validasi:**

-   Semua field wajib diisi.
-   `due_date` tidak boleh tanggal yang sudah lewat.
-   `status` harus salah satu dari: `Pending`, `In Progress`, `Completed`.
-   `priority` harus salah satu dari: `Low`, `Medium`, `High`.

**Contoh Respons Sukses (201 Created):**

```json
{
    "message": "Todo created successfully",
    "data": {
        "title": "Selesaikan Technical Test",
        "assignee": "Nama Anda",
        "due_date": "2025-12-31",
        "time_tracked": 180,
        "status": "In Progress",
        "priority": "High",
        "updated_at": "2025-09-17T04:55:00.000000Z",
        "created_at": "2025-09-17T04:55:00.000000Z",
        "id": 1
    }
}
```

---

### 2. Generate Excel Report

Mengunduh laporan todo dalam format `.xlsx`.  
Mendukung filter melalui query parameters.

**Endpoint:** `GET /todos/report`

**Query Parameters (opsional):**

-   `status` → Filter berdasarkan status (contoh: `?status=Completed`)
-   `priority` → Filter berdasarkan prioritas (contoh: `?priority=High`)
-   `assignee` → Filter berdasarkan nama assignee (pencarian parsial, contoh: `?assignee=Nama`)
-   `due_date_from` → Filter tanggal mulai (format `YYYY-MM-DD`)
-   `due_date_to` → Filter tanggal selesai (format `YYYY-MM-DD`)

**Contoh Penggunaan:**

```
GET /api/todos/report               (tanpa filter, semua data)
GET /api/todos/report?status=Completed&priority=High
```

**Respons Sukses (200 OK):**
Akan langsung mengunduh file `todos_report.xlsx`.  
Untuk menguji di Postman, gunakan fitur **Send and Download**.

---

### 3. Get Chart Data

Menyediakan data agregat dalam format JSON untuk visualisasi.

**Endpoint:** `GET /chart`

**Query Parameters (wajib):**

-   `type` → Menentukan jenis ringkasan. Nilai valid: `status`, `priority`, `assignee`.

---

#### a. Berdasarkan Status

**Request:**

```
GET /api/chart?type=status
```

**Contoh Respons Sukses:**

```json
{
    "Pending": 5,
    "In Progress": 2,
    "Completed": 10
}
```

---

#### b. Berdasarkan Prioritas

**Request:**

```
GET /api/chart?type=priority
```

**Contoh Respons Sukses:**

```json
{
    "Low": 8,
    "Medium": 7,
    "High": 2
}
```

---

#### c. Berdasarkan Assignee

**Request:**

```
GET /api/chart?type=assignee
```

**Contoh Respons Sukses:**

```json
{
    "Andi": {
        "total_todos": 5,
        "total_pending_todos": 1,
        "total_completed_todos": 4,
        "total_timetracked_completed_todos": 240
    },
    "Budi": {
        "total_todos": 3,
        "total_pending_todos": 2,
        "total_completed_todos": 1,
        "total_timetracked_completed_todos": 60
    }
}
```
