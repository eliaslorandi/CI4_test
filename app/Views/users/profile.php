<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Task App</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">Meu Perfil</h2>

        <div class="card border-light shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-600 text-muted">Nome</label>
                    <p class="form-control-plaintext fs-5"><?= $user['name'] ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-600 text-muted">Email</label>
                    <p class="form-control-plaintext fs-5"><?= $user['email'] ?></p>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-600 text-muted">Membro desde</label>
                    <p class="form-control-plaintext fs-5"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="<?= base_url('/user/edit') ?>" class="btn btn-primary fw-600">
                <i class="bi bi-pencil-square me-2"></i> Editar Perfil
            </a>
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-secondary fw-600">
                <i class="bi bi-list-check me-2"></i> Voltar às Tarefas
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
