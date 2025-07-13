# Employee Management API - Documentation

Sistem manajemen data karyawan berbasis Laravel API dengan autentikasi menggunakan Sanctum.
API ini mencakup fitur login, logout, manajemen karyawan, dan divisi, serta memiliki autentikasi token dan response JSON standar.

---

## Base URL

```
domain
```

---

## Autentikasi

API ini menggunakan **Laravel Sanctum**.

* Token dikirim melalui header:

```
Authorization: Bearer <token>
```

* Endpoint `login` hanya bisa diakses jika BELUM login.
* Semua endpoint lainnya hanya bisa diakses SETELAH login.

---

## Endpoints

### 1. Login Admin

**Endpoint:** `/login`
**Method:** `POST`
**Auth:** Tidak perlu token, tetapi akan ditolak jika sudah login.

#### Request:

```json
{
  "username": "admin",
  "password": "pastibisa"
}
```

#### Response (Sukses):

```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "token": "...",
    "admin": {
      "id": "uuid",
      "name": "Admin",
      "username": "admin",
      "phone": "0812xxx",
      "email": "admin@example.com"
    }
  }
}
```

#### Response (Jika sudah login):

```json
{
  "status": "error",
  "message": "Anda sudah login."
}
```

---

### 2. Logout Admin

**Endpoint:** `/logout`
**Method:** `POST`
**Auth:** Harus login

#### Response:

```json
{
  "status": "success",
  "message": "Berhasil logout"
}
```

---

### 3. Get All Employees

**Endpoint:** `/employees`
**Method:** `GET`
**Auth:** Harus login
**Filter:** Opsional `name`, `division_id`

#### Request Contoh (Query Params):

```
/employees?name=adi&division_id=uuid
```

#### Response:

```json
{
  "status": "success",
  "message": "Data karyawan berhasil diambil",
  "data": {
    "employees": [
      {
        "id": "uuid",
        "image": "url",
        "name": "Nama",
        "phone": "0812xxx",
        "division": {
          "id": "uuid",
          "name": "Divisi"
        },
        "position": "Jabatan"
      }
    ]
  },
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    ...
  }
}
```

---

### 4. Create Employee

**Endpoint:** `/employees`
**Method:** `POST`
**Auth:** Harus login

#### Request:

```json
{
  "image": "file",
  "name": "Nama Karyawan",
  "phone": "0812xxx",
  "division": "uuid-divisi",
  "position": "Jabatan"
}
```

#### Response:

```json
{
  "status": "success",
  "message": "Karyawan berhasil ditambahkan"
}
```

---

### 5. Update Employee

**Endpoint:** `/employees/{id}`
**Method:** `PUT`
**Auth:** Harus login

#### Request:

```json
{
  "image": "file",
  "name": "Nama Baru",
  "phone": "No Baru",
  "division": "uuid-divisi",
  "position": "Jabatan Baru"
}
```

#### Response:

```json
{
  "status": "success",
  "message": "Data karyawan berhasil diperbarui"
}
```

---

### 6. Delete Employee

**Endpoint:** `/employees/{id}`
**Method:** `DELETE`
**Auth:** Harus login

#### Response:

```json
{
  "status": "success",
  "message": "Data karyawan berhasil dihapus"
}
```

---

### 7. Get All Divisions

**Endpoint:** `/divisions`
**Method:** `GET`
**Auth:** Harus login
**Filter:** Opsional `name`

#### Request:

```
/divisions?name=IT
```

#### Response:

```json
{
  "status": "success",
  "message": "Data divisi berhasil diambil",
  "data": {
    "divisions": [
      {
        "id": "uuid",
        "name": "IT"
      }
    ]
  },
  "pagination": {
    "current_page": 1,
    ...
  }
}
```

---

## Error Response Format

```json
{
  "status": "error",
  "message": "Pesan kesalahan",
  "errors": {
    "field": ["Alasan error"]
  }
}
```

---

## Aturan Akses API

| Endpoint          | Auth Diperlukan | Keterangan                           |
| ----------------- | --------------- | ------------------------------------ |
| POST /login       | ❌               | Ditolak jika sudah login             |
| POST /logout      | ✅               | Hanya bisa jika sudah login          |
| GET /employees    | ✅               | Filter opsional by name/division_id |
| POST /employees   | ✅               | Buat karyawan                        |
| PUT /employees    | ✅               | Update karyawan                      |
| DELETE /employees | ✅               | Hapus karyawan                       |
| GET /divisions    | ✅               | Filter opsional by name              |

---
## JANGAN LUPA SELAIN Endpoint /login, wajib mengirimkan bearer token

## Setup Project Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## Admin Default (Seeder)

| Username | Password  |
| -------- | --------- |
| admin    | pastibisa |

---



