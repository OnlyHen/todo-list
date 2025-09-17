Backend Technical Test - Todo List API
Proyek ini adalah implementasi REST API untuk aplikasi Todo List sebagai bagian dari Technical Test Backend Developer di PT. Dynamic Talenta Navigator (Talenavi).

Aplikasi ini dibangun menggunakan Laravel Framework dan menyediakan serangkaian endpoint untuk mengelola tugas, menghasilkan laporan dalam format Excel yang detail, serta menyediakan data agregat untuk kebutuhan visualisasi grafik.

Arsitektur yang digunakan mengimplementasikan best practice seperti Service Layer untuk memisahkan logika bisnis dari controller dan Form Request untuk validasi yang terpusat dan rapi, memastikan kode yang bersih, mudah dikelola, dan tangguh.

Fitur Utama
Create Todo: Menambahkan data tugas baru dengan validasi input yang ketat.

Excel Report: Mengunduh daftar tugas dalam format .xlsx dengan dukungan filter dinamis serta baris ringkasan di bagian akhir.

Chart Data: Menyediakan data ringkasan (agregat) untuk visualisasi berdasarkan status, prioritas, dan assignee.

Prasyarat
PHP >= 8.2

Composer

MySQL (atau database SQL lainnya)

Postman (untuk pengujian API)

🚀 Instalasi & Setup
Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan lokal.

1. Clone Repositori
   Bash

git clone [URL_REPOSITORI_ANDA]
cd nama-folder-proyek 2. Install Dependensi
Gunakan Composer untuk menginstal semua dependensi PHP yang dibutuhkan.

Bash

composer install 3. Konfigurasi Lingkungan
Salin file .env.example menjadi .env dan sesuaikan konfigurasinya.

Bash

cp .env.example .env
Buka file .env dan atur koneksi database Anda. Pastikan Anda sudah membuat database kosong untuk proyek ini.

Code snippet

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talenavi_todolist
DB_USERNAME=root
DB_PASSWORD= 4. Generate Application Key
Jalankan perintah Artisan berikut untuk menghasilkan kunci aplikasi yang unik.

Bash

php artisan key:generate 5. Jalankan Migrasi Database
Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi di dalam database Anda.

Bash

php artisan migrate 6. Jalankan Server Lokal
Aplikasi Anda sekarang siap dijalankan.

Bash

php artisan serve
Secara default, API akan dapat diakses melalui http://127.0.0.1:8000.

📖 Dokumentasi API
Berikut adalah rincian endpoint yang tersedia.

Base URL: http://127.0.0.1:8000/api

1. Create a New Todo
   Menambahkan tugas baru ke dalam daftar.

Endpoint: POST /todos

Headers:

Content-Type: application/json

Accept: application/json

Body (raw JSON):

JSON

{
"title": "Selesaikan Technical Test",
"assignee": "Nama Anda",
"due_date": "2025-12-31",
"time_tracked": 180,
"status": "In Progress",
"priority": "High"
}
Validasi:

Semua field wajib diisi.

due_date tidak boleh tanggal yang sudah lewat.

status harus salah satu dari: Pending, In Progress, Completed.

priority harus salah satu dari: Low, Medium, High.

Contoh Respons Sukses (201 Created):

JSON

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
} 2. Generate Excel Report
Mengunduh laporan todo dalam format .xlsx. Mendukung filter melalui query parameters.

Endpoint: GET /todos/report

Query Parameters (Opsional):

status: Filter berdasarkan status (e.g., ?status=Completed)

priority: Filter berdasarkan prioritas (e.g., ?priority=High)

assignee: Filter berdasarkan nama assignee (pencarian parsial, e.g., ?assignee=Nama)

due_date_from: Filter tanggal mulai (format YYYY-MM-DD)

due_date_to: Filter tanggal selesai (format YYYY-MM-DD)

Contoh Penggunaan:

GET /api/todos/report (tanpa filter, mengunduh semua data)

GET /api/todos/report?status=Completed&priority=High

Respons Sukses (200 OK):

Akan langsung mengunduh file todos_report.xlsx.

Untuk menguji di Postman, gunakan fitur "Send and Download".

3. Get Chart Data
   Menyediakan data agregat dalam format JSON untuk visualisasi.

Endpoint: GET /chart

Query Parameters (Wajib):

type: Menentukan jenis ringkasan. Nilai yang valid: status, priority, assignee.

a. Berdasarkan Status
Request: GET /api/chart?type=status

Contoh Respons Sukses (200 OK):

JSON

{
"Pending": 5,
"In Progress": 2,
"Completed": 10
}
b. Berdasarkan Prioritas
Request: GET /api/chart?type=priority

Contoh Respons Sukses (200 OK):

JSON

{
"Low": 8,
"Medium": 7,
"High": 2
}
c. Berdasarkan Assignee
Request: GET /api/chart?type=assignee

Contoh Respons Sukses (200 OK):

JSON

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
