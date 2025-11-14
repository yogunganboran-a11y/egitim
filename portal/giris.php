<?php
session_start();

// Zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Giriş Yap - Eğitim Portalı</title>
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Logo -->
            <div class="logo">
                <img src="assets/img/logo.png" alt="Logo" onerror="this.style.display='none'">
            </div>

            <!-- Başlık -->
            <div class="login-header">
                <h2>Sisteme Giriş Yapın</h2>
                <p>TCKN ile giriş yapabilirsiniz</p>
            </div>

            <!-- Hata/Başarı Mesajları -->
            <div id="messageBox" class="message-box" style="display: none;"></div>

            <!-- Giriş Formu -->
            <form id="loginForm" class="login-form">
                <!-- TCKN -->
                <div class="form-group">
                    <label for="tckn">
                        <i class="fas fa-id-card"></i> TCKN
                    </label>
                    <input
                        type="text"
                        id="tckn"
                        name="tckn"
                        placeholder="11 haneli TCKN giriniz"
                        maxlength="11"
                        required
                        autocomplete="off"
                        inputmode="numeric"
                        pattern="[0-9]*"
                    >
                    <span class="input-error" id="tcknError"></span>
                </div>

                <!-- CAPTCHA (5 başarısız denemeden sonra göster) -->
                <div id="captchaContainer" class="form-group" style="display: none;">
                    <label for="captcha">
                        <i class="fas fa-shield-alt"></i> Güvenlik Kodu
                    </label>
                    <div class="captcha-box">
                        <canvas id="captchaCanvas" width="200" height="60"></canvas>
                        <button type="button" id="refreshCaptcha" class="btn-refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <input 
                        type="text" 
                        id="captcha" 
                        name="captcha" 
                        placeholder="Yukarıdaki kodu giriniz"
                        autocomplete="off"
                    >
                    <span class="input-error" id="captchaError"></span>
                </div>

                <!-- Deneme Sayısı Uyarısı -->
                <div id="attemptWarning" class="attempt-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="attemptText"></span>
                </div>

                <!-- Giriş Butonu -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </button>

                <!-- Loading -->
                <div id="loadingSpinner" class="loading-spinner" style="display: none;">
                    <i class="fas fa-circle-notch fa-spin"></i>
                    <span>Giriş yapılıyor...</span>
                </div>
            </form>

            <!-- Alt Bilgi -->
            <div class="login-footer">
                <a href="https://wa.me/905XXXXXXXXX" target="_blank" class="whatsapp-support-btn">
                    <i class="fab fa-whatsapp"></i> WhatsApp Destek
                </a>
            </div>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>
</html>
