<?php
// login.php - View para formulário de login
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/auth/style.css">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">
            <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
        </h2>
        
        <div id="alertError" class="alert alert-danger alert-dismissible fade" style="display: none;">
            <ul id="errorList" class="mb-0"></ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div id="alertSuccess" class="alert alert-success alert-dismissible fade" style="display: none;">
            <i class="bi bi-check-circle me-2"></i>
            <span id="successMessage"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <form id="loginForm" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label fw-600">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="seu@email.com" required>
                <div class="error-message" id="emailError"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-600">Senha</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Sua senha" required>
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                <label class="form-check-label" for="remember">Lembrar de mim</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-600 mb-3" id="submitBtn">
                <span id="btnText">Entrar</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" 
                      role="status" aria-hidden="true" style="display: none;"></span>
            </button>
        </form>

        <p class="text-center text-muted mb-0">
            Não tem conta? 
            <a href="<?= site_url('auth/register') ?>" class="text-primary fw-600">Registre-se aqui</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/shared/config.js"></script>
    <script src="/js/shared/constants.js"></script>
    <script src="/js/shared/utils.js"></script>
    <script src="/js/auth/login.js"></script>
</body>
</html>
