<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo | Task App</title>

    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>

<body>

    <div class="welcome-card text-center">
        <h1 class="fw-bold mb-3 text-primary">Task App</h1>
        <p class="text-muted mb-5">
            Organize suas tarefas, defina prioridades e aumente sua produtividade com nosso sistema de gestão simples e eficiente.
        </p>

        <div class="d-grid gap-3">
            <a href="<?= site_url('auth/login') ?>" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i> FAZER LOGIN
            </a>
            <a href="<?= site_url('auth/register') ?>" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="bi bi-person-plus me-2"></i> CRIAR UMA CONTA
            </a>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>