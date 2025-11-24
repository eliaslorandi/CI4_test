<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Tarefa - Task App</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div class="access-card">
        <h2 class="fw-bold mb-4 text-center text-primary">Criar Nova Tarefa</h2>
        
        <form method="POST" action="<?= url_to('TaskController::store') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="title" class="form-label fw-600">Título <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required placeholder="Título da tarefa">
                <small class="form-text text-muted d-block mt-1">Máximo 150 caracteres</small>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-600">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Descreva a tarefa..."><?= old('description') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label fw-600">Categoria <span class="text-danger">*</span></label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted d-block mt-1">
                    <a href="<?= base_url('/categories/create') ?>" target="_blank" class="text-primary">Criar nova categoria</a>
                </small>
            </div>

            <div class="mb-4">
                <label for="due_date" class="form-label fw-600">Data de Vencimento</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="<?= old('due_date') ?>">
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
                    <i class="bi bi-plus-circle me-2"></i> Criar Tarefa
                </button>
                <a href="<?= base_url('/tasks') ?>" class="btn btn-outline-secondary fw-600">
                    <i class="bi bi-x-circle me-2"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
