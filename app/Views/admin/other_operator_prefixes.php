<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Prefixes Autres Operateurs</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ajouter un Prefixe</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/add-other-operator-prefix') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="prefix" class="form-label">Prefixe</label>
                                <input type="text" class="form-control" id="prefix" name="prefix" 
                                       placeholder="Ex: 032" required maxlength="3">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="operator_name" class="form-label">Nom de l'operateur</label>
                                <input type="text" class="form-control" id="operator_name" name="operator_name" 
                                       placeholder="Ex: Telma" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Prefixes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($prefixes)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucun prefixe enregistre
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Prefixe</th>
                                    <th>Nom de l'operateur</th>
                                    <th>Date de creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prefixes as $prefix): ?>
                                    <tr>
                                        <td><?= $prefix['id'] ?></td>
                                        <td>
                                            <strong><?= $prefix['prefix'] ?></strong>
                                        </td>
                                        <td><?= esc($prefix['operator_name']) ?></td>
                                        <td><?= $prefix['created_at'] ?></td>
                                        <td>
                                            <a href="<?= base_url('/admin/delete-other-operator-prefix/' . $prefix['id']) ?>" 
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
