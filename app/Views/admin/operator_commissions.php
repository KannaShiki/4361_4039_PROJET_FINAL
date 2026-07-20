<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Commissions Inter-Operateurs</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ajouter une Commission</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/add-operator-commission') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="operator_prefix_id" class="form-label">Operateur</label>
                                <select class="form-select" id="operator_prefix_id" name="operator_prefix_id" required>
                                    <option value="">Selectionner un operateur</option>
                                    <?php if (isset($operators)): ?>
                                        <?php foreach ($operators as $operator): ?>
<option value="<?= $operator['id'] ?>"><?= $operator['operator_name'] ?> (<?= $operator['prefix'] ?>)</option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="commission_percentage" class="form-label">Pourcentage (%)</label>
                                <input type="number" class="form-control" id="commission_percentage" name="commission_percentage" 
                                       placeholder="Ex: 5" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="commission_amount" class="form-label">Montant fixe (Ar)</label>
                                <input type="number" class="form-control" id="commission_amount" name="commission_amount" 
                                       placeholder="Ex: 100" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-2">
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
                <h5 class="mb-0">Liste des Commissions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($commissions)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune commission enregistree
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Operateur</th>
                                    <th>Prefixe</th>
                                    <th>Pourcentage</th>
                                    <th>Montant fixe (Ar)</th>
                                    <th>Date de creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commissions as $commission): ?>
                                    <tr>
                                        <td><?= $commission['id'] ?></td>
                                        <td>
                                            <strong><?= esc($commission['operator_name']) ?></strong>
                                        </td>
                                        <td><?= $commission['prefix'] ?></td>
                                        <td><?= $commission['commission_percentage'] ?>%</td>
                                        <td><?= number_format($commission['commission_amount'], 0, ',', ' ') ?></td>
                                        <td><?= $commission['created_at'] ?></td>
                                        <td>
                                            <a href="<?= base_url('/admin/edit-operator-commission/' . $commission['id']) ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <a href="<?= base_url('/admin/delete-operator-commission/' . $commission['id']) ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Etes-vous sur de vouloir supprimer cette commission ?')">
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
