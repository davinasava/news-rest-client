# News REST Client

Aplikasi REST Client sederhana berbasis PHP yang menggunakan **cURL** untuk mengambil data berita dari **NewsAPI.org**.

## Fitur
- Mengambil berita melalui endpoint `GET /v2/top-headlines`.
- Filter kategori berita.
- Pencarian berita berdasarkan kata kunci.
- Menampilkan judul, sumber, tanggal, gambar, deskripsi, dan tautan artikel.
- Penanganan error cURL dan error dari API.
- API key disimpan di file konfigurasi lokal dan tidak diunggah ke GitHub.

## Teknologi
- PHP 8+
- PHP cURL
- HTML dan CSS
- NewsAPI.org

## Cara menjalankan di XAMPP
1. Ekstrak folder project ke `C:\xampp\htdocs\news-rest-client`.
2. Pastikan Apache aktif melalui XAMPP Control Panel.
3. Pastikan ekstensi cURL aktif di `php.ini` (hapus tanda `;` pada `extension=curl`, jika ada), lalu restart Apache.
4. Salin `config/config.example.php` menjadi `config/config.php`.
5. Daftar/login di https://newsapi.org/ untuk mendapatkan API key.
6. Masukkan API key ke `config/config.php`.
7. Buka `http://localhost/news-rest-client/`.

## Endpoint yang digunakan
`GET https://newsapi.org/v2/top-headlines`

Parameter yang digunakan:
- `category`: kategori berita.
- `q`: kata kunci pencarian (opsional).
- `pageSize`: jumlah artikel yang diminta.

API key dikirim melalui HTTP header `X-Api-Key`.

## Struktur folder
```text
news-rest-client/
├── assets/
│   └── style.css
├── config/
│   ├── api.php
│   └── config.example.php
├── .gitignore
├── index.php
└── README.md
```

## Catatan keamanan
Jangan unggah `config/config.php` atau membagikan API key. File tersebut sudah dimasukkan ke `.gitignore`. Saat mengunggah project ke GitHub, sertakan `config.example.php` sebagai contoh konfigurasi.

## Referensi
- Tutorial: https://rudyekoprasetya.wordpress.com/2020/03/09/membuat-rest-client-dengan-curl-di-php-bag-2/
- NewsAPI Top Headlines: https://newsapi.org/docs/endpoints/top-headlines
- NewsAPI Authentication: https://newsapi.org/docs/authentication
