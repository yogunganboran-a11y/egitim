        </div> <!-- .page-content -->
        
        <!-- Footer -->
        <footer class="page-footer">
            <p>&copy; <?php echo date('Y'); ?> Eğitim Portalı. Tüm hakları saklıdır.</p>
        </footer>
    </div> <!-- .main-content -->
    
    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <?php if (isset($page_js)): ?>
        <script src="<?php echo $page_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
