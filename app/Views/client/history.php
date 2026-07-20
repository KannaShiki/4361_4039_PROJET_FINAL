<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Historique</title>
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
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?= esc($transaction['created_at']) ?></td>
                                    <td>
                                        <?= esc($transaction['description']) ?>
                                        <?php if ($transaction['is_multi_send']): ?>
                                            <span class="badge bg-primary">Multi-Envoi</span>
                                        <?php endif; ?>
                                        <?php if ($transaction['include_withdrawal_fee']): ?>
                                            <span class="badge bg-info">Frais retrait inclus</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($transaction['amount'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['fee'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['balance_before'], 2, ',', ' ') ?></td>
                                    <td><?= number_format($transaction['balance_after'], 2, ',', ' ') ?></td>
                                    <td>
                                        <?php if ($transaction['is_multi_send']): ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#multiSendModal<?= $transaction['id'] ?>">
                                                Voir details
                                            </button>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
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

    <!-- Modals for multi-send details -->
    <?php foreach ($transactions as $transaction): ?>
        <?php if ($transaction['is_multi_send']): ?>
            <div class="modal fade" id="multiSendModal<?= $transaction['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Details du Multi-Envoi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Date:</strong> <?= esc($transaction['created_at']) ?></p>
                            <p><strong>Montant total:</strong> <?= number_format($transaction['amount'], 2, ',', ' ') ?> Ar</p>
                            <p><strong>Frais totaux:</strong> <?= number_format($transaction['fee'], 2, ',', ' ') ?> Ar</p>
                            <p><strong>Destinataires:</strong></p>
                            <ul>
                                <?php
                                $multiSendModel = new \App\Models\MultiSendRecipientModel();
                                $recipients = $multiSendModel->getRecipientsByTransactionId($transaction['id']);
                                foreach ($recipients as $recipient):
                                ?>
                                    <li>
                                        <?= esc($recipient['recipient_phone']) ?> - 
                                        <?= number_format($recipient['amount'], 2, ',', ' ') ?> Ar
                                        <?php if ($recipient['fee'] > 0): ?>
                                            (Frais retrait: <?= number_format($recipient['fee'], 2, ',', ' ') ?> Ar)
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
