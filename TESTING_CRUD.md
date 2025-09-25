# Manual CRUD Testing Script

## 1. Test Authentication

### Register User Baru
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nama": "Test Penyewa API",
    "email": "testapi@example.com",
    "no_tlpn": "081999888777",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "penyewa"
  }'
```

### Login Admin
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@sewa.com",
    "password": "password"
  }'
```

### Login Owner
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "pemilik1@sewa.com",
    "password": "password"
  }'
```

### Login Penyewa
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "penyewa1@sewa.com",
    "password": "password"
  }'
```

## 2. Test Admin CRUD

**Catatan: Ganti YOUR_ADMIN_TOKEN dengan token dari login admin**

### Create User (Admin)
```bash
curl -X POST http://127.0.0.1:8000/api/admin/users \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nama": "Admin Created User",
    "email": "admincreated@example.com",
    "no_tlpn": "081888999000",
    "password": "password123",
    "role": "penyewa"
  }'
```

### Read Users (Admin)
```bash
curl -X GET "http://127.0.0.1:8000/api/admin/users?page=1" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Accept: application/json"
```

### Update User (Admin)
```bash
curl -X PUT http://127.0.0.1:8000/api/admin/users/20 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nama": "Updated Penyewa Name"
  }'
```

### Delete User (Admin) - Hati-hati! 
```bash
curl -X DELETE http://127.0.0.1:8000/api/admin/users/25 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Accept: application/json"
```

## 3. Test Owner CRUD Motors

**Catatan: Ganti YOUR_OWNER_TOKEN dengan token dari login owner**

### Create Motor (Owner)
```bash
curl -X POST http://127.0.0.1:8000/api/owner/motors \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "merk": "Honda API Test",
    "tipe_cc": "125",
    "no_plat": "B9999API"
  }'
```

### Read Motors (Owner)
```bash
curl -X GET http://127.0.0.1:8000/api/owner/motors \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -H "Accept: application/json"
```

### Update Motor (Owner)
```bash
curl -X PUT http://127.0.0.1:8000/api/owner/motors/YOUR_MOTOR_ID \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "merk": "Honda Updated API",
    "no_plat": "B9999UPD"
  }'
```

### Delete Motor (Owner)
```bash
curl -X DELETE http://127.0.0.1:8000/api/owner/motors/YOUR_MOTOR_ID \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -H "Accept: application/json"
```

## 4. Test Renter CRUD Bookings

**Catatan: Ganti YOUR_RENTER_TOKEN dengan token dari login penyewa**

### Get Available Motors (Renter)
```bash
curl -X GET http://127.0.0.1:8000/api/renter/motors \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Accept: application/json"
```

### Create Booking (Renter)
```bash
curl -X POST http://127.0.0.1:8000/api/renter/penyewaans \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "motor_id": 1,
    "tanggal_mulai": "2025-10-01",
    "tanggal_selesai": "2025-10-03",
    "tipe_durasi": "harian",
    "harga": 200000,
    "catatan": "Test booking via API"
  }'
```

### Read My Bookings (Renter)
```bash
curl -X GET http://127.0.0.1:8000/api/renter/penyewaans \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Accept: application/json"
```

### Update Booking (Renter)
```bash
curl -X PUT http://127.0.0.1:8000/api/renter/penyewaans/YOUR_BOOKING_ID \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "tanggal_mulai": "2025-10-02",
    "tanggal_selesai": "2025-10-04",
    "catatan": "Updated booking dates"
  }'
```

### Cancel Booking (Renter)
```bash
curl -X PATCH http://127.0.0.1:8000/api/renter/penyewaans/YOUR_BOOKING_ID/cancel \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Accept: application/json"
```

### Delete Booking (Renter)
```bash
curl -X DELETE http://127.0.0.1:8000/api/renter/penyewaans/YOUR_BOOKING_ID \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Accept: application/json"
```

## 5. Test Dashboard Stats

### Admin Dashboard Stats
```bash
curl -X GET http://127.0.0.1:8000/api/admin/dashboard/stats \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Accept: application/json"
```

### Owner Dashboard Stats
```bash
curl -X GET http://127.0.0.1:8000/api/owner/dashboard/stats \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -H "Accept: application/json"
```

### Renter Dashboard Stats
```bash
curl -X GET http://127.0.0.1:8000/api/renter/dashboard/stats \
  -H "Authorization: Bearer YOUR_RENTER_TOKEN" \
  -H "Accept: application/json"
```

## 6. Test File Upload (Owner)

### Upload Motor Photo
```bash
curl -X POST http://127.0.0.1:8000/api/owner/motors/1/upload-photo \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -F "photo=@/path/to/your/motor-photo.jpg"
```

### Upload Motor Document
```bash
curl -X POST http://127.0.0.1:8000/api/owner/motors/1/upload-document \
  -H "Authorization: Bearer YOUR_OWNER_TOKEN" \
  -F "dokumen_kepemilikan=@/path/to/your/document.pdf"
```