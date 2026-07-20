<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Historique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/client/dashboard') ?>">Mobile Money</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('/client/dashboard') ?>">Retour</a>
                <a class="nav-link" href="<?= base_url('/client/logout') ?>">Deconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Historique des Operations</h5>
            </div>
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <p class="text-muted">Aucune operation enregistree</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Montant (Ar)</th>
                                    <th>Frais (Ar)</th>
                                    <th>Solde Avant (Ar)</th>
                                    <th>Solde Apres (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?= esc($transaction['created_at']) ?></td>
                                    <td><?= esc($transaction['description']) ?></td>
                                    <td><?= number_format($transaction['amount'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['fee'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['balance_before'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['balance_after'], 2, ',', ' ') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
