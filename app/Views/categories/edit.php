<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - Task App</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">Editar Categoria</h2>
        
        <form method="POST" action="<?= url_to('CategoryController::update', $category['id']) ?>">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label for="name" class="form-label fw-600">Nome da Categoria <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $category['name']) ?>" required>
                <small class="form-text text-muted d-block mt-1">Máximo 100 caracteres</small>
            </div>

            <?php if (isset($errors) && is_array($errors)): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary fw-600">
                    <i class="bi bi-check-circle me-2"></i> Atualizar Categoria
                </button>
                <button type="button" class="btn btn-outline-secondary fw-600" onclick="window.history.back()">
                    <i class="bi bi-x-circle me-2"></i> Cancelar
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
