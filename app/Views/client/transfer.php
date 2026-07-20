<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Transfert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Transfert</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('/client/process-transfer') ?>" method="post">
                            <div class="mb-3">
                                <label for="recipient_phone" class="form-label">Numero du destinataire</label>
                                <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" 
                                       placeholder="Ex: 0341234567" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Montant (Ar)</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                                       placeholder="Ex: 50000" required min="0.01">
                                <div class="form-text">Les frais seront appliques selon le bareme en vigueur</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="include_withdrawal_fee" name="include_withdrawal_fee">
                                <label class="form-check-label" for="include_withdrawal_fee">
                                    Inclure les frais de retrait dans le montant envoye
                                </label>
                                <div class="form-text">Le destinataire recevra le montant exact sans frais de retrait</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info">Effectuer le transfert</button>
                                <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
