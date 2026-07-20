<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Rapports Financiers</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Gains par Type d'Operation</h5>
            </div>
            <div class="card-body">
                <?php if (empty($feesByOperation)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune donnee disponible
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type d'operation</th>
                                    <th>Nombre de transactions</th>
                                    <th>Frais totaux (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feesByOperation as $fee): ?>
                                    <tr>
                                        <td><?= esc($fee['operation_name']) ?></td>
                                        <td><?= $fee['transaction_count'] ?></td>
                                        <td><strong><?= number_format($fee['total_fees'], 2, ',', ' ') ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Gains par Operateur</h5>
            </div>
            <div class="card-body">
                <?php if (empty($feesByOperator)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune donnee disponible
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type d'operateur</th>
                                    <th>Nombre de transactions</th>
                                    <th>Frais totaux (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feesByOperator as $fee): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($fee['operator_type']) ?></strong>
                                        </td>
                                        <td><?= $fee['transaction_count'] ?></td>
                                        <td><strong><?= number_format($fee['total_fees'], 2, ',', ' ') ?></strong></td>
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

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Montants a Envoyer aux Autres Operateurs</h5>
            </div>
            <div class="card-body">
                <?php if (empty($amountsToSend)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucun montant a envoyer
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Operateur</th>
                                    <th>Prefixe</th>
                                    <th>Nombre de transactions</th>
                                    <th>Montant a envoyer (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($amountsToSend as $amount): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($amount['operator_name']) ?></strong>
                                        </td>
                                        <td><?= esc($amount['prefix']) ?></td>
                                        <td><?= $amount['transaction_count'] ?></td>
                                        <td>
                                            <strong class="text-danger">
                                                <?= number_format($amount['total_fees'], 2, ',', ' ') ?>
                                            </strong>
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
