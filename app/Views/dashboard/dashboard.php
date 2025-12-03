<?php
// dashboard/index.php - Página principal do dashboard
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/dashboard/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-check2-square me-2"></i>Task App
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/user/profile">
                            <i class="bi bi-person me-1"></i><?= session()->get('user_name') ?? 'Perfil' ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/logout">
                            <i class="bi bi-box-arrow-right me-1"></i>Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="h3 mb-3">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-list-task text-primary" style="font-size: 2rem;"></i>
                        <h5 class="card-title mt-3">Total de Tarefas</h5>
                        <p class="card-text display-6 text-primary"><?= $total_tasks ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        <h5 class="card-title mt-3">Concluídas</h5>
                        <p class="card-text display-6 text-success"><?= $tasks_completed ?? 0 ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                        <h5 class="card-title mt-3">Pendentes</h5>
                        <p class="card-text display-6 text-warning"><?= $tasks_pending ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Minhas Tarefas
                        </h5>
                        <button class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Nova Tarefa
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-center py-5">
                            Nenhuma tarefa criada ainda. Comece criando uma nova tarefa!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/shared/config.js"></script>
    <script src="/js/shared/constants.js"></script>
</body>
</html>
