    </main>
    <?php
    $flashMessage = $_SESSION['mensaje'] ?? null;
    unset($_SESSION['mensaje']);
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.flashMessage = <?= json_encode($flashMessage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="js/alerts.js"></script>
    <script src="js/validations.js"></script>
</body>

</html>
