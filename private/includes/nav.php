<?php
/*
 * MedInfo Solutions — nav.php
 * Barra de navegacao superior.
 * (Comentarios meramente descritivos; nao alteram o codigo.)
 */
$assetPath = $assetPath ?? '../../assets';
?>
 
<!-- HEADER -->
<header class="container-fluid header-area-reservada text-white">
    <div class="row align-items-center">
        <div class="col-8 col-md-6 d-flex align-items-center p-3 gap-3">
            <img src="<?php echo $assetPath; ?>/img/Logo empresa.png" alt="<?php echo APP_NAME; ?>" class="header-logo">
 
            <div>
                <h5 class="mb-0 fw-bold"><?php echo APP_NAME; ?></h5>
                <small class="text-white-50"><?php echo APP_SUBTITLE; ?></small>
            </div>
        </div>
 
        <div class="col-4 col-md-6 text-end p-3">
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fa-regular fa-user"></i>
                    <span class="d-none d-sm-inline ms-2">
                        <?php echo htmlspecialchars(utilizador_nome()); ?>
                    </span>
                </button>
 
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text small">
                            <strong><?php echo htmlspecialchars(utilizador_nome()); ?></strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars(utilizador_email()); ?></span><br>
                            <span class="text-muted"><?php echo htmlspecialchars(perfil_nome()); ?></span>
                        </span>
                    </li>
 
                    <li>
                        <hr class="dropdown-divider">
                    </li>
 
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/login/logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>