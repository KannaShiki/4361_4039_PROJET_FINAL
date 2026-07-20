<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Historique des Transactions - <?= esc($client['phone_number']) ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transactions du Client</h5>
                <div>
                    <a href="<?= base_url('/admin/client-accounts') ?>" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Retour aux comptes
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Numero:</strong> <?= esc($client['phone_number']) ?> | 
                    <strong>Solde actuel:</strong> <?= number_format($client['balance'], 0, ',', ' ') ?> Ar
                </div>

                <?php if (empty($transactions)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune transaction enregistree pour ce client
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Operation</th>
                                    <th>Montant (Ar)</th>
                                    <th>Frais (Ar)</th>
                                    <th>Solde Avant (Ar)</th>
                                    <th>Solde Apres (Ar)</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction['created_at'] ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $transaction['operation_name'] ?></span>
                                        </td>
                                        <td><?= number_format($transaction['amount'], 0, ',', ' ') ?></td>
                                        <td><?= number_format($transaction['fee'], 0, ',', ' ') ?></td>
                                        <td><?= number_format($transaction['balance_before'], 0, ',', ' ') ?></td>
                                        <td><?= number_format($transaction['balance_after'], 0, ',', ' ') ?></td>
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
