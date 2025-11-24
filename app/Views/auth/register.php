<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Task App</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">Criar Conta</h2>
        
        <form method="POST" action="<?= url_to('AuthController::register') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label fw-600">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-600">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-600">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="form-text text-muted">Mínimo 8 caracteres</small>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label fw-600">Confirmar Senha</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
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

            <button type="submit" class="btn btn-primary w-100 fw-600 mb-3">
                <i class="bi bi-person-plus me-2"></i> Registrar
            </button>
        </form>

        <p class="text-center text-muted mb-0">
            Já tem conta? <a href="<?= base_url('/auth/login') ?>" class="text-primary fw-600">Faça login aqui</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
