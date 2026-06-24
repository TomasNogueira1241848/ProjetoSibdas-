<?php

require_once __DIR__ . '/../../private/includes/funcoes.php';

registar_evento_sistema('autenticacao', 'login', 'logout', 'Logout efetuado.');
logout_and_redirect('/public/login/login.php');