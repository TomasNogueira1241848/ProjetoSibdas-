<?php
$assetPath = $assetPath ?? '../../assets';
?>
 
<!-- Bootstrap JS -->
<script src="<?php echo $assetPath; ?>/bootstrap/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="<?php echo $assetPath; ?>/js/chart.umd.min.js"></script>
<!-- JS -->
<script src="<?php echo $assetPath; ?>/js/1241848.js"></script>
 
<!-- Ativação do Flatpickr em todos os campos de data -->
<script>
    if (typeof flatpickr !== 'undefined') {
        document.querySelectorAll('.flatpickr-data').forEach(function (campo) {
            if (!campo.getAttribute('placeholder')) {
                campo.setAttribute('placeholder', 'AAAA-MM-DD');
            }
        });
 
        flatpickr('.flatpickr-data', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }
</script>
 
<?php if (!empty($extraScripts)): ?>
    <?php foreach ($extraScripts as $script): ?>
        <script src="<?php echo htmlspecialchars($script); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
 
<?php if (!empty($pageScript)): ?>
    <script>
        <?php echo $pageScript; ?>
    </script>
<?php endif; ?>
</body>
 
</html>