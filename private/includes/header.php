<?php
/*
 * MedInfo Solutions — header.php
 * Cabecalho HTML comum a todas as paginas (meta, CSS, abertura do body).
 * (Comentarios meramente descritivos; nao alteram o codigo.)
 */
require_once __DIR__ . '/funcoes.php';
 
redirect_if_not_logged();
 
$pageTitle = $pageTitle ?? APP_NAME;
$assetPath = $assetPath ?? '../../assets';
$bodyClass = $bodyClass ?? 'pagina-area-reservada';
?>
 
<!DOCTYPE html>
<html lang="pt">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="icon" href="<?php echo $assetPath; ?>/img/Logo empresa.png" type="image/png">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/bootstrap/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/fontawesome/all.min.css">
    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
 
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/css/1241848.css">
 
    <!-- Flatpickr (calendário gráfico para campos de data) -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>/flatpickr/flatpickr.min.css">
    <script src="<?php echo $assetPath; ?>/flatpickr/flatpickr.js"></script>
</head>
 
<body class="<?php echo $bodyClass; ?>">