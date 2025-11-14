<?php
session_start();

// Demo login handler (backend hazır olana kadar)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tckn = isset($_POST['tckn']) ? trim($_POST['tckn']) : '';
    
    // Demo kullanıcılar
    $demo_users = [
        '12345678901' => [
            'tckn' => '12345678901',
            'ad' => 'Ahmet',
            'soyad' => 'Yılmaz',
            'telefon' => '0532 123 4567',
            'dogum_tarihi' => '15.03.1990',
            'avatar' => null
        ],
        '98765432109' => [
            'tckn' => '98765432109',
            'ad' => 'Ayşe',
            'soyad' => 'Kaya',
            'telefon' => '0533 234 5678',
            'dogum_tarihi' => '22.07.1985',
            'avatar' => null
        ]
    ];
    
    // Kullanıcı kontrolü
    if (isset($demo_users[$tckn])) {
        // Session oluştur
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_data'] = $demo_users[$tckn];
        
        echo json_encode([
            'success' => true,
            'message' => 'Giriş başarılı'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Kullanıcı bulunamadı'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz istek'
    ]);
}
?>
