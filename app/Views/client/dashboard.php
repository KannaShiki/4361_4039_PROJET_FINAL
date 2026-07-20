<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Dashboard Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/client/dashboard') ?>">Mobile Money</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <?= esc($client['phone_number']) ?>
                </span>
                <a class="nav-link" href="<?= base_url('/client/logout') ?>">Deconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Solde Actuel</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="display-4"><?= number_format($client['balance'], 2, ',', ' ') ?> Ar</h2>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Operations</h5>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->get('success') ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('/client/deposit') ?>" class="btn btn-success">Depot</a>
                            <a href="<?= base_url('/client/withdraw') ?>" class="btn btn-warning">Retrait</a>
                            <a href="<?= base_url('/client/transfer') ?>" class="btn btn-info">Transfert</a>
                            <a href="<?= base_url('/client/history') ?>" class="btn btn-secondary">Historique</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Informations du Compte</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Numero de telephone:</th>
                                <td><?= esc($client['phone_number']) ?></td>
                            </tr>
                            <tr>
                                <th>Solde:</th>
                                <td><?= number_format($client['balance'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                            <tr>
                                <th>Date de creation:</th>
                                <td><?= esc($client['created_at']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
