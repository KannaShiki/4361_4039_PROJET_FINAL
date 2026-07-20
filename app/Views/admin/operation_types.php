<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Gestion des Types d'Operations</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ajouter un Type d'Operation</h5>
            </div>
            <div class="card-body">
                <form action="/admin/add-operation-type" method="post">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" 
                               placeholder="Ex: depot" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Ex: Dépôt" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Description de l'operation"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Types d'Operations</h5>
            </div>
            <div class="card-body">
                <?php if (empty($operationTypes)): ?>
                    <p class="text-muted">Aucun type d'operation configure</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($operationTypes as $type): ?>
                                    <tr>
                                        <td><?= $type['id'] ?></td>
                                        <td><code><?= $type['code'] ?></code></td>
                                        <td><strong><?= $type['name'] ?></strong></td>
                                        <td><?= $type['description'] ?? '-' ?></td>
                                        <td>
                                            <a href="/admin/delete-operation-type/<?= $type['id'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Etes-vous sur de vouloir supprimer ce type d\'operation ?')">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
