<?php
$assetPath = $assetPath ?? '../../assets';
$jsVersion = $jsVersion ?? 'conteudos-bd-1';
?>
 
<!-- Bootstrap JS -->
<script src="<?php echo $assetPath; ?>/bootstrap/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="<?php echo $assetPath; ?>/js/chart.umd.min.js"></script>

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

<!-- JS principal -->
<script src="<?php echo $assetPath; ?>/js/1241848.js?v=<?php echo urlencode($jsVersion); ?>"></script>
 
</body>
 
</html>
