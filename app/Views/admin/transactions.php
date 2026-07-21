<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Toutes les Transactions</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Transactions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune transaction enregistree
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Operation</th>
                                    <th>Montant</th>
                                    <th>Frais</th>
                                    <th>Solde avant</th>
                                    <th>Solde apres</th>
                                    <th>Destinataire</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= substr($transaction['created_at'], 0, 16) ?></td>
                                        <td><?= esc($transaction['client_phone']) ?></td>
                                        <td><?= esc($transaction['operation_name']) ?></td>
                                        <td><?= number_format($transaction['amount'], 0, ',', ' ') ?> Ar</td>
                                        <td class="<?= $transaction['fee'] > 0 ? 'text-danger' : 'text-muted' ?>">
                                            <?= number_format($transaction['fee'], 0, ',', ' ') ?> Ar
                                        </td>
                                        <td><?= number_format($transaction['balance_before'], 0, ',', ' ') ?> Ar</td>
                                        <td><?= number_format($transaction['balance_after'], 0, ',', ' ') ?> Ar</td>
                                        <td><?= $transaction['recipient_phone'] ? esc($transaction['recipient_phone']) : '-' ?></td>
                                        <td><?= esc($transaction['description']) ?></td>
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
