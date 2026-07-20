<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Modifier Commission</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Modifier la Commission</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/update-operator-commission/' . $commission['id']) ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="operator_prefix_id" class="form-label">Operateur</label>
                                <select class="form-select" id="operator_prefix_id" name="operator_prefix_id" required>
                                    <option value="">Selectionner un operateur</option>
                                    <?php foreach ($operators as $operator): ?>
                                        <option value="<?= $operator['id'] ?>" 
                                                <?= $operator['id'] == $commission['operator_prefix_id'] ? 'selected' : '' ?>>
                                            <?= $operator['operator_name'] ?> (<?= $operator['prefix'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="commission_percentage" class="form-label">Pourcentage (%)</label>
                                <input type="number" class="form-control" id="commission_percentage" name="commission_percentage" 
                                       step="0.01" min="0" value="<?= $commission['commission_percentage'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="commission_amount" class="form-label">Montant fixe (Ar)</label>
                                <input type="number" class="form-control" id="commission_amount" name="commission_amount" 
                                       step="0.01" min="0" value="<?= $commission['commission_amount'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="bi bi-save"></i> Enregistrer
                                    </button>
                                    <a href="<?= base_url('/admin/operator-commissions') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
