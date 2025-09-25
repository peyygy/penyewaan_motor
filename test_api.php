<?php

// Simple API Tester Script
echo "=== Laravel Motor Rental API Tester ===\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    $headers = array_merge($defaultHeaders, $headers);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_error($ch)) {
        echo "CURL Error: " . curl_error($ch) . "\n";
        return null;
    }
    
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

// Test 1: Register new user
echo "1. Testing Registration...\n";
$registerData = [
    'nama' => 'API Test User',
    'email' => 'apitest@example.com',
    'no_tlpn' => '081999888777',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'penyewa'
];

$response = makeRequest("$baseUrl/auth/register", 'POST', $registerData);
if ($response) {
    echo "Status: {$response['status_code']}\n";
    if ($response['status_code'] == 201) {
        echo "✓ Registration successful!\n";
        echo "User ID: " . $response['body']['data']['user']['id'] . "\n";
        echo "Token: " . substr($response['body']['data']['access_token'], 0, 20) . "...\n";
    } else {
        echo "✗ Registration failed\n";
        print_r($response['body']);
    }
}
echo "\n";

// Test 2: Login Admin
echo "2. Testing Admin Login...\n";
$loginData = [
    'email' => 'admin@sewa.com',
    'password' => '123456'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $loginData);
if ($response) {
    echo "Status: {$response['status_code']}\n";
    if ($response['status_code'] == 200) {
        echo "✓ Admin login successful!\n";
        $adminToken = $response['body']['data']['access_token'];
        echo "Admin Token: " . substr($adminToken, 0, 20) . "...\n";
        
        // Test Admin API
        echo "\n3. Testing Admin API - Get Users...\n";
        $adminResponse = makeRequest("$baseUrl/admin/users", 'GET', null, ["Authorization: Bearer $adminToken"]);
        if ($adminResponse && $adminResponse['status_code'] == 200) {
            echo "✓ Admin can access user list\n";
            echo "Total users: " . $adminResponse['body']['data']['total'] . "\n";
        } else {
            echo "✗ Admin API failed\n";
            if ($adminResponse) print_r($adminResponse['body']);
        }
        
        // Test Create User via Admin
        echo "\n4. Testing Admin Create User...\n";
        $newUserData = [
            'nama' => 'Admin Created User',
            'email' => 'admincreated' . time() . '@example.com',
            'no_tlpn' => '081777666555',
            'password' => 'password123',
            'role' => 'penyewa'
        ];
        
        $createResponse = makeRequest("$baseUrl/admin/users", 'POST', $newUserData, ["Authorization: Bearer $adminToken"]);
        if ($createResponse && $createResponse['status_code'] == 201) {
            echo "✓ Admin successfully created user\n";
            echo "New User ID: " . $createResponse['body']['data']['id'] . "\n";
        } else {
            echo "✗ Admin create user failed\n";
            if ($createResponse) print_r($createResponse['body']);
        }
        
    } else {
        echo "✗ Admin login failed\n";
        print_r($response['body']);
    }
}
echo "\n";

// Test 3: Login Owner
echo "5. Testing Owner Login...\n";
$ownerLoginData = [
    'email' => 'pemilik1@sewa.com',
    'password' => '123456'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $ownerLoginData);
if ($response) {
    echo "Status: {$response['status_code']}\n";
    if ($response['status_code'] == 200) {
        echo "✓ Owner login successful!\n";
        $ownerToken = $response['body']['data']['access_token'];
        echo "Owner Token: " . substr($ownerToken, 0, 20) . "...\n";
        
        // Test Owner API - Get Motors
        echo "\n6. Testing Owner API - Get My Motors...\n";
        $motorResponse = makeRequest("$baseUrl/owner/motors", 'GET', null, ["Authorization: Bearer $ownerToken"]);
        if ($motorResponse && $motorResponse['status_code'] == 200) {
            echo "✓ Owner can access motor list\n";
            echo "Total motors: " . $motorResponse['body']['data']['total'] . "\n";
        } else {
            echo "✗ Owner motor API failed\n";
            if ($motorResponse) print_r($motorResponse['body']);
        }
        
        // Test Create Motor
        echo "\n7. Testing Owner Create Motor...\n";
        $newMotorData = [
            'merk' => 'Honda API Test',
            'tipe_cc' => '125',
            'no_plat' => 'B' . time() . 'API'
        ];
        
        $createMotorResponse = makeRequest("$baseUrl/owner/motors", 'POST', $newMotorData, ["Authorization: Bearer $ownerToken"]);
        if ($createMotorResponse && $createMotorResponse['status_code'] == 201) {
            echo "✓ Owner successfully created motor\n";
            echo "New Motor ID: " . $createMotorResponse['body']['data']['id'] . "\n";
            echo "Motor Merk: " . $createMotorResponse['body']['data']['merk'] . "\n";
        } else {
            echo "✗ Owner create motor failed\n";
            if ($createMotorResponse) print_r($createMotorResponse['body']);
        }
        
    } else {
        echo "✗ Owner login failed\n";
        print_r($response['body']);
    }
}

// Test 4: Login Renter
echo "\n8. Testing Renter Login...\n";
$renterLoginData = [
    'email' => 'penyewa1@sewa.com',
    'password' => '123456'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $renterLoginData);
if ($response) {
    echo "Status: {$response['status_code']}\n";
    if ($response['status_code'] == 200) {
        echo "✓ Renter login successful!\n";
        $renterToken = $response['body']['data']['access_token'];
        echo "Renter Token: " . substr($renterToken, 0, 20) . "...\n";
        
        // Test Available Motors
        echo "\n9. Testing Get Available Motors...\n";
        $availableResponse = makeRequest("$baseUrl/renter/motors", 'GET', null, ["Authorization: Bearer $renterToken"]);
        if ($availableResponse && $availableResponse['status_code'] == 200) {
            echo "✓ Renter can see available motors\n";
            echo "Available motors: " . $availableResponse['body']['data']['total'] . "\n";
            
            // If motors available, try to book one
            if ($availableResponse['body']['data']['total'] > 0) {
                $firstMotor = $availableResponse['body']['data']['data'][0];
                echo "\n10. Testing Create Booking...\n";
                echo "Attempting to book Motor ID: " . $firstMotor['id'] . " - " . $firstMotor['merk'] . "\n";
                $bookingData = [
                    'motor_id' => $firstMotor['id'],
                    'tanggal_mulai' => date('Y-m-d', strtotime('+1 day')),
                    'tanggal_selesai' => date('Y-m-d', strtotime('+3 days')),
                    'tipe_durasi' => 'harian',
                    'harga' => 150000,
                    'catatan' => 'Test booking via API'
                ];
                
                $bookingResponse = makeRequest("$baseUrl/renter/penyewaans", 'POST', $bookingData, ["Authorization: Bearer $renterToken"]);
                if ($bookingResponse && $bookingResponse['status_code'] == 201) {
                    echo "✓ Renter successfully created booking\n";
                    echo "Booking ID: " . $bookingResponse['body']['data']['id'] . "\n";
                    echo "Motor: " . $bookingResponse['body']['data']['motor']['merk'] . " - " . $bookingResponse['body']['data']['motor']['no_plat'] . "\n";
                    
                    // Test Get My Bookings
                    echo "\n11. Testing Get My Bookings...\n";
                    $myBookingsResponse = makeRequest("$baseUrl/renter/penyewaans", 'GET', null, ["Authorization: Bearer $renterToken"]);
                    if ($myBookingsResponse && $myBookingsResponse['status_code'] == 200) {
                        echo "✓ Renter can see own bookings\n";
                        echo "Total bookings: " . $myBookingsResponse['body']['data']['total'] . "\n";
                    }
                } else {
                    echo "✗ Renter create booking failed\n";
                    if ($bookingResponse) print_r($bookingResponse['body']);
                }
            }
        } else {
            echo "✗ Get available motors failed\n";
            if ($availableResponse) print_r($availableResponse['body']);
        }
        
    } else {
        echo "✗ Renter login failed\n";
        print_r($response['body']);
    }
}

echo "\n=== Test Complete ===\n";
?>