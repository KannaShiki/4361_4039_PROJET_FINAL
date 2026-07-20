<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Gestion des Prefixes Operateurs</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ajouter un Prefixe</h5>
            </div>
            <div class="card-body">
                <form action="/admin/add-prefix" method="post">
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Prefixe (3 chiffres)</label>
                        <input type="text" class="form-control" id="prefix" name="prefix" 
                               pattern="[0-9]{3}" maxlength="3" placeholder="Ex: 033" required>
                        <div class="form-text">Le prefixe doit etre compose de 3 chiffres</div>
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
                <h5 class="mb-0">Liste des Prefixes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($prefixes)): ?>
                    <p class="text-muted">Aucun prefixe configure</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Prefixe</th>
                                    <th>Date de creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prefixes as $prefix): ?>
                                    <tr>
                                        <td><?= $prefix['id'] ?></td>
                                        <td><strong><?= $prefix['prefix'] ?></strong></td>
                                        <td><?= $prefix['created_at'] ?></td>
                                        <td>
                                            <a href="/admin/delete-prefix/<?= $prefix['id'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Etes-vous sur de vouloir supprimer ce prefixe ?')">
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
