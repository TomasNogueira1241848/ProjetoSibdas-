<?php
$areaPath = $areaPath ?? '';
$activeMenu = $activeMenu ?? 'dashboard';

if (!function_exists('menuAtivo')) {
    function menuAtivo($menu, $activeMenu)
    {
        return $menu === $activeMenu ? ' active' : '';
    }
}
?>

<!-- SIDEBAR / MENU -->
<aside class="col-12 col-md-3 col-lg-2 sidebar p-3">
    <p class="text-uppercase text-muted small fw-bold mb-3 mt-2 d-none d-md-block">Menu</p>

    <nav class="nav nav-pills flex-row flex-md-column flex-nowrap overflow-auto gap-2 w-100">
        <?php if (tem_permissao('dashboard', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>index.php" class="nav-link<?php echo menuAtivo('dashboard', $activeMenu); ?>">
                <i class="fa-solid fa-gauge me-2"></i> Dashboard
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('equipamentos', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>equipamentos/equipamentos.php" class="nav-link<?php echo menuAtivo('equipamentos', $activeMenu); ?>">
                <i class="fa-solid fa-stethoscope me-2"></i> Equipamentos
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('fornecedores', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>fornecedores/fornecedores.php" class="nav-link<?php echo menuAtivo('fornecedores', $activeMenu); ?>">
                <i class="fa-solid fa-truck me-2"></i> Fornecedores
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('localizacoes', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>localizacoes/localizacoes.php" class="nav-link<?php echo menuAtivo('localizacoes', $activeMenu); ?>">
                <i class="fa-solid fa-location-dot me-2"></i> Localizações
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('documentacao', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>documentacao/documentacao.php" class="nav-link<?php echo menuAtivo('documentacao', $activeMenu); ?>">
                <i class="fa-solid fa-file-medical me-2"></i> Documentação
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('contratos', 'ver')): ?>
            <a href="<?php echo $areaPath; ?>contratos/contratos.php" class="nav-link<?php echo menuAtivo('contratos', $activeMenu); ?>">
                <i class="fa-solid fa-shield me-2"></i> Garantias e Contratos
            </a>
        <?php endif; ?>

        <?php if (tem_permissao('conteudos', 'editar')): ?>
            <a href="<?php echo $areaPath; ?>conteudos/conteudos.php" class="nav-link<?php echo menuAtivo('conteudos', $activeMenu); ?>">
                <i class="fa-solid fa-pen-to-square me-2"></i> Gestão de Conteúdos
            </a>
        <?php endif; ?>
    </nav>
</aside>
