<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Task App</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">Editar Perfil</h2>
        
        <form method="POST" action="<?= url_to('UserController::update') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label fw-600">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-600">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
            </div>

            <hr class="my-4">

            <h6 class="fw-600 text-secondary mb-3">Alterar Senha (Opcional)</h6>

            <div class="mb-3">
                <label for="password" class="form-label fw-600">Nova Senha</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted d-block mt-1">Deixe em branco para não alterar. Mínimo 8 caracteres</small>
            </div>

            <div class="mb-4">
                <label for="password_confirm" class="form-label fw-600">Confirmar Senha</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm">
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mb-3"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (isset($errors) && is_array($errors)): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-primary fw-600">
                    <i class="bi bi-check-circle me-2"></i> Salvar Alterações
                </button>
                <a href="<?= base_url('/user/profile') ?>" class="btn btn-outline-secondary fw-600">
                    <i class="bi bi-x-circle me-2"></i> Cancelar
                </a>
            </div>
        </form>

        <hr class="my-4">

        <div class="alert alert-danger p-3">
            <h6 class="fw-600 mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i> Zona de Perigo
            </h6>
            <p class="mb-2 small">Deletar sua conta é permanente e não pode ser desfeito. Todos os seus dados serão removidos.</p>
            <button class="btn btn-danger btn-sm fw-600" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <i class="bi bi-trash me-1"></i> Deletar Conta
            </button>
        </div>
    </div>

    <!-- Modal de Confirmação de Deleção -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger-subtle border-danger">
                    <h5 class="modal-title fw-600">
                        <i class="bi bi-exclamation-circle me-2 text-danger"></i> Confirmar Deleção
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Você tem certeza que deseja deletar sua conta? 
                        <strong class="text-danger">Esta ação é irreversível!</strong>
                    </p>
                    <form method="POST" action="<?= url_to('UserController::delete') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label fw-600">Confirme sua senha para deletar</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-600" form="deleteForm">
                        <i class="bi bi-trash me-1"></i> Deletar Minha Conta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
