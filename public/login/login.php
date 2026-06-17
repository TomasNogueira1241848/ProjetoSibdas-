<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedInfo Solutions — Área Restrita</title>
    <link rel="icon" href="../../assets/img/Logo empresa.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/fontawesome/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/1241848.css">
</head>

<body id="pagina-login">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-lg-4 col-md-6 col-sm-8 col-11">

                <div class="card p-4 shadow-sm">

                    <!-- Logo e título -->
                    <div class="text-center mb-4">
                        <img src="../../assets/img/Logo empresa.png" alt="MedInfo Solutions" id="login-logo">
                        <h5 class="fw-bold mt-3 mb-0">MedInfo Solutions</h5>
                        <p class="text-muted small mb-0">Hospital Inventory</p>
                        <hr class="divisor mx-auto mt-3">
                    </div>

                    <!-- Formulário -->
                    <form id="formLogin" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@"
                                    required>
                                <div class="invalid-feedback">Introduza um email válido.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="••••••••" required>
                                <div class="invalid-feedback">Introduza a password.</div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
                            </button>
                        </div>
                    </form>

                    <!-- Erro -->
                    <div id="erroLogin" class="alert alert-danger d-none p-2 text-center small">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        Email ou password incorretos.
                    </div>

                    <!-- Link para voltar ao site -->
                    <div class="text-center mt-3">
                        <a href="../index.php" class="text-muted small text-decoration-none">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar ao site
                        </a>
                    </div>

                </div>

                <!-- Rodapé simples -->
                <p class="text-center text-muted small mt-3">
                    &copy; 2025 MedInfo Solutions
                </p>

            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="../../assets/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- JS --->
    <script src="../../assets/js/1241848.js"></script>
</body>

</html>

