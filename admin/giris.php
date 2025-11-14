<?php
session_start();

// Eğer zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Demo için sabit değerler (backend olunca değişecek)
    if ($email === 'admin@egitim.com' && $password === '123456') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_name'] = 'Sistem Yöneticisi';
        header('Location: index.php');
        exit;
    } else {
        $error = 'E-posta veya şifre hatalı!';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Giriş - Eğitim Platformu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-bg: #0f1c2e;
            --card-bg: #1e2d44;
            --blue: #3b82f6;
            --blue-glow: rgba(59, 130, 246, 0.4);
            --text-primary: #ffffff;
            --text-secondary: #8b9cbc;
            --border-color: #2a3f5f;
            --error-red: #ef4444;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #0f1c2e 0%, #1a2942 50%, #0f1c2e 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            padding: 2rem;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo-placeholder {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--blue);
            box-shadow: 0 8px 32px var(--blue-glow);
        }

        .logo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 20px;
        }

        .login-box {
            width: 100%;
            background: linear-gradient(135deg, rgba(30, 45, 68, 0.95) 0%, rgba(26, 41, 66, 0.9) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.2rem 1rem 3.2rem;
            background: rgba(10, 22, 40, 0.5);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--blue);
            background: rgba(10, 22, 40, 0.7);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: rgba(139, 156, 188, 0.4);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-red);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid rgba(239, 68, 68, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--blue) 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 16px var(--blue-glow);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--blue-glow);
        }

        .login-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(59, 130, 246, 0.1);
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }

            .login-box {
                padding: 2rem 1.5rem;
            }

            .logo-placeholder {
                width: 100px;
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .login-box {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo-placeholder">
                <!-- Logo admin panelden yüklenecek -->
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-box">
            <div class="login-header">
                <h2>Admin Girişi</h2>
                <p>Devam etmek için giriş bilgilerinizi girin</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="admin@egitim.com"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Şifre</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </button>
            </form>

            <div class="login-footer">
                Giriş bilgilerinizi unuttuysanız sistem yöneticinizle iletişime geçin.
            </div>
        </div>
    </div>

    <script>
        // Brute Force Koruması
        const MAX_ATTEMPTS = 5;
        const LOCKOUT_TIME = 30 * 1000; // 30 saniye (test için - production'da 5 * 60 * 1000 yapın)

        function checkLoginAttempts() {
            const attempts = JSON.parse(localStorage.getItem('loginAttempts') || '{"count": 0, "lockoutUntil": 0}');
            const now = Date.now();

            if (attempts.lockoutUntil > now) {
                const remainingTime = Math.ceil((attempts.lockoutUntil - now) / 1000);
                alert(`Çok fazla hatalı giriş denemesi yaptınız. ${remainingTime} saniye sonra tekrar deneyebilirsiniz.`);
                return false;
            }

            if (attempts.lockoutUntil > 0 && attempts.lockoutUntil <= now) {
                // Reset attempts after lockout period
                localStorage.setItem('loginAttempts', JSON.stringify({"count": 0, "lockoutUntil": 0}));
            }

            return true;
        }

        function recordFailedAttempt() {
            const attempts = JSON.parse(localStorage.getItem('loginAttempts') || '{"count": 0, "lockoutUntil": 0}');
            attempts.count++;

            if (attempts.count >= MAX_ATTEMPTS) {
                attempts.lockoutUntil = Date.now() + LOCKOUT_TIME;
                alert('Çok fazla hatalı giriş denemesi yaptınız. 30 saniye boyunca giriş yapamazsınız.');
            }

            localStorage.setItem('loginAttempts', JSON.stringify(attempts));
        }

        // Form submit kontrolü
        const loginForm = document.querySelector('form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                if (!checkLoginAttempts()) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Sayfa yüklendiğinde hatalı girişi kontrol et
        <?php if (isset($error)): ?>
        recordFailedAttempt();
        <?php endif; ?>

        // Başarılı giriş durumunda attempts'i sıfırla
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        localStorage.removeItem('loginAttempts');
        <?php endif; ?>
    </script>
</body>
</html>
