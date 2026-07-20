<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Admin Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/admin">
                <i class="bi bi-gear-fill"></i> Admin Mobile Money
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/prefixes">Prefixes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/operation-types">Types d'Operations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/fee-brackets">Baremes de Frais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/client-accounts">Comptes Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/transactions">Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/other-operator-prefixes">Prefixes Autres Operateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/operator-commissions">Commissions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/financial-reports">Rapports Financiers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/client" target="_blank">
                            <i class="bi bi-person"></i> Interface Client
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (session()->get('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->get('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->get('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
