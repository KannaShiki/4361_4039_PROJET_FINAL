<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Situation des Comptes Clients</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des Clients et leurs Soldes</h5>
                <div>
                    <span class="badge bg-primary">Total Clients: <?= count($clients) ?></span>
                </div>
            </div>
            <div class="card-body">
                <form method="get" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-select" name="operator_filter" onchange="this.form.submit()">
                                <option value="">Tous les operateurs</option>
                                <option value="033" <?= isset($_GET['operator_filter']) && $_GET['operator_filter'] == '033' ? 'selected' : '' ?>>033</option>
                                <option value="037" <?= isset($_GET['operator_filter']) && $_GET['operator_filter'] == '037' ? 'selected' : '' ?>>037</option>
                                <option value="034" <?= isset($_GET['operator_filter']) && $_GET['operator_filter'] == '034' ? 'selected' : '' ?>>034</option>
                                <option value="038" <?= isset($_GET['operator_filter']) && $_GET['operator_filter'] == '038' ? 'selected' : '' ?>>038</option>
                            </select>
                        </div>
                    </div>
                </form>
                <?php if (empty($clients)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucun client enregistre
                    </div>
                <?php else: ?>
                    <?php 
                    $totalBalance = 0;
                    foreach ($clients as $client) {
                        $totalBalance += $client['balance'];
                    }
                    ?>
                    <div class="alert alert-success">
                        <strong>Solde total de tous les clients:</strong> 
                        <?= number_format($totalBalance, 0, ',', ' ') ?> Ar
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numero de Telephone</th>
                                    <th>Solde (Ar)</th>
                                    <th>Date de creation</th>
                                    <th>Derniere mise a jour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?= $client['id'] ?></td>
                                        <td>
                                            <strong><?= $client['phone_number'] ?></strong>
                                            <?php 
                                            $prefix = substr($client['phone_number'], 0, 3);
                                            ?>
                                            <span class="badge bg-info">Prefixe: <?= $prefix ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $client['balance'] >= 0 ? 'bg-success' : 'bg-danger' ?> fs-6">
                                                <?= number_format($client['balance'], 0, ',', ' ') ?> Ar
                                            </span>
                                        </td>
                                        <td><?= $client['created_at'] ?></td>
                                        <td><?= $client['updated_at'] ?></td>
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
