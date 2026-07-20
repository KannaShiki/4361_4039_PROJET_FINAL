<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Gestion des Baremes de Frais</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ajouter un Bareme de Frais</h5>
            </div>
            <div class="card-body">
                <form action="/admin/add-fee-bracket" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="operation_type_id" class="form-label">Type d'Operation</label>
                        <select class="form-select" id="operation_type_id" name="operation_type_id" required>
                            <option value="">Selectionner...</option>
                            <?php foreach ($operationTypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_amount" class="form-label">Montant Minimum (Ar)</label>
                        <input type="number" class="form-control" id="min_amount" name="min_amount" 
                               step="0.01" min="0" placeholder="Ex: 0" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_amount" class="form-label">Montant Maximum (Ar)</label>
                        <input type="number" class="form-control" id="max_amount" name="max_amount" 
                               step="0.01" min="0" placeholder="Ex: 10000" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee_amount" class="form-label">Frais Fixe (Ar)</label>
                        <input type="number" class="form-control" id="fee_amount" name="fee_amount" 
                               step="0.01" min="0" placeholder="Ex: 100" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee_percentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="fee_percentage" name="fee_percentage" 
                               step="0.01" min="0" max="100" placeholder="Ex: 0" value="0">
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
                <h5 class="mb-0">Liste des Baremes de Frais</h5>
            </div>
            <div class="card-body">
                <?php if (empty($feeBrackets)): ?>
                    <p class="text-muted">Aucun bareme de frais configure</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Operation</th>
                                    <th>Tranche (Min - Max)</th>
                                    <th>Frais Fixe</th>
                                    <th>Pourcentage</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feeBrackets as $bracket): ?>
                                    <tr>
                                        <td><?= $bracket['id'] ?></td>
                                        <td><span class="badge bg-secondary"><?= $bracket['operation_name'] ?></span></td>
                                        <td>
                                            <strong><?= number_format($bracket['min_amount'], 0, ',', ' ') ?></strong> Ar - 
                                            <strong><?= number_format($bracket['max_amount'], 0, ',', ' ') ?></strong> Ar
                                        </td>
                                        <td><?= number_format($bracket['fee_amount'], 0, ',', ' ') ?> Ar</td>
                                        <td><?= $bracket['fee_percentage'] ?>%</td>
                                        <td>
                                            <a href="/admin/edit-fee-bracket/<?= $bracket['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <a href="/admin/delete-fee-bracket/<?= $bracket['id'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Etes-vous sur de vouloir supprimer ce bareme ?')">
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
