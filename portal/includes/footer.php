        </div> <!-- .page-content -->

        <!-- Footer -->
        <footer class="page-footer">
            <p>&copy; <?php echo date('Y'); ?> Eğitim Portalı. Tüm hakları saklıdır.</p>
        </footer>
    </div> <!-- .main-content -->

    <!-- Bottom Navigation (Mobil) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-container">
            <a href="index.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Ana Sayfa</span>
            </a>
            <a href="egitimler.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'egitimler.php' ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap"></i>
                <span>Eğitimlerim</span>
            </a>
            <a href="kayit.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'kayit.php' ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i>
                <span>Eğitimler</span>
            </a>
            <a href="sertifikalarim.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'sertifikalarim.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                <span>Sertifikalar</span>
            </a>
            <a href="profil.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Profilim</span>
            </a>
        </div>
    </nav>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <?php if (isset($page_js)): ?>
        <script src="<?php echo $page_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
