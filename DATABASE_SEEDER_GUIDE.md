# 🚀 Dynamic Test Data Generator

## 📋 Overview
This system provides **dynamic, realistic test data** for the LuxuryMoto rental system. Each time you run the seeder, it generates **different realistic data** with proper relationships and constraints.

## 🎯 Features

### ✨ **Dynamic Data Generation**
- **Randomized realistic Indonesian names** each time
- **Unique email addresses** with proper formatting
- **50 different motor combinations** with varied brands/models
- **Realistic pricing** based on CC and brand
- **Sample bookings and transactions** for testing

### 🔒 **Data Safety**
- **Safely deletes old data** before inserting new data
- **Maintains foreign key relationships**
- **No primary key conflicts**
- **Auto-increment reset** for clean IDs

## 🚀 Usage Methods

### Method 1: Laravel Seeder (Recommended)
```bash
# Run the specific dynamic seeder
php artisan db:seed --class=DynamicTestDataSeeder

# OR run all seeders (includes DynamicTestDataSeeder)
php artisan db:seed
```

### Method 2: Direct SQL Execution
```bash
# Execute the SQL file directly in SQLite
sqlite3 database/database.sqlite < database/dynamic_test_data.sql
```

### Method 3: Fresh Migration + Seeding
```bash
# Complete reset: drop tables, migrate, and seed
php artisan migrate:fresh --seed
```

## 📊 Generated Data Structure

### 👥 **Users (9 total)**
- **1 Admin**: `admin@luxurymoto.com`
- **3 Pemilik (Owners)**: Dynamic names like `ahmad.pratama.pemilik@example.com`
- **5 Penyewa (Renters)**: Dynamic names like `dewi.lestari.penyewa@example.com`

### 🏍️ **Motors (50 total)**
- **Realistic brands**: Honda, Yamaha, Suzuki, Kawasaki
- **Popular models**: Beat, Vario, Mio, NMAX, CBR, Ninja, etc.
- **CC variations**: 100cc, 125cc, 150cc
- **Realistic plate numbers**: Indonesian format (B 1234 ABC)
- **Varied status**: available, rented, maintenance, verified

### 💰 **Pricing (Realistic)**
- **100cc**: Rp 40,000 - 70,000/day
- **125cc**: Rp 60,000 - 90,000/day  
- **150cc**: Rp 80,000 - 150,000/day
- **Premium brands** get 20% higher pricing

### 📅 **Sample Bookings (15 total)**
- **Mixed statuses**: pending, confirmed, active, completed
- **Realistic durations**: 1-7 days
- **Proper date ranges** with created timestamps

### 💳 **Sample Transactions (15 total)**
- **Payment methods**: bank_transfer, credit_card, e_wallet, cash
- **Realistic external IDs**: TXN20250925001 format
- **Mixed statuses**: pending, success, failed

## 🔑 Login Credentials

### 🔐 **Default Passwords**
| Role | Password |
|------|----------|
| Admin | `admin123` |
| Pemilik | `pemilik123` |
| Penyewa | `penyewa123` |

### 📧 **Admin Account**
- **Email**: `admin@luxurymoto.com`
- **Password**: `admin123`
- **Role**: Administrator with full access

### 🏢 **Pemilik (Owner) Accounts**
Dynamic names with format:
- **Email**: `[firstname].[lastname].pemilik@example.com`
- **Password**: `pemilik123`
- **Example**: `ahmad.pratama.pemilik@example.com`

### 🏠 **Penyewa (Renter) Accounts**
Dynamic names with format:
- **Email**: `[firstname].[lastname].penyewa@example.com`
- **Password**: `penyewa123`
- **Example**: `dewi.lestari.penyewa@example.com`

## 🎲 Dynamic Features

### 🔄 **Randomization Every Run**
- **Names**: 30 Indonesian first names × 24 last names = 720+ combinations
- **Motor models**: 4 brands × 6 models each = 24+ combinations
- **Plate numbers**: Realistic Indonesian format with random generation
- **Pricing**: Dynamic based on CC, brand, and random factors
- **Dates**: Spread across last 90 days for realistic timeline

### 🎯 **Realistic Business Logic**
- **Motors owned by Pemilik users only**
- **Bookings created by Penyewa users only**
- **Pricing varies by engine size and brand reputation**
- **Status distribution** reflects real business scenarios
- **Transaction records** match booking amounts

## 🛠️ Technical Details

### 🗃️ **Database Tables Managed**
- `users` - User accounts with roles
- `motors` - Motor inventory
- `tarif_rentals` - Pricing for each motor
- `penyewaans` - Rental bookings
- `transaksis` - Payment transactions
- `bagi_hasils` - Revenue sharing (optional)

### 🔄 **Safe Data Deletion Order**
1. `transaksis` (child)
2. `penyewaans` (child) 
3. `tarif_rentals` (child)
4. `bagi_hasils` (child)
5. `motors` (parent)
6. `users` (parent)

### 📈 **Auto-Increment Reset**
- SQLite sequence table cleaned
- Fresh ID numbering from 1
- No constraint conflicts

## 🎨 Customization

### 🏷️ **Adding More Names**
Edit the arrays in `DynamicTestDataSeeder.php`:
```php
private array $firstNames = [
    'Ahmad', 'Budi', 'Siti', // ... add more
];

private array $lastNames = [
    'Pratama', 'Sari', 'Wijaya', // ... add more  
];
```

### 🏍️ **Adding More Motor Models**
```php
private array $motorBrands = [
    'Honda' => ['Beat', 'Vario', 'New_Model'], // add models
    'NewBrand' => ['Model1', 'Model2'], // add brands
];
```

### 💰 **Adjusting Price Ranges**
```php
$basePrice = match($cc) {
    '100' => rand(30000, 60000), // adjust ranges
    '125' => rand(50000, 80000),
    '150' => rand(70000, 120000),
};
```

## 🎉 Success Verification

After running the seeder, verify success:

```bash
# Check record counts
php artisan tinker --execute="
echo 'Users: ' . App\Models\User::count() . PHP_EOL;
echo 'Motors: ' . App\Models\Motor::count() . PHP_EOL;
echo 'Bookings: ' . App\Models\Penyewaan::count() . PHP_EOL;
"

# Test admin login
# Visit: http://127.0.0.1:8000/login
# Email: admin@luxurymoto.com
# Password: admin123
```

## 🚨 Troubleshooting

### ❌ Foreign Key Constraint Errors
```bash
# If you see constraint errors, run:
php artisan migrate:fresh
php artisan db:seed --class=DynamicTestDataSeeder
```

### 🔄 **Regenerate Different Data**
```bash
# Simply run the seeder again for new random data:
php artisan db:seed --class=DynamicTestDataSeeder
```

### 📝 **Check Seeder Logs**
The seeder provides detailed output showing:
- Data deletion progress
- Creation counts for each table
- Final summary with login credentials

---

## 🎯 **Ready to Use!**

Your Laravel motor rental system now has **dynamic, realistic test data** that changes every time you run the seeder. Perfect for:

- ✅ **Development testing**
- ✅ **Demo presentations** 
- ✅ **User acceptance testing**
- ✅ **Performance testing with varied data**

**Happy Testing! 🚀**