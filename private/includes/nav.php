<?php
$assetPath = $assetPath ?? '../../assets';
$loginPath = $loginPath ?? '../../public/login.php';
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
                        <?php echo htmlspecialchars($_SESSION['utilizador']['nome'] ?? 'Utilizador'); ?>
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa-solid fa-key me-2"></i> Alterar password
                        </a>
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
