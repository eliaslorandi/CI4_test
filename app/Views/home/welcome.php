<?php
// home/welcome.php - Página inicial
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo - Task App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/auth/style.css">
</head>
<body>
    <div class="access-card">
        <h1 class="fw-bold mb-2 text-center text-primary">
            <i class="bi bi-check2-square me-2"></i> Task App
        </h1>
        
        <p class="text-muted text-center mb-5">
            Organize suas tarefas, defina prioridades e aumente sua produtividade com nosso sistema de gestão simples e eficiente.
        </p>

        <!-- Botões -->
        <div class="d-grid gap-3">
            <a href="<?= site_url('auth/login') ?>" class="btn btn-primary fw-600 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i> Fazer Login
            </a>
            <a href="<?= site_url('auth/register') ?>" class="btn btn-outline-primary fw-600 py-2">
                <i class="bi bi-person-plus me-2"></i> Criar Conta
            </a>
        </div>

        <!-- Divider -->
        <div class="d-flex align-items-center my-4">
            <hr class="flex-grow-1">
            <span class="text-muted px-3 small">ou</span>
            <hr class="flex-grow-1">
        </div>

        <!-- Funcionalidades -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="feature-box text-center">
                    <i class="bi bi-lightning-charge text-warning mb-2"></i>
                    <h6 class="fw-600 mb-1">Rápido</h6>
                    <small class="text-muted">Interface intuitiva e responsiva</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-box text-center">
                    <i class="bi bi-shield-check text-success mb-2"></i>
                    <h6 class="fw-600 mb-1">Seguro</h6>
                    <small class="text-muted">Dados criptografados e protegidos</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-box text-center">
                    <i class="bi bi-graph-up text-info mb-2"></i>
                    <h6 class="fw-600 mb-1">Produtivo</h6>
                    <small class="text-muted">Gerencie tarefas com facilidade</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-box text-center">
                    <i class="bi bi-cloud-sync text-primary mb-2"></i>
                    <h6 class="fw-600 mb-1">Sincronizado</h6>
                    <small class="text-muted">Acesse de qualquer lugar</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/shared/config.js"></script>
    <script src="/js/shared/constants.js"></script>
</body>
</html>
