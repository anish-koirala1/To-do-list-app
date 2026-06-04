<?php
/**
 * Page Footer Template - footer.php
 * 
 * Closes the main content container and renders the page footer.
 * This template is included at the bottom of all page views.
 * 
 * Also loads the global JavaScript file for interactive features.
 */
?>
<!-- Close main content container -->
</main>

<!-- Site footer -->
<footer class="site-footer">
    <div class="footer-inner">
        <!-- Copyright year is dynamically updated -->
        <span>&copy; <?= date('Y') ?> APSU</span>
    </div>
</footer>

<!-- Global JavaScript for interactive features (password toggle, search, etc) -->
<script src="<?= $baseUrl ?? '../' ?>assets/js/main.js"></script>
</body>
</html>