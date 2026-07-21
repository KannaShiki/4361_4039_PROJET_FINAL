<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Modifier le Bareme de Frais</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Formulaire de Modification</h5>
            </div>
            <div class="card-body">
                <form action="/admin/update-fee-bracket/<?= $feeBracket['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="operation_type_id" class="form-label">Type d'Operation</label>
                        <select class="form-select" id="operation_type_id" name="operation_type_id" required>
                            <option value="">Selectionner...</option>
                            <?php foreach ($operationTypes as $type): ?>
                                <option value="<?= $type['id'] ?>" 
                                        <?= $type['id'] == $feeBracket['operation_type_id'] ? 'selected' : '' ?>>
                                    <?= $type['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_amount" class="form-label">Montant Minimum (Ar)</label>
                        <input type="number" class="form-control" id="min_amount" name="min_amount" 
                               step="0.01" min="0" value="<?= $feeBracket['min_amount'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_amount" class="form-label">Montant Maximum (Ar)</label>
                        <input type="number" class="form-control" id="max_amount" name="max_amount" 
                               step="0.01" min="0" value="<?= $feeBracket['max_amount'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee_amount" class="form-label">Frais Fixe (Ar)</label>
                        <input type="number" class="form-control" id="fee_amount" name="fee_amount" 
                               step="0.01" min="0" value="<?= $feeBracket['fee_amount'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee_percentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="fee_percentage" name="fee_percentage" 
                               step="0.01" min="0" max="100" value="<?= $feeBracket['fee_percentage'] ?>">
                    </div>
                      <div class="mb-3">
                        <label for="promotion_percentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="promotion_percentage" name="promotion_percentage"
                               step="0.01" min="0" max="100" value="<?= $feeBracket['promotion_percentage'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                    <a href="/admin/fee-brackets" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
